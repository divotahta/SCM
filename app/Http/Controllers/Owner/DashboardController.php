<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Data untuk notifikasi pembelian yang menunggu persetujuan
        $pendingPurchases = Purchase::where('status', 'pending')->get();

        // Ringkasan keuangan
        $totalIncome = Transaction::where('status_pesanan', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('sub_total');

        $totalExpense = Purchase::where('status', 'approved')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_amount');

        $totalProfit = $totalIncome - $totalExpense;

        // Data untuk grafik penjualan vs pembelian
        $months = collect(range(5, 0))->map(function ($i) {
            return Carbon::now()->subMonths($i);
        });

        $salesData = $months->map(function ($month) {
            return Transaction::where('status_pesanan', 'completed')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('sub_total');
        });

        $purchaseData = $months->map(function ($month) {
            return Purchase::where('status', 'approved')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('total_amount');
        });

        $chartLabels = $months->map(function ($month) {
            return $month->format('M Y');
        });

        // Produk terlaris
        $topProducts = Product::select('products.*')
            ->selectRaw('SUM(order_details.jumlah) as total_sold')
            ->selectRaw('SUM(order_details.jumlah * order_details.harga_satuan) as total_revenue')
            ->join('order_details', 'products.id', '=', 'order_details.produk_id')
            ->join('orders', 'orders.id', '=', 'order_details.pesanan_id')
            ->where('orders.status_pesanan', 'completed')
            ->whereMonth('orders.created_at', Carbon::now()->month)
            ->groupBy('products.id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Pelanggan teratas
        $topCustomers = Customer::select('customers.*')
            ->selectRaw('COUNT(orders.id) as total_transactions')
            ->selectRaw('SUM(orders.sub_total) as total_spent')
            ->join('orders', 'customers.id', '=', 'orders.pelanggan_id')
            ->where('orders.status_pesanan', 'completed')
            ->whereMonth('orders.created_at', Carbon::now()->month)
            ->groupBy('customers.id')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get();

        return view('owner.dashboard', compact(
            'pendingPurchases',
            'totalIncome',
            'totalExpense',
            'totalProfit',
            'salesData',
            'purchaseData',
            'chartLabels',
            'topProducts',
            'topCustomers'
        ));
    }
} 