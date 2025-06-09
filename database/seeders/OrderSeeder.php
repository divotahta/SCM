<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Create an order
        $order = Order::create([
            'pelanggan_id' => 1,
            'tanggal_pesanan' => now(),
            'status_pesanan' => 'completed',
            'total_produk' => 2,
            'sub_total' => 1500000,
            'pajak' => 150000,
            'total' => 1650000,
            'nomor_faktur' => 'INV001',
            'jenis_pembayaran' => 'transfer',
            'bayar' => 1650000,
        ]);

        // Create order details
        OrderDetail::create([
            'pesanan_id' => $order->id,
            'produk_id' => 1,
            'jumlah' => 10,
            'harga_satuan' => 85000,
            'total' => 850000,
        ]);

        OrderDetail::create([
            'pesanan_id' => $order->id,
            'produk_id' => 2,
            'jumlah' => 5,
            'harga_satuan' => 175000,
            'total' => 875000,
        ]);
    }
} 