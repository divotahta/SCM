<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Customer;
use App\Models\StockHistory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Data untuk card
            $totalPenjualan = Transaction::where('status', 'selesai')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('total_harga');

            if ($totalPenjualan === null) {
                $totalPenjualan = 0;
            }
            
            $totalTransaksi = Transaction::where('status', 'selesai')
                ->whereMonth('created_at', Carbon::now()->month)
                ->count();
            
            $totalProduk = Product::count();
            $totalPelanggan = Customer::count();

            // Data untuk grafik penjualan 7 hari terakhir
            $penjualanHarian = Transaction::where('status', 'selesai')
                ->whereDate('created_at', '>=', Carbon::now()->subDays(7))
                ->select(
                    DB::raw('DATE(created_at) as tanggal'),
                    DB::raw('COALESCE(SUM(total_harga), 0) as total')
                )
                ->groupBy('tanggal')
                ->orderBy('tanggal')
                ->get();

            // Data untuk produk terlaris
            $produkTerlaris = DB::table('produk')
                ->join('detail_transaksi', 'produk.id', '=', 'detail_transaksi.produk_id')
                ->join('transaction', 'transaction.id', '=', 'detail_transaksi.transaksi_id')
                ->where('transaction.status', 'selesai')
                ->whereMonth('transaction.created_at', Carbon::now()->month)
                ->select(
                    'produk.*',
                    DB::raw('SUM(detail_transaksi.jumlah) as total_terjual'),
                    DB::raw('SUM(detail_transaksi.jumlah * detail_transaksi.harga) as total_pendapatan')
                )
                ->groupBy('produk.id')
                ->orderByDesc('total_terjual')
                ->limit(5)
                ->get();

            // Data untuk stok menipis
            $stokMenipis = Product::where('stok', '<=', 10)
                ->orderBy('stok')
                ->take(5)
                ->get();

            // Data untuk transaksi terakhir
            $transaksiTerakhir = Transaction::with(['customer', 'user'])
                ->where('status', 'selesai')
                ->latest()
                ->take(5)
                ->get();

            // Data untuk riwayat stok
            $riwayatStok = StockHistory::with(['product', 'user'])
                ->latest()
                ->take(5)
                ->get();

            return view('admin.dashboard', [
                'totalPenjualan' => $totalPenjualan,
                'totalTransaksi' => $totalTransaksi,
                'totalProduk' => $totalProduk,
                'totalPelanggan' => $totalPelanggan,
                'penjualanHarian' => $penjualanHarian,
                'produkTerlaris' => $produkTerlaris,
                'stokMenipis' => $stokMenipis,
                'transaksiTerakhir' => $transaksiTerakhir,
                'riwayatStok' => $riwayatStok
            ]);

        } catch (\Exception $e) {
            return view('admin.dashboard', [
                'totalPenjualan' => 0,
                'totalTransaksi' => 0,
                'totalProduk' => 0,
                'totalPelanggan' => 0,
                'penjualanHarian' => collect(),
                'produkTerlaris' => collect(),
                'stokMenipis' => collect(),
                'transaksiTerakhir' => collect(),
                'riwayatStok' => collect()
            ]);
        }
    }
}
