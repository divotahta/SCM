<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'nama_kategori' => 'Minuman Panas',
            ],
            [
                'nama_kategori' => 'Minuman Dingin',
            ],
            [
                'nama_kategori' => 'Kopi',
            ],
            [
                'nama_kategori' => 'Teh',
            ],
            [
                'nama_kategori' => 'Makanan Ringan',
            ],
            [
                'nama_kategori' => 'Makanan Berat',
            ],
            [
                'nama_kategori' => 'Dessert',
            ],
            [
                'nama_kategori' => 'Snack',
            ],
            [
                'nama_kategori' => 'Bahan Baku',
            ],
            [
                'nama_kategori' => 'Peralatan',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
} 