<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'nama_produk' => 'Semen Portland',
                'kategori_id' => 5,
                'kode_produk' => 'SEM001',
                'harga_beli' => 75000,
                'harga_jual' => 85000,
                'stok' => 100,
                'unit_id' => 6,
            ],
            [
                'nama_produk' => 'Cat Tembok Putih',
                'kategori_id' => 3,
                'kode_produk' => 'CAT001',
                'harga_beli' => 150000,
                'harga_jual' => 175000,
                'stok' => 50,
                'unit_id' => 5,
            ],
            [
                'nama_produk' => 'Paku 2 inch',
                'kategori_id' => 4,
                'kode_produk' => 'PKU001',
                'harga_beli' => 25000,
                'harga_jual' => 30000,
                'stok' => 200,
                'unit_id' => 1,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
} 