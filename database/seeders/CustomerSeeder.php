<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'nama' => 'Budi Santoso',
                'telepon' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                
                'foto' => null,
            ],
            [
                'nama' => 'Siti Rahayu',
                'telepon' => '082345678901',
                'alamat' => 'Jl. Sudirman No. 45, Jakarta',
                
                'foto' => null,
            ],
            [
                'nama' => 'PT Maju Bersama',
                'telepon' => '021-5550123',
                'alamat' => 'Jl. Gatot Subroto No. 78, Jakarta',
               
                'foto' => null,
            ],
            [
                'nama' => 'Andi Wijaya',
                'telepon' => '083456789012',
                'alamat' => 'Jl. Asia Afrika No. 56, Bandung',
                
                'foto' => null,
            ],
            [
                'nama' => 'CV Sejahtera Abadi',
                'telepon' => '021-7778899',
                'alamat' => 'Jl. Thamrin No. 90, Jakarta',
                
                'foto' => null,
            ],
            [
                'nama' => 'Dewi Lestari',
                'telepon' => '084567890123',
                'alamat' => 'Jl. Diponegoro No. 34, Surabaya',
                
                'foto' => null,
            ],
            [
                'nama' => 'PT Karya Mandiri',
                'telepon' => '021-8887766',
                'alamat' => 'Jl. Sudirman No. 12, Jakarta',
                
                'foto' => null,
            ],
            [
                'nama' => 'Rudi Hartono',
                'telepon' => '085678901234',
                'alamat' => 'Jl. Veteran No. 67, Jakarta',
               
                'foto' => null,
            ],
            [
                'nama' => 'CV Sukses Jaya',
                'telepon' => '021-9998877',
                'alamat' => 'Jl. Gatot Subroto No. 89, Jakarta',
                
                'foto' => null,
            ],
            [
                'nama' => 'Maya Putri',
                'telepon' => '086789012345',
                'alamat' => 'Jl. Asia Afrika No. 78, Bandung',
                
                'foto' => null,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
} 