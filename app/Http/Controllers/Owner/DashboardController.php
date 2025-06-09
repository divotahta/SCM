<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Order;
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
        $totalIncome = Order::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_amount');

        $totalExpense = Purchase::where('status', 'approved')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_amount');

        $totalProfit = $totalIncome - $totalExpense;

        // Data untuk grafik penjualan vs pembelian
        $months = collect(range(5, 0))->map(function ($i) {
            return Carbon::now()->subMonths($i);
        });

        $salesData = $months->map(function ($month) {
            return Order::where('status', 'completed')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('total_amount');
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
            ->selectRaw('SUM(order_details.quantity) as total_sold')
            ->selectRaw('SUM(order_details.quantity * order_details.price) as total_revenue')
            ->join('order_details', 'products.id', '=', 'order_details.product_id')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.status', 'completed')
            ->whereMonth('orders.created_at', Carbon::now()->month)
            ->groupBy('products.id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Pelanggan teratas
        $topCustomers = Customer::select('customers.*')
            ->selectRaw('COUNT(orders.id) as total_transactions')
            ->selectRaw('SUM(orders.total_amount) as total_spent')
            ->join('orders', 'customers.id', '=', 'orders.customer_id')
            ->where('orders.status', 'completed')
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