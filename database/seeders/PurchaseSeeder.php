<?php

namespace Database\Seeders;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    public function run()
    {
        // Create a purchase
        $purchase = Purchase::create([
            'tanggal_pembelian' => now(),
            'nomor_pembelian' => 'PUR001',
            'pemasok_id' => 1,
            'status_pembelian' => 'completed',
            'dibuat_oleh' => 1,
        ]);

        // Create purchase details
        PurchaseDetail::create([
            'pembelian_id' => $purchase->id,
            'produk_id' => 1,
            'jumlah' => 10,
            'harga_satuan' => 75000,
            'total' => 750000,
        ]);

        PurchaseDetail::create([
            'pembelian_id' => $purchase->id,
            'produk_id' => 2,
            'jumlah' => 5,
            'harga_satuan' => 150000,
            'total' => 750000,
        ]);
    }
} 