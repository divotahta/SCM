<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $suppliers = [
            [
                'nama' => 'PT Bangunan Jaya',
                'email' => 'info@bangunanjaya.com',
                'telepon' => '081234567890',
                'alamat' => 'Jl. Bangunan No. 1, Jakarta',
                'nama_toko' => 'Toko Bangunan Jaya',
                'jenis' => 'Distributor',
                'nama_bank' => 'BCA',
                'pemegang_rekening' => 'PT Bangunan Jaya',
                'nomor_rekening' => '1234567890',
            ],
            [
                'nama' => 'CV Material Sukses',
                'email' => 'contact@materialsukses.com',
                'telepon' => '082345678901',
                'alamat' => 'Jl. Material No. 2, Jakarta',
                'nama_toko' => 'Toko Material Sukses',
                'jenis' => 'Supplier',
                'nama_bank' => 'Mandiri',
                'pemegang_rekening' => 'CV Material Sukses',
                'nomor_rekening' => '0987654321',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
} 