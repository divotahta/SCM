<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockHistory;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class StockHistorySeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $products = Product::all();

        // Buat riwayat stok untuk setiap produk
        foreach ($products as $product) {
            // Riwayat masuk
            for ($i = 0; $i < 5; $i++) {
                $quantity = rand(10, 50);
                $oldStock = $product->stok;
                $newStock = $oldStock + $quantity;

                StockHistory::create([
                    'produk_id' => $product->id,
                    'jenis' => 'masuk',
                    'jumlah' => $quantity,
                    'stok_lama' => $oldStock,
                    'stok_baru' => $newStock,
                    'keterangan' => 'Pembelian stok',
                    'dibuat_oleh' => $users->random()->id,
                    'created_at' => Carbon::now()->subDays(rand(1, 30))
                ]);

                $product->stok = $newStock;
                $product->save();
            }

            // Riwayat keluar
            for ($i = 0; $i < 5; $i++) {
                $quantity = rand(1, 10);
                $oldStock = $product->stok;
                $newStock = max(0, $oldStock - $quantity);

                StockHistory::create([
                    'produk_id' => $product->id,
                    'jenis' => 'keluar',
                    'jumlah' => $quantity,
                    'stok_lama' => $oldStock,
                    'stok_baru' => $newStock,
                    'keterangan' => 'Penjualan',
                    'dibuat_oleh' => $users->random()->id,
                    'created_at' => Carbon::now()->subDays(rand(1, 30))
                ]);

                $product->stok = $newStock;
                $product->save();
            }
        }
    }
} 