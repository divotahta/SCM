<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $customers = [
            [
                'nama' => 'PT Konstruksi Maju',
                'email' => 'info@konstruksimaju.com',
                'telepon' => '083456789012',
                'alamat' => 'Jl. Konstruksi No. 1, Jakarta',
                'jenis' => 'Kontraktor',
                'nama_bank' => 'BNI',
                'pemegang_rekening' => 'PT Konstruksi Maju',
                'nomor_rekening' => '1122334455',
            ],
            [
                'nama' => 'CV Bangun Bersama',
                'email' => 'contact@bangunbersama.com',
                'telepon' => '084567890123',
                'alamat' => 'Jl. Bangun No. 2, Jakarta',
                'jenis' => 'Developer',
                'nama_bank' => 'BRI',
                'pemegang_rekening' => 'CV Bangun Bersama',
                'nomor_rekening' => '5544332211',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
} 