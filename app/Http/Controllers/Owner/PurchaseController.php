<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Product;
use App\Models\TransactionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PurchaseApprovalNotification;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'details.product'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('purchase_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('purchase_date', '<=', $request->end_date);
        }

        $purchases = $query->paginate(10);

        return view('owner.purchases.index', compact('purchases'));
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'details.product', 'approvalHistory']);
        return view('owner.purchases.show', compact('purchase'));
    }

    public function approve(Purchase $purchase)
    {
        if ($purchase->status !== 'pending') {
            return redirect()->back()->with('error', 'Pembelian ini tidak dapat disetujui.');
        }

        DB::beginTransaction();
        try {
            // Update status pembelian
            $purchase->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id()
            ]);

            // Catat history persetujuan
            $purchase->approvalHistory()->create([
                'status' => 'approved',
                'notes' => 'Pembelian disetujui',
                'created_by' => auth()->id()
            ]);

            // Kirim notifikasi email ke admin
            Mail::to($purchase->createdBy->email)->send(new PurchaseApprovalNotification($purchase, 'approved'));

            DB::commit();
            return redirect()->route('owner.purchases.index')->with('success', 'Pembelian berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyetujui pembelian.');
        }
    }

    public function reject(Request $request, Purchase $purchase)
    {
        if ($purchase->status !== 'pending') {
            return redirect()->back()->with('error', 'Pembelian ini tidak dapat ditolak.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:255'
        ]);

        DB::beginTransaction();
        try {
            // Update status pembelian
            $purchase->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
                'rejection_reason' => $request->rejection_reason
            ]);

            // Catat history penolakan
            $purchase->approvalHistory()->create([
                'status' => 'rejected',
                'notes' => $request->rejection_reason,
                'created_by' => auth()->id()
            ]);

            // Kirim notifikasi email ke admin
            Mail::to($purchase->createdBy->email)->send(new PurchaseApprovalNotification($purchase, 'rejected'));

            DB::commit();
            return redirect()->route('owner.purchases.index')->with('success', 'Pembelian berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menolak pembelian.');
        }
    }
} 