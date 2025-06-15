<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\StockHistory;
use App\Models\Purchase;
use App\Models\Supplier;
use Carbon\Carbon;

class StockTriggerSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();
        $suppliers = Supplier::all();

        foreach ($products as $product) {
            // Set stok awal yang rendah (antara 5-20 unit)
            $currentStock = rand(5, 20);
            $product->update(['stok' => $currentStock]);

            // Buat riwayat penggunaan stok yang tinggi dalam 3 bulan terakhir
            $threeMonthsAgo = Carbon::now()->subMonths(3);
            $weeklyUsage = rand(20, 50); // Penggunaan mingguan yang tinggi

            // Buat riwayat penggunaan stok keluar setiap minggu
            for ($i = 0; $i < 12; $i++) { // 12 minggu = 3 bulan
                $date = $threeMonthsAgo->copy()->addWeeks($i);
                
                // Buat 2-4 transaksi keluar per minggu
                $transactionsPerWeek = rand(2, 4);
                for ($j = 0; $j < $transactionsPerWeek; $j++) {
                    $quantity = rand(5, 15); // Jumlah per transaksi
                    
                    StockHistory::create([
                        'produk_id' => $product->id,
                        'jenis' => 'keluar',
                        'jumlah' => $quantity,
                        'stok_lama' => $currentStock,
                        'stok_baru' => $currentStock - $quantity,
                        'keterangan' => 'Penjualan mingguan',
                        'dibuat_oleh' => 1, // Asumsi user ID 1 adalah admin
                        'created_at' => $date->copy()->addHours(rand(1, 24))
                    ]);

                    $currentStock -= $quantity;
                }
            }

            // Buat pembelian yang menunggu persetujuan
            $purchase = Purchase::create([
                'pemasok_id' => $suppliers->random()->id,
                'nomor_pembelian' => 'INV-' . date('YmdHis') . rand(100, 999),
                'tanggal_pembelian' => Carbon::now(),
                'total_amount' => $product->harga_beli * $weeklyUsage * 2, // Beli untuk 2 minggu
                'status_pembelian' => 'pending',
                'catatan' => 'Pembelian untuk mengatasi stok menipis',
                
            ]);

            // Tambahkan detail pembelian
            $purchase->details()->create([
                'produk_id' => $product->id,
                'jumlah' => $weeklyUsage * 2, // Beli untuk 2 minggu
                'harga_satuan' => $product->harga_beli,
                'total' => $product->harga_beli * $weeklyUsage * 2
            ]);
        }
    }
} 