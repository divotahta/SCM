<?php

namespace App\Imports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SuppliersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Supplier([
            'nama' => $row['nama'],
            'email' => $row['email'],
            'telepon' => $row['telepon'],
            'alamat' => $row['alamat'],
            'nama_toko' => $row['nama_toko'],
            'jenis' => $row['jenis'],
            'nama_bank' => $row['nama_bank'],
            'pemegang_rekening' => $row['pemegang_rekening'],
            'nomor_rekening' => $row['nomor_rekening']
        ]);
    }
} 