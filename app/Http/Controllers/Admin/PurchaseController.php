<?php

namespace App\Http\Controllers\Admin;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\TransactionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'details.product'])
            ->when($request->search, function($q) use ($request) {
                $q->where('nomor_pembelian', 'like', "%{$request->search}%")
                    ->orWhereHas('supplier', function($q) use ($request) {
                        $q->where('nama', 'like', "%{$request->search}%");
                    });
            })
            ->when($request->status, function($q) use ($request) {
                $q->where('status_pembelian', $request->status);
            })
            ->when($request->date_from, function($q) use ($request) {
                $q->whereDate('tanggal_pembelian', '>=', $request->date_from);
            })
            ->when($request->date_to, function($q) use ($request) {
                $q->whereDate('tanggal_pembelian', '<=', $request->date_to);
            });

        $purchases = $query->latest()->paginate(10);
        $draftCount = Purchase::where('status_pembelian', 'draft')->count();
        $pendingCount = Purchase::where('status_pembelian', 'pending')->count();

        return view('admin.purchases.index', compact('purchases', 'draftCount', 'pendingCount'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('admin.purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $purchase = Purchase::create([
                'tanggal_pembelian' => $request->tanggal_pembelian,
                'nomor_pembelian' => $this->generateInvoiceNumber(),
                'pemasok_id' => $request->pemasok_id,
                'total_amount' => 0,
                'catatan' => $request->catatan,
                'status_pembelian' => 'draft',
                'dibuat_oleh' => Auth::id()
            ]);

            $total = 0;
            foreach ($request->products as $item) {
                $product = Product::find($item['id']);
                $subtotal = $item['jumlah'] * $item['harga_satuan'];
                
                PurchaseDetail::create([
                    'pembelian_id' => $purchase->id,
                    'produk_id' => $item['id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                    'total' => $subtotal
                ]);

                $total += $subtotal;
            }

            $purchase->update(['total_amount' => $total]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembelian berhasil dibuat',
                'redirect' => route('admin.purchases.show', $purchase->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat pembelian'
            ], 500);
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'details.product', 'approvedBy', 'rejectedBy', 'receivedBy']);
        return view('admin.purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        if ($purchase->status_pembelian !== 'draft') {
            return redirect()->route('admin.purchases.show', $purchase->id)
                ->with('error', 'Pembelian tidak dapat diedit karena status bukan draft');
        }

        $purchase->load(['supplier', 'details.product']);
        $suppliers = Supplier::all();
        $products = Product::all();
        
        return view('admin.purchases.edit', compact('purchase', 'suppliers', 'products'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        if ($purchase->status_pembelian !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Pembelian tidak dapat diedit karena status bukan draft'
            ], 422);
        }

        try {
            DB::beginTransaction();

            $purchase->update([
                'pemasok_id' => $request->pemasok_id,
                'tanggal_pembelian' => $request->tanggal_pembelian,
                'catatan' => $request->catatan
            ]);

            // Hapus detail lama
            $purchase->details()->delete();

            // Tambah detail baru
            $total = 0;
            foreach ($request->products as $item) {
                $product = Product::find($item['id']);
                $subtotal = $item['jumlah'] * $item['harga_satuan'];
                
                PurchaseDetail::create([
                    'pembelian_id' => $purchase->id,
                    'produk_id' => $item['id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                    'total' => $subtotal
                ]);

                $total += $subtotal;
            }

            $purchase->update(['total_amount' => $total]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembelian berhasil diperbarui',
                'redirect' => route('admin.purchases.show', $purchase->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui pembelian'
            ], 500);
        }
    }

    public function submit(Purchase $purchase)
    {
        if ($purchase->status_pembelian !== 'draft') {
            return redirect()->back()->with('error', 'Status pembelian tidak valid');
        }

        try {
            DB::beginTransaction();

            $purchase->update(['status_pembelian' => 'pending']);

            // Log pengajuan pembelian
            // TransactionLog::create([
            //     'transaction_id' => $purchase->id,
            //     'transaction_type' => 'purchase',
            //     'action' => 'submit',
            //     'description' => "Pembelian diajukan untuk persetujuan",
            //     'old_data' => ['status_pembelian' => 'draft'],
            //     'new_data' => ['status_pembelian' => 'pending'],
            //     'user_id' => Auth::id()
            // ]);

            DB::commit();

            return redirect()->route('admin.purchases.show', $purchase->id)
                ->with('success', 'Pembelian berhasil diajukan untuk persetujuan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengajukan pembelian');
        }
    }

    public function approve(Purchase $purchase)
    {
        if (Auth::user()->role !== 'owner') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menyetujui pembelian');
        }

        if ($purchase->status_pembelian !== 'pending') {
            return redirect()->back()->with('error', 'Status pembelian tidak valid');
        }

        try {
            DB::beginTransaction();

            $purchase->update([
                'status_pembelian' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);

            // Log persetujuan pembelian
            // TransactionLog::create([
            //     'transaction_id' => $purchase->id,
            //     'transaction_type' => 'purchase',
            //     'action' => 'approve',
            //     'description' => "Pembelian disetujui oleh owner",
            //     'old_data' => ['status_pembelian' => 'pending'],
            //     'new_data' => ['status_pembelian' => 'approved'],
            //     'user_id' => Auth::id()
            // ]);

            DB::commit();

            return redirect()->route('admin.purchases.show', $purchase->id)
                ->with('success', 'Pembelian berhasil disetujui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyetujui pembelian');
        }
    }

    public function receive(Purchase $purchase)
    {
        if ($purchase->status_pembelian !== 'approved') {
            return redirect()->back()->with('error', 'Status pembelian tidak valid');
        }

        try {
            DB::beginTransaction();

            $purchase->update([
                'status_pembelian' => 'received',
                'received_by' => Auth::id(),
                'received_at' => now()
            ]);

            // Update stok produk
            foreach ($purchase->details as $detail) {
                $product = $detail->product;
                $product->stock += $detail->quantity;
                $product->save();
            }

            // Log penerimaan pembelian
            // TransactionLog::create([
            //     'transaction_id' => $purchase->id,
            //     'transaction_type' => 'purchase',
            //     'action' => 'receive',
            //     'description' => "Pembelian diterima dan stok diperbarui",
            //     'old_data' => ['status_pembelian' => 'approved'],
            //     'new_data' => ['status_pembelian' => 'received'],
            //     'user_id' => Auth::id()
            // ]);

            DB::commit();

            return redirect()->route('admin.purchases.show', $purchase->id)
                ->with('success', 'Pembelian berhasil diterima dan stok diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menerima pembelian');
        }
    }

    public function destroy(Purchase $purchase)
    {
        if ($purchase->status_pembelian !== 'draft') {
            return redirect()->back()->with('error', 'Pembelian tidak dapat dihapus karena status bukan draft');
        }

        try {
            DB::beginTransaction();

            // // Log penghapusan pembelian
            // TransactionLog::create([
            //     'transaction_id' => $purchase->id,
            //     'transaction_type' => 'purchase',
            //     'action' => 'delete',
            //     'description' => "Pembelian dihapus",
            //     'old_data' => $purchase->toArray(),
            //     'new_data' => null,
            //     'user_id' => Auth::id()
            // ]);

            $purchase->details()->delete();
            $purchase->delete();

            DB::commit();

            return redirect()->route('admin.purchases.index')
                ->with('success', 'Pembelian berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus pembelian');
        }
    }

    private function generateInvoiceNumber()
    {
        $prefix = 'PO';
        $date = now()->format('Ymd');
        $lastPurchase = Purchase::where('nomor_pembelian', 'like', "{$prefix}{$date}%")
            ->orderBy('nomor_pembelian', 'desc')
            ->first();

        if ($lastPurchase) {
            $lastNumber = (int) substr($lastPurchase->nomor_pembelian, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}{$date}{$newNumber}";
    }
} 