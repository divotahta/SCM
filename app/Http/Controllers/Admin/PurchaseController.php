<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\PurchaseDetail;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['supplier', 'details.product', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('admin.purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pemasok_id' => 'required|exists:suppliers,id',
            'tanggal_pembelian' => 'required|date',
            'catatan' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.produk_id' => 'required|exists:products,id',
            'products.*.jumlah' => 'required|integer|min:1',
            'products.*.harga_satuan' => 'required|numeric|min:0',
        ]);
        
        try {
            DB::beginTransaction();

            $purchase = new Purchase();
            $purchase->pemasok_id = $request->pemasok_id;
            $purchase->tanggal_pembelian = $request->tanggal_pembelian;
            $purchase->nomor_pembelian = $purchase->generateInvoiceNumber();
            $purchase->catatan = $request->catatan;
            $purchase->status_pembelian = 'pending';
            $purchase->total_amount = 0;
            $purchase->dibuat_oleh = Auth::id();
            $purchase->save();

            $total = 0;
            foreach ($request->products as $product) {
                $subtotal = $product['jumlah'] * $product['harga_satuan'];
                $total += $subtotal;

                PurchaseDetail::create([
                    'pembelian_id' => $purchase->id,
                    'produk_id' => $product['produk_id'],
                    'jumlah' => $product['jumlah'],
                    'harga_satuan' => $product['harga_satuan'],
                    'total' => $subtotal,
                    'catatan' => $product['catatan'] ?? null
                ]);
            }

            $purchase->total_amount = $total;
            $purchase->save();

            DB::commit();

            return redirect()->route('admin.purchases.index')
                ->with('success', 'Pembelian berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'details.product', 'createdBy', 'approvedBy', 'rejectedBy', 'receivedBy']);
        return view('admin.purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        // Cek apakah pembelian masih bisa diedit
        if ($purchase->status_pembelian !== 'pending') {
            return redirect()->route('admin.purchases.index')
                ->with('error', 'Pembelian ini tidak dapat diedit karena statusnya bukan pending.');
        }

        $suppliers = Supplier::all();
        $products = Product::with('unit')->get();

        return view('Admin.purchases.edit', compact('purchase', 'suppliers', 'products'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        try {
            Log::info('Memulai proses update pembelian', [
                'purchase_id' => $purchase->id,
                'request_data' => $request->all()
            ]);

            // Validasi request
        $request->validate([
            'pemasok_id' => 'required|exists:suppliers,id',
            'tanggal_pembelian' => 'required|date',
                'status_pembelian' => 'required|in:pending,approved,rejected,received',
                'items' => 'required|array|min:1',
                'items.*.produk_id' => 'required|exists:products,id',
                'items.*.jumlah' => 'required|numeric|min:1',
                'items.*.harga_satuan' => 'required|numeric|min:0',
                'items.*.total' => 'required|numeric|min:0',
                'items.*.catatan' => 'nullable|string|max:255',
                'total' => 'required|numeric|min:0',
                'alasan_penolakan' => 'required_if:status_pembelian,rejected|nullable|string|max:255'
        ]);

            // Cek apakah pembelian masih bisa diedit
            if ($purchase->status_pembelian !== 'pending') {
                return redirect()->route('admin.purchases.index')
                    ->with('error', 'Pembelian ini tidak dapat diedit karena statusnya bukan pending.');
            }

            DB::beginTransaction();
            Log::info('Memulai transaksi database');

            // Update data pembelian
            $updateResult = $purchase->update([
                'pemasok_id' => $request->pemasok_id,
                'tanggal_pembelian' => $request->tanggal_pembelian,
                'status_pembelian' => $request->status_pembelian,
                'total' => $request->total,
                'dibuat_oleh' => Auth::id()
            ]);

            if (!$updateResult) {
                throw new \Exception('Gagal mengupdate data pembelian');
            }

            Log::info('Data pembelian berhasil diupdate', [
                'purchase_id' => $purchase->id,
                'new_status' => $request->status_pembelian
            ]);

            // Hapus detail pembelian lama
            $purchase->details()->delete();
            Log::info('Detail pembelian lama berhasil dihapus');

            // Buat detail pembelian baru
            foreach ($request->items as $item) {
                $detail = $purchase->details()->create([
                    'produk_id' => $item['produk_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                    'total' => $item['total'],
                    'catatan' => $item['catatan'] ?? null
                ]);

                if (!$detail) {
                    throw new \Exception('Gagal membuat detail pembelian');
                }
            }

            Log::info('Detail pembelian baru berhasil dibuat', [
                'purchase_id' => $purchase->id,
                'item_count' => count($request->items)
            ]);

            // Jika status rejected, update alasan penolakan
            if ($request->status_pembelian === 'rejected') {
                $purchase->update([
                    'alasan_penolakan' => $request->alasan_penolakan,
                    'ditolak_oleh' => Auth::id(),
                    'ditolak_pada' => now()
                ]);
                Log::info('Status pembelian diupdate ke rejected', [
                    'purchase_id' => $purchase->id,
                    'alasan' => $request->alasan_penolakan
                ]);
            }

            DB::commit();
            Log::info('Transaksi berhasil diselesaikan', ['purchase_id' => $purchase->id]);

            return redirect()->route('admin.purchases.index')
                ->with('success', 'Pembelian berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error dalam proses update pembelian', [
                'purchase_id' => $purchase->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui pembelian. Silakan coba lagi.');
        }
    }

    public function destroy(Purchase $purchase)
    {
        if ($purchase->status_pembelian !== 'pending') {
            return redirect()->route('admin.purchases.show', $purchase)
                ->with('error', 'Pembelian yang sudah disetujui tidak dapat dihapus');
        }

        try {
            DB::beginTransaction();
            $purchase->details()->delete();
            $purchase->delete();
            DB::commit();

            return redirect()->route('admin.purchases.index')
                ->with('success', 'Pembelian berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function approve(Purchase $purchase)
    {
        try {
            Log::info('Memulai proses persetujuan pembelian', [
                'purchase_id' => $purchase->id,
                'user_id' => Auth::id()
            ]);

            // Cek apakah pembelian masih pending
            if ($purchase->status_pembelian !== 'pending') {
                Log::warning('Status pembelian tidak valid untuk disetujui', [
                    'purchase_id' => $purchase->id,
                    'current_status' => $purchase->status_pembelian
                ]);
                return redirect()->route('admin.purchases.index')
                    ->with('error', 'Pembelian ini tidak dapat disetujui karena statusnya bukan pending.');
        }

            DB::beginTransaction();
            Log::info('Memulai transaksi database');

            try {
                // Update status pembelian
                $updateResult = $purchase->update([
                'status_pembelian' => 'approved',
                'disetujui_oleh' => Auth::id(),
                'disetujui_pada' => now()
            ]);

                if (!$updateResult) {
                    throw new \Exception('Gagal mengupdate status pembelian');
                }

                Log::info('Status pembelian berhasil diupdate', [
                    'purchase_id' => $purchase->id,
                    'new_status' => 'approved'
                ]);

                // Buat notifikasi untuk admin
                try {
                    $notification = Notification::create([
                        'user_id' => Auth::id(),
                        'judul' => 'Pembelian Disetujui',
                        'pesan' => "Pembelian #{$purchase->nomor_pembelian} dari {$purchase->supplier->nama_supplier} telah disetujui oleh " . Auth::user()->name,
                        'jenis' => 'purchase_approved',
                        'dibaca' => false,
                        'detail' => [
                            'purchase_id' => $purchase->id,
                            'nomor_pembelian' => $purchase->nomor_pembelian,
                            'supplier' => $purchase->supplier->nama_supplier
                        ],
                        'link' => route('admin.purchases.show', $purchase->id)
                    ]);
                    Log::info('Notifikasi berhasil dibuat', ['notification_id' => $notification->id]);
                } catch (\Exception $e) {
                    Log::warning('Gagal membuat notifikasi', [
                        'error' => $e->getMessage(),
                        'purchase_id' => $purchase->id
                    ]);
                }

            DB::commit();
                Log::info('Transaksi berhasil diselesaikan', ['purchase_id' => $purchase->id]);

                return redirect()->route('admin.purchases.index')
                    ->with('success', 'Pembelian berhasil disetujui.');

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error dalam transaksi database', [
                    'purchase_id' => $purchase->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error dalam proses persetujuan pembelian', [
                'purchase_id' => $purchase->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.purchases.index')
                ->with('error', 'Terjadi kesalahan saat menyetujui pembelian: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Purchase $purchase)
    {
        try {
            Log::info('Memulai proses penolakan pembelian', [
                'purchase_id' => $purchase->id,
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

        $request->validate([
                'alasan_penolakan' => 'required|string|max:255'
        ]);

            // Cek apakah pembelian masih pending
            if ($purchase->status_pembelian !== 'pending') {
                Log::warning('Status pembelian tidak valid untuk ditolak', [
                    'purchase_id' => $purchase->id,
                    'current_status' => $purchase->status_pembelian
                ]);
                return redirect()->route('admin.purchases.index')
                    ->with('error', 'Pembelian ini tidak dapat ditolak karena statusnya bukan pending.');
            }

            DB::beginTransaction();
            Log::info('Memulai transaksi database');

            try {
                // Update status pembelian
                $updateResult = $purchase->update([
                'status_pembelian' => 'rejected',
                'ditolak_oleh' => Auth::id(),
                'ditolak_pada' => now(),
                'alasan_penolakan' => $request->alasan_penolakan
            ]);

                if (!$updateResult) {
                    throw new \Exception('Gagal mengupdate status pembelian');
                }

                Log::info('Status pembelian berhasil diupdate', [
                    'purchase_id' => $purchase->id,
                    'new_status' => 'rejected'
                ]);

                // Buat notifikasi untuk admin
                try {
                    $notification = Notification::create([
                        'user_id' => Auth::id(),
                        'judul' => 'Pembelian Ditolak',
                        'pesan' => "Pembelian #{$purchase->nomor_pembelian} dari {$purchase->supplier->nama_supplier} telah ditolak oleh " . Auth::user()->name . ". Alasan: " . $request->alasan_penolakan,
                        'jenis' => 'purchase_rejected',
                        'dibaca' => false,
                        'detail' => [
                            'purchase_id' => $purchase->id,
                            'nomor_pembelian' => $purchase->nomor_pembelian,
                            'supplier' => $purchase->supplier->nama_supplier,
                            'alasan_penolakan' => $request->alasan_penolakan
                        ],
                        'link' => route('admin.purchases.show', $purchase->id)
                    ]);
                    Log::info('Notifikasi berhasil dibuat', ['notification_id' => $notification->id]);
                } catch (\Exception $e) {
                    Log::warning('Gagal membuat notifikasi', [
                        'error' => $e->getMessage(),
                        'purchase_id' => $purchase->id
                    ]);
                }

            DB::commit();
                Log::info('Transaksi berhasil diselesaikan', ['purchase_id' => $purchase->id]);

                return redirect()->route('admin.purchases.index')
                    ->with('success', 'Pembelian berhasil ditolak.');

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error dalam transaksi database', [
                    'purchase_id' => $purchase->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error dalam proses penolakan pembelian', [
                'purchase_id' => $purchase->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.purchases.index')
                ->with('error', 'Terjadi kesalahan saat menolak pembelian: ' . $e->getMessage());
        }
    }

    public function receive(Purchase $purchase)
    {
        try {
            Log::info('Memulai proses penerimaan barang', [
                'purchase_id' => $purchase->id,
                'user_id' => Auth::id()
            ]);

            // Cek apakah pembelian sudah disetujui
            if ($purchase->status_pembelian !== 'approved') {
                Log::warning('Status pembelian tidak valid untuk diterima', [
                    'purchase_id' => $purchase->id,
                    'current_status' => $purchase->status_pembelian
                ]);
                return redirect()->route('admin.purchases.index')
                    ->with('error', 'Pembelian ini tidak dapat diterima karena statusnya bukan approved.');
        }

            DB::beginTransaction();
            Log::info('Memulai transaksi database');

            try {
            // Update status pembelian
                $updateResult = $purchase->update([
                'status_pembelian' => 'received',
                'diterima_oleh' => Auth::id(),
                'diterima_pada' => now()
            ]);

                if (!$updateResult) {
                    throw new \Exception('Gagal mengupdate status pembelian');
                }

                Log::info('Status pembelian berhasil diupdate', [
                    'purchase_id' => $purchase->id,
                    'new_status' => 'received'
                ]);

            // Update stok produk
            foreach ($purchase->details as $detail) {
                    try {
                $product = $detail->product;
                        if (!$product) {
                            throw new \Exception("Produk dengan ID {$detail->produk_id} tidak ditemukan");
                        }

                $oldStock = $product->stok;
                $newStock = $oldStock + $detail->jumlah;

                        Log::info('Mengupdate stok produk', [
                            'product_id' => $product->id,
                            'product_name' => $product->nama_produk,
                            'old_stock' => $oldStock,
                            'new_stock' => $newStock,
                            'quantity' => $detail->jumlah
                        ]);

                        $updateStock = $product->update([
                            'stok' => $newStock
                        ]);

                        if (!$updateStock) {
                            throw new \Exception("Gagal mengupdate stok produk {$product->nama_produk}");
                        }

                        // Catat history stok
                        $stockHistory = StockHistory::create([
                    'produk_id' => $product->id,
                            'jenis_perubahan' => 'masuk',
                    'jumlah' => $detail->jumlah,
                            'stok_lama' => $oldStock,
                            'stok_baru' => $newStock,
                            'keterangan' => "Penerimaan barang dari pembelian #{$purchase->nomor_pembelian}",
                    'dibuat_oleh' => Auth::id()
                ]);

                        if (!$stockHistory) {
                            throw new \Exception("Gagal mencatat history stok untuk produk {$product->nama_produk}");
                        }

                        // Buat notifikasi untuk perubahan stok
                        try {
                            $notification = Notification::create([
                                'user_id' => Auth::id(),
                                'judul' => 'Stok Produk Diperbarui',
                                'pesan' => "Stok produk {$product->nama_produk} diperbarui: {$oldStock} → {$newStock} (Penambahan: {$detail->jumlah})",
                                'jenis' => 'stock_updated',
                                'dibaca' => false,
                                'detail' => [
                                    'product_id' => $product->id,
                                    'product_name' => $product->nama_produk,
                                    'old_stock' => $oldStock,
                                    'new_stock' => $newStock,
                                    'quantity' => $detail->jumlah
                                ],
                                'link' => route('admin.products.show', $product->id)
                            ]);
                            Log::info('Notifikasi stok berhasil dibuat', ['notification_id' => $notification->id]);
                        } catch (\Exception $e) {
                            Log::warning('Gagal membuat notifikasi stok', [
                                'error' => $e->getMessage(),
                                'product_id' => $product->id
                            ]);
                        }

                        Log::info('Stok produk berhasil diupdate', [
                            'product_id' => $product->id,
                            'product_name' => $product->nama_produk,
                            'old_stock' => $oldStock,
                            'new_stock' => $newStock
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Error saat mengupdate stok produk', [
                            'product_id' => $detail->produk_id,
                            'error' => $e->getMessage()
                        ]);
                        throw $e;
                    }
                }

                // Buat notifikasi untuk penerimaan barang
                try {
                    $notification = Notification::create([
                        'user_id' => Auth::id(),
                        'judul' => 'Barang Diterima',
                        'pesan' => "Barang dari pembelian #{$purchase->nomor_pembelian} dari {$purchase->supplier->nama_supplier} telah diterima oleh " . Auth::user()->name,
                        'jenis' => 'purchase_received',
                        'dibaca' => false,
                        'detail' => [
                            'purchase_id' => $purchase->id,
                            'nomor_pembelian' => $purchase->nomor_pembelian,
                            'supplier' => $purchase->supplier->nama_supplier
                        ],
                        'link' => route('admin.purchases.show', $purchase->id)
                    ]);
                    Log::info('Notifikasi berhasil dibuat', ['notification_id' => $notification->id]);
                } catch (\Exception $e) {
                    Log::warning('Gagal membuat notifikasi', [
                        'error' => $e->getMessage(),
                        'purchase_id' => $purchase->id
                    ]);
                }

                DB::commit();
                Log::info('Transaksi berhasil diselesaikan', ['purchase_id' => $purchase->id]);

                return redirect()->route('admin.purchases.index')
                    ->with('success', 'Barang berhasil diterima dan stok telah diperbarui.');

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error dalam transaksi database', [
                    'purchase_id' => $purchase->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error dalam proses penerimaan barang', [
                'purchase_id' => $purchase->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.purchases.index')
                ->with('error', 'Terjadi kesalahan saat menerima barang: ' . $e->getMessage());
        }
    }
} 