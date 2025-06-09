<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            // Minuman Panas
            [
                'nama_produk' => 'Teh Tarik',
                'kategori_id' => 1,
                'kode_produk' => 'TTR001',
                'harga_beli' => 5000,
                'harga_jual' => 15000,
                'stok' => 100,
                'unit_id' => 1,
            ],
            [
                'nama_produk' => 'Susu Jahe',
                'kategori_id' => 1,
                'kode_produk' => 'SJH001',
                'harga_beli' => 8000,
                'harga_jual' => 18000,
                'stok' => 50,
                'unit_id' => 1,
            ],

            // Minuman Dingin
            [
                'nama_produk' => 'Es Teh Manis',
                'kategori_id' => 2,
                'kode_produk' => 'ETM001',
                'harga_beli' => 3000,
                'harga_jual' => 8000,
                'stok' => 100,
                'unit_id' => 1,
            ],
            [
                'nama_produk' => 'Es Jeruk',
                'kategori_id' => 2,
                'kode_produk' => 'EJR001',
                'harga_beli' => 5000,
                'harga_jual' => 12000,
                'stok' => 80,
                'unit_id' => 1,
            ],

            // Kopi
            [
                'nama_produk' => 'Kopi Hitam',
                'kategori_id' => 3,
                'kode_produk' => 'KPH001',
                'harga_beli' => 4000,
                'harga_jual' => 10000,
                'stok' => 100,
                'unit_id' => 1,
            ],
            [
                'nama_produk' => 'Cappuccino',
                'kategori_id' => 3,
                'kode_produk' => 'CPC001',
                'harga_beli' => 8000,
                'harga_jual' => 18000,
                'stok' => 50,
                'unit_id' => 1,
            ],

            // Teh
            [
                'nama_produk' => 'Teh Hijau',
                'kategori_id' => 4,
                'kode_produk' => 'THJ001',
                'harga_beli' => 4000,
                'harga_jual' => 10000,
                'stok' => 80,
                'unit_id' => 1,
            ],
            [
                'nama_produk' => 'Teh Oolong',
                'kategori_id' => 4,
                'kode_produk' => 'TOL001',
                'harga_beli' => 5000,
                'harga_jual' => 12000,
                'stok' => 60,
                'unit_id' => 1,
            ],

            // Makanan Ringan
            [
                'nama_produk' => 'Kentang Goreng',
                'kategori_id' => 5,
                'kode_produk' => 'KTG001',
                'harga_beli' => 8000,
                'harga_jual' => 20000,
                'stok' => 50,
                'unit_id' => 2,
            ],
            [
                'nama_produk' => 'Onion Ring',
                'kategori_id' => 5,
                'kode_produk' => 'ONR001',
                'harga_beli' => 7000,
                'harga_jual' => 18000,
                'stok' => 40,
                'unit_id' => 2,
            ],

            // Makanan Berat
            [
                'nama_produk' => 'Nasi Goreng',
                'kategori_id' => 6,
                'kode_produk' => 'NSG001',
                'harga_beli' => 12000,
                'harga_jual' => 25000,
                'stok' => 30,
                'unit_id' => 2,
            ],
            [
                'nama_produk' => 'Mie Goreng',
                'kategori_id' => 6,
                'kode_produk' => 'MGR001',
                'harga_beli' => 10000,
                'harga_jual' => 22000,
                'stok' => 30,
                'unit_id' => 2,
            ],

            // Dessert
            [
                'nama_produk' => 'Cheesecake',
                'kategori_id' => 7,
                'kode_produk' => 'CHC001',
                'harga_beli' => 15000,
                'harga_jual' => 30000,
                'stok' => 20,
                'unit_id' => 2,
            ],
            [
                'nama_produk' => 'Tiramisu',
                'kategori_id' => 7,
                'kode_produk' => 'TRM001',
                'harga_beli' => 12000,
                'harga_jual' => 28000,
                'stok' => 20,
                'unit_id' => 2,
            ],

            // Snack
            [
                'nama_produk' => 'Popcorn',
                'kategori_id' => 8,
                'kode_produk' => 'PPC001',
                'harga_beli' => 5000,
                'harga_jual' => 15000,
                'stok' => 40,
                'unit_id' => 2,
            ],
            [
                'nama_produk' => 'Nachos',
                'kategori_id' => 8,
                'kode_produk' => 'NCH001',
                'harga_beli' => 8000,
                'harga_jual' => 20000,
                'stok' => 30,
                'unit_id' => 2,
            ],

            // Bahan Baku
            [
                'nama_produk' => 'Biji Kopi Arabika',
                'kategori_id' => 9,
                'kode_produk' => 'BKA001',
                'harga_beli' => 150000,
                'harga_jual' => 180000,
                'stok' => 10,
                'unit_id' => 3,
            ],
            [
                'nama_produk' => 'Teh Hijau Premium',
                'kategori_id' => 9,
                'kode_produk' => 'THP001',
                'harga_beli' => 120000,
                'harga_jual' => 150000,
                'stok' => 8,
                'unit_id' => 3,
            ],

            // Peralatan
            [
                'nama_produk' => 'Cup Paper 12oz',
                'kategori_id' => 10,
                'kode_produk' => 'CPP001',
                'harga_beli' => 50000,
                'harga_jual' => 60000,
                'stok' => 20,
                'unit_id' => 4,
            ],
            [
                'nama_produk' => 'Straw Plastik',
                'kategori_id' => 10,
                'kode_produk' => 'STP001',
                'harga_beli' => 25000,
                'harga_jual' => 30000,
                'stok' => 15,
                'unit_id' => 4,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
} 