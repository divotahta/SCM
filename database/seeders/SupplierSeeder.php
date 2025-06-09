<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'nama' => 'PT Kopi Indonesia',
                'email' => 'info@kopiindonesia.com',
                'telepon' => '021-5550123',
                'alamat' => 'Jl. Kopi No. 1, Jakarta',
                'nama_toko' => 'Toko Kopi Indonesia',
                'jenis' => 'distributor',
                'nama_bank' => 'BCA',
                'pemegang_rekening' => 'PT Kopi Indonesia',
                'nomor_rekening' => '1234567890',
                'foto' => null,
            ],
            [
                'nama' => 'CV Susu Segar',
                'email' => 'contact@sususegar.com',
                'telepon' => '021-6667890',
                'alamat' => 'Jl. Susu No. 2, Jakarta',
                'nama_toko' => 'Toko Susu Segar',
                'jenis' => 'grosir',
                'nama_bank' => 'Mandiri',
                'pemegang_rekening' => 'CV Susu Segar',
                'nomor_rekening' => '0987654321',
                'foto' => null,
            ],
            [
                'nama' => 'PT Teh Premium',
                'email' => 'info@tehpremium.com',
                'telepon' => '021-7778901',
                'alamat' => 'Jl. Teh No. 3, Jakarta',
                'nama_toko' => 'Toko Teh Premium',
                'jenis' => 'distributor',
                'nama_bank' => 'BNI',
                'pemegang_rekening' => 'PT Teh Premium',
                'nomor_rekening' => '1122334455',
                'foto' => null,
            ],
            [
                'nama' => 'CV Roti Enak',
                'email' => 'contact@rotienak.com',
                'telepon' => '021-8889012',
                'alamat' => 'Jl. Roti No. 4, Jakarta',
                'nama_toko' => 'Toko Roti Enak',
                'jenis' => 'grosir',
                'nama_bank' => 'BRI',
                'pemegang_rekening' => 'CV Roti Enak',
                'nomor_rekening' => '5544332211',
                'foto' => null,
            ],
            [
                'nama' => 'PT Gula Manis',
                'email' => 'info@gulamanis.com',
                'telepon' => '021-9990123',
                'alamat' => 'Jl. Gula No. 5, Jakarta',
                'nama_toko' => 'Toko Gula Manis',
                'jenis' => 'distributor',
                'nama_bank' => 'BCA',
                'pemegang_rekening' => 'PT Gula Manis',
                'nomor_rekening' => '6677889900',
                'foto' => null,
            ],
            [
                'nama' => 'CV Buah Segar',
                'email' => 'contact@buahsegar.com',
                'telepon' => '021-5551234',
                'alamat' => 'Jl. Buah No. 6, Jakarta',
                'nama_toko' => 'Toko Buah Segar',
                'jenis' => 'grosir',
                'nama_bank' => 'Mandiri',
                'pemegang_rekening' => 'CV Buah Segar',
                'nomor_rekening' => '9988776655',
                'foto' => null,
            ],
            [
                'nama' => 'PT Snack Enak',
                'email' => 'info@snackenak.com',
                'telepon' => '021-6662345',
                'alamat' => 'Jl. Snack No. 7, Jakarta',
                'nama_toko' => 'Toko Snack Enak',
                'jenis' => 'distributor',
                'nama_bank' => 'BNI',
                'pemegang_rekening' => 'PT Snack Enak',
                'nomor_rekening' => '1122334455',
                'foto' => null,
            ],
            [
                'nama' => 'CV Es Krim',
                'email' => 'contact@eskrim.com',
                'telepon' => '021-7773456',
                'alamat' => 'Jl. Es Krim No. 8, Jakarta',
                'nama_toko' => 'Toko Es Krim',
                'jenis' => 'grosir',
                'nama_bank' => 'BRI',
                'pemegang_rekening' => 'CV Es Krim',
                'nomor_rekening' => '5544332211',
                'foto' => null,
            ],
            [
                'nama' => 'PT Kue Lezat',
                'email' => 'info@kuelezat.com',
                'telepon' => '021-8884567',
                'alamat' => 'Jl. Kue No. 9, Jakarta',
                'nama_toko' => 'Toko Kue Lezat',
                'jenis' => 'distributor',
                'nama_bank' => 'BCA',
                'pemegang_rekening' => 'PT Kue Lezat',
                'nomor_rekening' => '6677889900',
                'foto' => null,
            ],
            [
                'nama' => 'CV Bumbu Dapur',
                'email' => 'contact@bumbudapur.com',
                'telepon' => '021-9995678',
                'alamat' => 'Jl. Bumbu No. 10, Jakarta',
                'nama_toko' => 'Toko Bumbu Dapur',
                'jenis' => 'grosir',
                'nama_bank' => 'Mandiri',
                'pemegang_rekening' => 'CV Bumbu Dapur',
                'nomor_rekening' => '9988776655',
                'foto' => null,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
} 