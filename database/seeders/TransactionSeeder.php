<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\User;
use App\Models\Customer;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $customers = Customer::all();
        $products = Product::all();

        // Buat 20 transaksi
        for ($i = 0; $i < 20; $i++) {
            $transaction = Transaction::create([
                'kode_transaksi' => 'TRX-' . date('YmdHis') . rand(100, 999),
                'user_id' => $users->random()->id,
                'pelanggan_id' => $customers->random()->id,
                'total_harga' => 0,
                'total_bayar' => 0,
                'total_kembali' => 0,
                'metode_pembayaran' => collect(['cash', 'transfer', 'qris'])->random(),
                'status' => 'selesai',
                'catatan' => 'Transaksi test',
                'created_at' => Carbon::now()->subDays(rand(1, 30))
            ]);

            $total = 0;
            // Buat 1-5 item untuk setiap transaksi
            $itemCount = rand(1, 5);
            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $quantity = rand(1, 5);
                $price = $product->harga_jual;
                $subtotal = $quantity * $price;

                TransactionDetail::create([
                    'transaksi_id' => $transaction->id,
                    'produk_id' => $product->id,
                    'jumlah' => $quantity,
                    'harga' => $price,
                    'subtotal' => $subtotal
                ]);

                $total += $subtotal;
            }

            // Update total transaksi
            $transaction->update([
                'total_harga' => $total,
                'total_bayar' => $total,
                'total_kembali' => 0
            ]);
        }
    }
} 