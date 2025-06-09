<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionLog;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\StockHistory;
use App\Models\User;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::where('stok', '>', 0)
            ->orderBy('nama_produk')
            ->get();
            
        $categories = $products->groupBy('category.nama_kategori');
        $customers = Customer::orderBy('nama')->get();
        
        $cart = [];
        $subtotal = 0;
        $tax = 0;
        $total = 0;
        
        return view('admin.pos.index', compact('products', 'categories', 'customers', 'cart', 'subtotal', 'tax', 'total'));
    }

    public function searchProducts(Request $request)
    {
        $query = $request->get('q');
        
        $products = Product::with(['category', 'unit'])
            ->where(function($q) use ($query) {
                $q->where('nama_produk', 'like', "%{$query}%")
                    ->orWhere('kode_produk', 'like', "%{$query}%");
            })
            ->where('stok', '>', 0)
            ->select('id', 'nama_produk', 'kode_produk', 'harga_jual', 'stok')
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    public function getCustomers()
    {
        $customers = Customer::select('id', 'nama', 'telepon')->get();
        return response()->json($customers);
    }

    public function processTransaction(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.produk_id' => 'required|exists:products,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
            'pelanggan_id' => 'required|exists:customers,id',
            'total_harga' => 'required|numeric|min:0',
            'total_bayar' => 'required|numeric|min:0',
            'total_kembali' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:cash,transfer,qris',
            'catatan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Buat transaksi baru
            $transaction = Transaction::create([
                'pelanggan_id' => $request->pelanggan_id,
                'total_harga' => $request->total_harga,
                'total_bayar' => $request->total_bayar,
                'total_kembali' => $request->total_kembali,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status' => 'selesai',
                'catatan' => $request->catatan
            ]);

            // Simpan detail transaksi
            foreach ($request->items as $item) {
                TransactionDetail::create([
                    'transaksi_id' => $transaction->id,
                    'produk_id' => $item['produk_id'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['subtotal']
                ]);

                // Update stok produk
                $product = Product::find($item['produk_id']);
                $product->stok -= $item['jumlah'];
                $product->save();
            }

            // Update data pelanggan jika ada
            if ($request->pelanggan_id) {
                $customer = Customer::find($request->pelanggan_id);
                $customer->total_pembelian += $request->total_harga;
                $customer->updateLoyaltyLevel();
                $customer->addPoints($request->total_harga);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'data' => [
                    'kode_transaksi' => $transaction->kode_transaksi,
                    'total_harga' => $transaction->total_harga,
                    'total_bayar' => $transaction->total_bayar,
                    'total_kembali' => $transaction->total_kembali
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function printReceipt($id)
    {
        $transaksi = Transaction::with(['details.product', 'customer', 'user'])
            ->findOrFail($id);

        $data = [
            'transaksi' => $transaksi,
            'tanggal' => $transaksi->created_at->format('d/m/Y H:i:s'),
            'kasir' => $transaksi->user ? $transaksi->user->name : 'System',
            'items' => $transaksi->details->map(function($detail) {
                return [
                    'nama' => $detail->product->nama_produk,
                    'jumlah' => $detail->jumlah,
                    'harga' => $detail->harga,
                    'subtotal' => $detail->subtotal
                ];
            }),
            'subtotal' => $transaksi->details->sum('subtotal'),
            'ppn' => $transaksi->details->sum('subtotal') * 0.11,
            'total' => $transaksi->total_harga,
            'bayar' => $transaksi->total_bayar,
            'kembali' => $transaksi->total_kembali,
            'metode' => $this->getMetodePembayaran($transaksi->metode_pembayaran)
        ];

        return view('admin.pos.receipt', $data);
    }

    private function getMetodePembayaran($metode)
    {
        return match($metode) {
            'cash' => 'Tunai',
            'card' => 'Kartu',
            'qris' => 'QRIS',
            default => $metode
        };
    }

    public function voidTransaction($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            $transaction->void();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibatalkan'
            ]);
        } catch (\Exception $e) {
            Log::error('Void transaction failed', [
                'transaction_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getTransactionStatus($id)
    {
        $transaction = Transaction::findOrFail($id);
        return response()->json([
            'status' => $transaction->status,
            'invoice_number' => $transaction->invoice_number
        ]);
    }

    protected function updateStock($product, $quantity, $type = 'out', $reference = null)
    {
        DB::transaction(function() use ($product, $quantity, $type, $reference) {
            // Update stock
            $product->stock += ($type == 'in' ? $quantity : -$quantity);
            $product->save();

            // Create history
            StockHistory::create([
                'product_id' => $product->id,
                'type' => $type,
                'quantity' => $quantity,
                'reference_type' => $reference ? get_class($reference) : null,
                'reference_id' => $reference ? $reference->id : null,
                'notes' => $reference ? 'Transaksi ' . ($type == 'in' ? 'pembelian' : 'penjualan') : 'Penyesuaian manual',
                'created_by' => Auth::id()
            ]);
        });
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card,qris',
            'amount_paid' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Handle pelanggan
            $pelangganId = null;
            if (isset($request->pelanggan)) {
                // Buat pelanggan baru
                $pelanggan = Customer::create([
                    'nama' => $request->pelanggan['nama'],
                    'telepon' => $request->pelanggan['telepon'] ?? null,
                    'alamat' => $request->pelanggan['alamat'] ?? null,
                    'total_pembelian' => 0,
                    'loyalty_level' => 'bronze',
                    'points' => 0
                ]);
                $pelangganId = $pelanggan->id;
            } else {
                $pelangganId = $request->pelanggan_id;
            }

            // Hitung total
            $subtotal = 0;
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['id']);
                if ($product->stok < $item['quantity']) {
                    throw new \Exception("Stok produk {$product->nama_produk} tidak mencukupi");
                }
                $subtotal += $product->harga_jual * $item['quantity'];
            }
            $tax = $subtotal * 0.11;
            $total = $subtotal + $tax;

            // Validasi jumlah bayar
            if ($request->amount_paid < $total) {
                throw new \Exception("Jumlah bayar kurang dari total");
            }

            // Buat transaksi
            $transaction = Transaction::create([
                'kode_transaksi' => 'TRX-' . date('YmdHis') . rand(100, 999),
                'pelanggan_id' => $pelangganId,
                'user_id' => Auth::id(),
                'total_harga' => $total,
                'total_bayar' => $request->amount_paid,
                'total_kembali' => $request->amount_paid - $total,
                'metode_pembayaran' => $request->payment_method,
                'status' => 'selesai',
                'catatan' => $request->notes
            ]);

            // Simpan detail transaksi dan update stok
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['id']);
                
                // Buat detail transaksi
                TransactionDetail::create([
                    'transaksi_id' => $transaction->id,
                    'produk_id' => $item['id'],
                    'jumlah' => $item['quantity'],
                    'harga' => $product->harga_jual,
                    'subtotal' => $product->harga_jual * $item['quantity']
                ]);

                // Update stok
                $product->stok -= $item['quantity'];
                $product->save();

                // Catat history stok
                StockHistory::create([
                    'produk_id' => $product->id,
                    'jenis' => 'keluar',
                    'jumlah' => $item['quantity'],
                    'stok_lama' => $product->stok + $item['quantity'], // stok sebelum dikurangi
                    'stok_baru' => $product->stok, // stok setelah dikurangi
                    'keterangan' => 'Penjualan POS',
                    'dibuat_oleh' => Auth::id()
                ]);
            }

            // Update data pelanggan jika ada
            if ($pelangganId) {
                $customer = Customer::find($pelangganId);
                $customer->total_pembelian += $total;
                $customer->updateLoyaltyLevel();
                $customer->addPoints($total);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil',
                'data' => [
                    'transaction_id' => $transaction->id,
                    'code' => $transaction->kode_transaksi,
                    'total' => $total,
                    'paid' => $request->amount_paid,
                    'change' => $request->amount_paid - $total
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 