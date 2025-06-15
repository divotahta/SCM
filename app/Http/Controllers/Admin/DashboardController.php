<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Product::all();
        // Data untuk card statistik
        $totalPenjualan = Transaction::where('status', 'selesai')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_harga');

        $totalPembelian = Purchase::where('status_pembelian', 'received')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_amount');

        $totalProduk = Product::count();
        $totalPelanggan = Customer::count();
        $totalSupplier = Supplier::count();

        // Produk dengan stok menipis (kurang dari 10)
        $produkStokMenipis = Product::where('stok', '<', 10)
            ->orderBy('stok')
            ->take(5)
            ->get();

        // Transaksi terbaru
        $transaksiTerbaru = Transaction::with(['customer', 'details.product'])
            ->latest()
            ->take(5)
            ->get();

        // Pembelian terbaru
        $pembelianTerbaru = Purchase::with(['supplier', 'details.product'])
            ->latest()
            ->take(5)
            ->get();

        // Grafik penjualan bulanan
        $penjualanBulanan = Transaction::where('status', 'selesai')
            ->whereYear('created_at', Carbon::now()->year)
            ->select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('SUM(total_harga) as total')
            )
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Grafik pembelian bulanan
        $pembelianBulanan = Purchase::where('status_pembelian', 'received')
            ->whereYear('created_at', Carbon::now()->year)
            ->select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Produk terlaris
        $produkTerlaris = DB::table('transaction_detail')
            ->join('products', 'transaction_detail.produk_id', '=', 'products.id')
            ->select('products.id', 'products.nama_produk', DB::raw('SUM(transaction_detail.jumlah) as total_terjual'))
            ->groupBy('products.id', 'products.nama_produk')
            ->orderBy('total_terjual', 'desc')
            ->take(5)
            ->get();


        return view('admin.dashboard', compact(
            'totalPenjualan',
            'totalPembelian',
            'totalProduk',
            'totalPelanggan',
            'totalSupplier',
            'produkStokMenipis',
            'transaksiTerbaru',
            'pembelianTerbaru',
            'penjualanBulanan',
            'pembelianBulanan',
            'produkTerlaris'
        ));
    }
}
