<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Bahan Bangunan',
            'Peralatan',
            'Cat',
            'Paku',
            'Semen',
            'Bata',
            'Keramik',
            'Pipa',
        ];

        foreach ($categories as $category) {
            Category::create([
                'nama_kategori' => $category
            ]);
        }
    }
} 