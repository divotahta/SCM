<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['customer', 'user', 'details.product'])
            ->latest();

        // Filter berdasarkan status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // Filter berdasarkan customer
        if ($request->has('customer_id')) {
            $query->where('pelanggan_id', $request->customer_id);
        }

        $transactions = $query->paginate(10);
        $customers = Customer::all();
        // dd($customers);

        return view('Admin.transactions.index', compact('transactions', 'customers'));
    }

    public function create()
    {
        $products = Product::where('stok', '>', 0)->get();
        $customers = Customer::all();
        return view('Admin.transactions.create', compact('products', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'metode_pembayaran' => 'required|in:cash,transfer,qris',
            'total_bayar' => 'required|numeric|min:0',
            'catatan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Hitung total harga
            $total_harga = 0;
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['id']);
                if ($product->stok < $item['quantity']) {
                    throw new \Exception("Stok produk {$product->nama_produk} tidak mencukupi");
                }
                $total_harga += $product->harga_jual * $item['quantity'];
            }

            // Buat transaksi
            $transaction = Transaction::create([
                'pelanggan_id' => $request->customer_id,
                'user_id' => Auth::id(),
                'total_harga' => $total_harga,
                'total_bayar' => $request->total_bayar,
                'total_kembali' => $request->total_bayar - $total_harga,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status' => 'completed',
                'catatan' => $request->catatan
            ]);

            // Simpan detail transaksi
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['id']);
                
                TransactionDetail::create([
                    'transaksi_id' => $transaction->id,
                    'produk_id' => $product->id,
                    'jumlah' => $item['quantity'],
                    'harga' => $product->harga_jual,
                    'subtotal' => $product->harga_jual * $item['quantity']
                ]);

                // Kurangi stok
                $product->decrement('stok', $item['quantity']);
            }

            DB::commit();

            return redirect()->route('admin.transactions.show', $transaction)
                ->with('success', 'Transaksi berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['customer', 'user', 'details.product']);
        return view('Admin.transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        if ($transaction->status === 'void') {
            return back()->with('error', 'Transaksi yang sudah dibatalkan tidak dapat diedit');
        }

        $transaction->load(['details.product']);
        $products = Product::where('stok', '>', 0)->get();
        $customers = Customer::all();

        return view('Admin.transactions.edit', compact('transaction', 'products', 'customers'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->status === 'void') {
            return back()->with('error', 'Transaksi yang sudah dibatalkan tidak dapat diedit');
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'metode_pembayaran' => 'required|in:cash,transfer,qris',
            'total_bayar' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
            'status' => 'required|in:batal,selesai'
        ]);

        try {
            DB::beginTransaction();

            // Kembalikan stok lama
            foreach ($transaction->details as $detail) {
                $detail->product->increment('stok', $detail->jumlah);
            }

            // Hitung total harga baru
            $total_harga = 0;
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['id']);
                if ($product->stok < $item['quantity']) {
                    throw new \Exception("Stok produk {$product->nama_produk} tidak mencukupi");
                }
                $total_harga += $product->harga_jual * $item['quantity'];
            }

            // Update transaksi
            $transaction->update([
                'pelanggan_id' => $request->customer_id,
                'total_harga' => $total_harga,
                'total_bayar' => $request->total_bayar,
                'total_kembali' => $request->total_bayar - $total_harga,
                'metode_pembayaran' => $request->metode_pembayaran,
                'catatan' => $request->catatan,
                'status' => $request->status
            ]);

            // Hapus detail lama
            $transaction->details()->delete();

            // Simpan detail baru
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['id']);
                
                TransactionDetail::create([
                    'transaksi_id' => $transaction->id,
                    'produk_id' => $product->id,
                    'jumlah' => $item['quantity'],
                    'harga' => $product->harga_jual,
                    'subtotal' => $product->harga_jual * $item['quantity']
                ]);

                // Kurangi stok
                $product->decrement('stok', $item['quantity']);
            }

            DB::commit();

            return redirect()->route('admin.transactions.show', $transaction)
                ->with('success', 'Transaksi berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    // public function destroy(Transaction $transaction)
    // {
    //     if ($transaction->status === 'void') {
    //         return back()->with('error', 'Transaksi yang sudah dibatalkan tidak dapat dihapus');
    //     }

    //     try {
    //         DB::beginTransaction();

    //         // Kembalikan stok
    //         foreach ($transaction->details as $detail) {
    //             $detail->product->increment('stok', $detail->jumlah);
    //         }

    //         // Hapus transaksi
    //         $transaction->delete();

    //         DB::commit();

    //         return redirect()->route('admin.transactions.index')
    //             ->with('success', 'Transaksi berhasil dihapus');

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', $e->getMessage());
    //     }
    // }

    public function exportExcel()
    {
        $transactions = Transaction::with(['customer', 'user', 'details.product'])
            ->latest()
            ->get();

        return Excel::download(new TransactionsExport($transactions), 'transactions.xlsx');
    }

    public function exportPdf()
    {
        $transactions = Transaction::with(['customer', 'user', 'details.product'])
            ->latest()
            ->get();

        $pdf = PDF::loadView('Admin.transactions.pdf', compact('transactions'));
        return $pdf->download('transactions.pdf');
    }

    public function getTransactionCounts()
    {
        $pending = Transaction::where('status', 'pending')->count();
        $unpaid = Transaction::where('status', 'unpaid')->count();

        return response()->json([
            'pending' => $pending,
            'unpaid' => $unpaid
        ]);
    }

    public function printStruk(Transaction $transaction)
    {
        $transaction->load(['customer', 'user', 'details.product']);
        
        // Format tanggal
        $tanggal = $transaction->created_at->format('d/m/Y H:i');
        
        // Nama kasir
        $kasir = $transaction->user ? $transaction->user->name : 'System';
        
        // Format items
        $items = $transaction->details->map(function($detail) {
            return [
                'nama' => $detail->product->nama_produk,
                'harga' => $detail->harga,
                'jumlah' => $detail->jumlah,
                'subtotal' => $detail->subtotal
            ];
        })->toArray();
        
        // Hitung total dan PPN
        $subtotal = $transaction->total_harga;
        $ppn = round($subtotal * 0.11); // PPN 11%
        $total = $subtotal + $ppn;
        
        // Format metode pembayaran
        $metode = match($transaction->metode_pembayaran) {
            'cash' => 'Tunai',
            'transfer' => 'Transfer',
            'qris' => 'QRIS',
            default => ucfirst($transaction->metode_pembayaran)
        };
        
        $pdf = PDF::loadView('Admin.transactions.struk', [
            'transaksi' => $transaction,
            'tanggal' => $tanggal,
            'kasir' => $kasir,
            'items' => $items,
            'subtotal' => $subtotal,
            'ppn' => $ppn,
            'total' => $total,
            'metode' => $metode,
            'bayar' => $transaction->total_bayar,
            'kembali' => $transaction->total_kembali
        ]);
        
        return $pdf->stream('struk-' . $transaction->kode_transaksi . '.pdf');
    }
} 