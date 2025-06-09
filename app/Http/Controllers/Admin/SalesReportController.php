<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\PDF;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Exports\SalesReportExport;
use App\Http\Controllers\Controller;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        // Filter tanggal
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
        $period = $request->period ?? 'monthly';

        // Query dasar
        $query = Transaction::with(['customer', 'details.product.category'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed');

        // Filter produk
        if ($request->product_id) {
            $query->whereHas('details', function($q) use ($request) {
                $q->where('product_id', $request->product_id);
            });
        }

        // Filter kategori
        if ($request->category_id) {
            $query->whereHas('details.product', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Filter pelanggan
        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }

        // Data untuk grafik tren penjualan
        $salesTrend = $this->getSalesTrend($query->clone(), $period);

        // Perbandingan dengan periode sebelumnya
        $comparison = $this->getPeriodComparison($query->clone(), $startDate, $endDate);

        // Top produk
        $topProducts = $query->clone()
            ->join('order_details', 'orders.id', '=', 'order_details.pesanan_id')
            ->join('products', 'order_details.produk_id', '=', 'products.id')
            ->select('products.code', 'products.name', 
                DB::raw('SUM(order_details.jumlah) as total_quantity'),
                DB::raw('SUM(order_details.subtotal) as total_sales'),
                DB::raw('SUM(order_details.subtotal - (order_details.quantity * order_details.purchase_price)) as total_profit'))
            ->groupBy('products.id', 'products.code', 'products.name')
            ->orderBy('total_sales', 'desc')
            ->limit(10)
            ->get();

        // Top pelanggan
        $topCustomers = $query->clone()
            ->join('customers', 'orders.pelanggan_id', '=', 'customers.id')
            ->select('customers.kode_pelanggan', 'customers.nama',
                DB::raw('COUNT(DISTINCT orders.id) as total_orders'),
                DB::raw('SUM(orders.sub_total) as total_sales'),
                DB::raw('SUM(orders.total_amount - orders.total_cost) as total_profit'))
            ->groupBy('customers.id', 'customers.code', 'customers.name')
            ->orderBy('total_sales', 'desc')
            ->limit(10)
            ->get();

        // Top kategori
        $topCategories = $query->clone()
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name',
                DB::raw('SUM(order_details.quantity) as total_quantity'),
                DB::raw('SUM(order_details.subtotal) as total_sales'),
                DB::raw('SUM(order_details.subtotal - (order_details.quantity * order_details.purchase_price)) as total_profit'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_sales', 'desc')
            ->limit(10)
            ->get();

        // Analisis profit
        $profitAnalysis = $query->clone()
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name',
                DB::raw('SUM(order_details.subtotal) as total_sales'),
                DB::raw('SUM(order_details.subtotal - (order_details.quantity * order_details.purchase_price)) as total_profit'),
                DB::raw('(SUM(order_details.subtotal - (order_details.quantity * order_details.purchase_price)) / SUM(order_details.subtotal)) * 100 as profit_margin'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_profit', 'desc')
            ->get();

        // Ringkasan
        $summary = [
            'total_sales' => $query->sum('total_amount'),
            'total_profit' => $query->sum(DB::raw('total_amount - total_cost')),
            'total_orders' => $query->count(),
            'average_order_value' => $query->avg('total_amount'),
            'profit_margin' => $query->sum('total_amount') > 0 
                ? ($query->sum(DB::raw('total_amount - total_cost')) / $query->sum('total_amount')) * 100 
                : 0
        ];

        // Data untuk filter
        $products = Product::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();

        if ($request->export) {
            if ($request->export === 'excel') {
                return Excel::download(new SalesReportExport(
                    $query->get(),
                    $summary,
                    $salesTrend,
                    $comparison,
                    $topProducts,
                    $topCustomers,
                    $topCategories,
                    $profitAnalysis
                ), 'laporan-penjualan.xlsx');
            } else {
                $pdf = PDF::loadView('admin.reports.sales.pdf', compact(
                    'startDate',
                    'endDate',
                    'summary',
                    'comparison',
                    'topProducts',
                    'topCustomers',
                    'topCategories',
                    'profitAnalysis'
                ));
                return $pdf->download('laporan-penjualan.pdf');
            }
        }

        return view('admin.reports.sales.index', compact(
            'startDate',
            'endDate',
            'period',
            'summary',
            'salesTrend',
            'comparison',
            'topProducts',
            'topCustomers',
            'topCategories',
            'profitAnalysis',
            'products',
            'categories',
            'customers'
        ));
    }

    private function getSalesTrend($query, $period)
    {
        $format = match($period) {
            'daily' => 'Y-m-d',
            'weekly' => 'Y-W',
            'monthly' => 'Y-m',
            'yearly' => 'Y',
            default => 'Y-m'
        };

        return $query->select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as date"),
            DB::raw('SUM(total_amount) as total_sales'),
            DB::raw('SUM(total_amount - total_cost) as total_profit')
        )
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    }

    private function getPeriodComparison($query, $startDate, $endDate)
    {
        $currentPeriod = $query->clone()->get();
        $previousPeriod = $query->clone()
            ->whereBetween('created_at', [
                $startDate->copy()->subDays($endDate->diffInDays($startDate) + 1),
                $startDate->copy()->subDay()
            ])
            ->get();

        return [
            'current' => $currentPeriod,
            'previous' => $previousPeriod,
            'growth' => [
                'sales' => $this->calculateGrowth(
                    $currentPeriod->sum('total_amount'),
                    $previousPeriod->sum('total_amount')
                ),
                'profit' => $this->calculateGrowth(
                    $currentPeriod->sum(DB::raw('total_amount - total_cost')),
                    $previousPeriod->sum(DB::raw('total_amount - total_cost'))
                ),
                'orders' => $this->calculateGrowth(
                    $currentPeriod->count(),
                    $previousPeriod->count()
                )
            ]
        ];
    }

    private function calculateGrowth($current, $previous)
    {
        if ($previous == 0) return 0;
        return (($current - $previous) / $previous) * 100;
    }
} 