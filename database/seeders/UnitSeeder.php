<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run()
    {
        $units = [
            'Pcs',
            'Box',
            'Kg',
            'Meter',
            'Liter',
            'Sak',
            'Unit',
            'Set',
        ];

        foreach ($units as $unit) {
            Unit::create([
                'nama_satuan' => $unit
            ]);
        }
    }
} 