<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PurchaseReportExport;

class PurchaseReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'details', 'approvedBy', 'rejectedBy', 'receivedBy'])
            ->when($request->supplier_id, function($q) use ($request) {
                return $q->where('supplier_id', $request->supplier_id);
            })
            ->when($request->status, function($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->when($request->date_start && $request->date_end, function($q) use ($request) {
                return $q->whereBetween('purchase_date', [
                    Carbon::parse($request->date_start)->startOfDay(),
                    Carbon::parse($request->date_end)->endOfDay()
                ]);
            });

        // Filter berdasarkan role
        if (Auth::user()->role === 'admin') {
            $query->where('created_by', Auth::id());
        }

        $purchases = $query->latest()->paginate(10);
        $suppliers = Supplier::all();

        // Data untuk chart
        $monthlyData = $this->getMonthlyData($request);
        $supplierData = $this->getSupplierData($request);
        
        // Summary data
        $summary = $this->getSummaryData($query);
        
        // Top 5 suppliers
        $topSuppliers = $this->getTopSuppliers($request);

        return view('admin.reports.purchases.index', compact(
            'purchases',
            'suppliers',
            'monthlyData',
            'supplierData',
            'summary',
            'topSuppliers'
        ));
    }

    private function getMonthlyData($request)
    {
        $query = Purchase::select(
            DB::raw('MONTH(purchase_date) as month'),
            DB::raw('YEAR(purchase_date) as year'),
            DB::raw('SUM(total_amount) as total')
        )
        ->when($request->supplier_id, function($q) use ($request) {
            return $q->where('supplier_id', $request->supplier_id);
        })
        ->when($request->status, function($q) use ($request) {
            return $q->where('status', $request->status);
        })
        ->when($request->date_start && $request->date_end, function($q) use ($request) {
            return $q->whereBetween('purchase_date', [
                Carbon::parse($request->date_start)->startOfDay(),
                Carbon::parse($request->date_end)->endOfDay()
            ]);
        });

        if (Auth::user()->role === 'admin') {
            $query->where('created_by', Auth::id());
        }

        return $query->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
    }

    private function getSupplierData($request)
    {
        $query = Purchase::select(
            'suppliers.name',
            DB::raw('SUM(purchases.total_amount) as total')
        )
        ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
        ->when($request->status, function($q) use ($request) {
            return $q->where('purchases.status', $request->status);
        })
        ->when($request->date_start && $request->date_end, function($q) use ($request) {
            return $q->whereBetween('purchase_date', [
                Carbon::parse($request->date_start)->startOfDay(),
                Carbon::parse($request->date_end)->endOfDay()
            ]);
        });

        if (Auth::user()->role === 'admin') {
            $query->where('purchases.created_by', Auth::id());
        }

        return $query->groupBy('suppliers.id', 'suppliers.name')
            ->orderBy('total', 'desc')
            ->get();
    }

    private function getSummaryData($query)
    {
        $clone = clone $query;
        $total = $clone->sum('total_amount');
        $count = $clone->count();
        
        return [
            'total_purchases' => $total,
            'transaction_count' => $count,
            'average_per_transaction' => $count > 0 ? $total / $count : 0
        ];
    }

    private function getTopSuppliers($request)
    {
        $query = Purchase::select(
            'suppliers.name',
            DB::raw('COUNT(*) as transaction_count'),
            DB::raw('SUM(purchases.total_amount) as total_amount')
        )
        ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
        ->when($request->status, function($q) use ($request) {
            return $q->where('purchases.status', $request->status);
        })
        ->when($request->date_start && $request->date_end, function($q) use ($request) {
            return $q->whereBetween('purchase_date', [
                Carbon::parse($request->date_start)->startOfDay(),
                Carbon::parse($request->date_end)->endOfDay()
            ]);
        });

        if (Auth::user()->role === 'admin') {
            $query->where('purchases.created_by', Auth::id());
        }

        return $query->groupBy('suppliers.id', 'suppliers.name')
            ->orderBy('total_amount', 'desc')
            ->limit(5)
            ->get();
    }

    public function exportPdf(Request $request)
    {
        $query = Purchase::with(['supplier', 'details', 'approvedBy', 'rejectedBy', 'receivedBy'])
            ->when($request->supplier_id, function($q) use ($request) {
                return $q->where('supplier_id', $request->supplier_id);
            })
            ->when($request->status, function($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->when($request->date_start && $request->date_end, function($q) use ($request) {
                return $q->whereBetween('purchase_date', [
                    Carbon::parse($request->date_start)->startOfDay(),
                    Carbon::parse($request->date_end)->endOfDay()
                ]);
            });

        if (Auth::user()->role === 'admin') {
            $query->where('created_by', Auth::id());
        }

        $purchases = $query->get();
        $summary = $this->getSummaryData($query);
        $topSuppliers = $this->getTopSuppliers($request);

        $pdf = PDF::loadView('admin.reports.purchases.pdf', compact('purchases', 'summary', 'topSuppliers'));
        return $pdf->download('laporan-pembelian.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new PurchaseReportExport($request), 'laporan-pembelian.xlsx');
    }
} 