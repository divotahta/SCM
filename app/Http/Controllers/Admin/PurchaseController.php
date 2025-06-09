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
                $q->where('invoice_number', 'like', "%{$request->search}%")
                    ->orWhereHas('supplier', function($q) use ($request) {
                        $q->where('name', 'like', "%{$request->search}%");
                    });
            })
            ->when($request->status, function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->date_from, function($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->date_to, function($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            });

        $purchases = $query->latest()->paginate(10);
        $draftCount = Purchase::where('status', 'draft')->count();
        $pendingCount = Purchase::where('status', 'pending')->count();

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
                'invoice_number' => $this->generateInvoiceNumber(),
                'supplier_id' => $request->supplier_id,
                'purchase_date' => $request->purchase_date,
                'total' => 0,
                'status' => 'draft',
                'notes' => $request->notes,
                'created_by' => Auth::id()
            ]);

            $total = 0;
            foreach ($request->products as $item) {
                $product = Product::find($item['id']);
                $subtotal = $item['quantity'] * $item['price'];
                
                PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal
                ]);

                $total += $subtotal;
            }

            $purchase->update(['total' => $total]);

            // Log pembuatan pembelian
            TransactionLog::create([
                'transaction_id' => $purchase->id,
                'transaction_type' => 'purchase',
                'action' => 'create',
                'description' => "Pembelian baru dibuat",
                'old_data' => null,
                'new_data' => $purchase->toArray(),
                'user_id' => Auth::id()
            ]);

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
        $purchase->load(['supplier', 'details.product', 'logs' => function($query) {
            $query->latest();
        }]);
        
        return view('admin.purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        if ($purchase->status !== 'draft') {
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
        if ($purchase->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Pembelian tidak dapat diedit karena status bukan draft'
            ], 422);
        }

        try {
            DB::beginTransaction();

            $purchase->update([
                'supplier_id' => $request->supplier_id,
                'purchase_date' => $request->purchase_date,
                'notes' => $request->notes
            ]);

            // Hapus detail lama
            $purchase->details()->delete();

            // Tambah detail baru
            $total = 0;
            foreach ($request->products as $item) {
                $product = Product::find($item['id']);
                $subtotal = $item['quantity'] * $item['price'];
                
                PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal
                ]);

                $total += $subtotal;
            }

            $purchase->update(['total' => $total]);

            // Log update pembelian
            TransactionLog::create([
                'transaction_id' => $purchase->id,
                'transaction_type' => 'purchase',
                'action' => 'update',
                'description' => "Pembelian diperbarui",
                'old_data' => $purchase->getOriginal(),
                'new_data' => $purchase->toArray(),
                'user_id' => Auth::id()
            ]);

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
        if ($purchase->status !== 'draft') {
            return redirect()->back()->with('error', 'Status pembelian tidak valid');
        }

        try {
            DB::beginTransaction();

            $purchase->update(['status' => 'pending']);

            // Log pengajuan pembelian
            TransactionLog::create([
                'transaction_id' => $purchase->id,
                'transaction_type' => 'purchase',
                'action' => 'submit',
                'description' => "Pembelian diajukan untuk persetujuan",
                'old_data' => ['status' => 'draft'],
                'new_data' => ['status' => 'pending'],
                'user_id' => Auth::id()
            ]);

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
        if (!Auth::user()->hasRole('owner')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menyetujui pembelian');
        }

        if ($purchase->status !== 'pending') {
            return redirect()->back()->with('error', 'Status pembelian tidak valid');
        }

        try {
            DB::beginTransaction();

            $purchase->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);

            // Log persetujuan pembelian
            TransactionLog::create([
                'transaction_id' => $purchase->id,
                'transaction_type' => 'purchase',
                'action' => 'approve',
                'description' => "Pembelian disetujui oleh owner",
                'old_data' => ['status' => 'pending'],
                'new_data' => ['status' => 'approved'],
                'user_id' => Auth::id()
            ]);

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
        if ($purchase->status !== 'approved') {
            return redirect()->back()->with('error', 'Status pembelian tidak valid');
        }

        try {
            DB::beginTransaction();

            $purchase->update([
                'status' => 'received',
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
            TransactionLog::create([
                'transaction_id' => $purchase->id,
                'transaction_type' => 'purchase',
                'action' => 'receive',
                'description' => "Pembelian diterima dan stok diperbarui",
                'old_data' => ['status' => 'approved'],
                'new_data' => ['status' => 'received'],
                'user_id' => Auth::id()
            ]);

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
        if ($purchase->status !== 'draft') {
            return redirect()->back()->with('error', 'Pembelian tidak dapat dihapus karena status bukan draft');
        }

        try {
            DB::beginTransaction();

            // Log penghapusan pembelian
            TransactionLog::create([
                'transaction_id' => $purchase->id,
                'transaction_type' => 'purchase',
                'action' => 'delete',
                'description' => "Pembelian dihapus",
                'old_data' => $purchase->toArray(),
                'new_data' => null,
                'user_id' => Auth::id()
            ]);

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
        $lastPurchase = Purchase::where('invoice_number', 'like', "{$prefix}{$date}%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastPurchase) {
            $lastNumber = (int) substr($lastPurchase->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
} 