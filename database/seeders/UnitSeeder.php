<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run()
    {
        $units = [
            [
                'nama_satuan' => 'Porsi',
            ],
            [
                'nama_satuan' => 'Piring',
            ],
            [
                'nama_satuan' => 'Kilogram',
            ],
            [
                'nama_satuan' => 'Pack',
            ],
            [
                'nama_satuan' => 'Box',
            ],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
} 