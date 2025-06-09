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
                'email' => 'budi.santoso@gmail.com',
                'telepon' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                'jenis' => 'perorangan',
                'nama_bank' => 'BCA',
                'pemegang_rekening' => 'Budi Santoso',
                'nomor_rekening' => '1234567890',
                'foto' => null,
            ],
            [
                'nama' => 'Siti Rahayu',
                'email' => 'siti.rahayu@yahoo.com',
                'telepon' => '082345678901',
                'alamat' => 'Jl. Sudirman No. 45, Jakarta',
                'jenis' => 'perorangan',
                'nama_bank' => 'Mandiri',
                'pemegang_rekening' => 'Siti Rahayu',
                'nomor_rekening' => '0987654321',
                'foto' => null,
            ],
            [
                'nama' => 'PT Maju Bersama',
                'email' => 'info@majubersama.com',
                'telepon' => '021-5550123',
                'alamat' => 'Jl. Gatot Subroto No. 78, Jakarta',
                'jenis' => 'perusahaan',
                'nama_bank' => 'BNI',
                'pemegang_rekening' => 'PT Maju Bersama',
                'nomor_rekening' => '1122334455',
                'foto' => null,
            ],
            [
                'nama' => 'Andi Wijaya',
                'email' => 'andi.wijaya@gmail.com',
                'telepon' => '083456789012',
                'alamat' => 'Jl. Asia Afrika No. 56, Bandung',
                'jenis' => 'perorangan',
                'nama_bank' => 'BRI',
                'pemegang_rekening' => 'Andi Wijaya',
                'nomor_rekening' => '5544332211',
                'foto' => null,
            ],
            [
                'nama' => 'CV Sejahtera Abadi',
                'email' => 'admin@sejahteraabadi.com',
                'telepon' => '021-7778899',
                'alamat' => 'Jl. Thamrin No. 90, Jakarta',
                'jenis' => 'perusahaan',
                'nama_bank' => 'BCA',
                'pemegang_rekening' => 'CV Sejahtera Abadi',
                'nomor_rekening' => '6677889900',
                'foto' => null,
            ],
            [
                'nama' => 'Dewi Lestari',
                'email' => 'dewi.lestari@yahoo.com',
                'telepon' => '084567890123',
                'alamat' => 'Jl. Diponegoro No. 34, Surabaya',
                'jenis' => 'perorangan',
                'nama_bank' => 'Mandiri',
                'pemegang_rekening' => 'Dewi Lestari',
                'nomor_rekening' => '9988776655',
                'foto' => null,
            ],
            [
                'nama' => 'PT Karya Mandiri',
                'email' => 'contact@karyamandiri.com',
                'telepon' => '021-8887766',
                'alamat' => 'Jl. Sudirman No. 12, Jakarta',
                'jenis' => 'perusahaan',
                'nama_bank' => 'BNI',
                'pemegang_rekening' => 'PT Karya Mandiri',
                'nomor_rekening' => '1122334455',
                'foto' => null,
            ],
            [
                'nama' => 'Rudi Hartono',
                'email' => 'rudi.hartono@gmail.com',
                'telepon' => '085678901234',
                'alamat' => 'Jl. Veteran No. 67, Jakarta',
                'jenis' => 'perorangan',
                'nama_bank' => 'BRI',
                'pemegang_rekening' => 'Rudi Hartono',
                'nomor_rekening' => '5544332211',
                'foto' => null,
            ],
            [
                'nama' => 'CV Sukses Jaya',
                'email' => 'info@suksesjaya.com',
                'telepon' => '021-9998877',
                'alamat' => 'Jl. Gatot Subroto No. 89, Jakarta',
                'jenis' => 'perusahaan',
                'nama_bank' => 'BCA',
                'pemegang_rekening' => 'CV Sukses Jaya',
                'nomor_rekening' => '6677889900',
                'foto' => null,
            ],
            [
                'nama' => 'Maya Putri',
                'email' => 'maya.putri@yahoo.com',
                'telepon' => '086789012345',
                'alamat' => 'Jl. Asia Afrika No. 78, Bandung',
                'jenis' => 'perorangan',
                'nama_bank' => 'Mandiri',
                'pemegang_rekening' => 'Maya Putri',
                'nomor_rekening' => '9988776655',
                'foto' => null,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
} 