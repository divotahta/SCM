<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SuppliersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Supplier::select(
            'nama',
            'email',
            'telepon',
            'alamat',
            'nama_toko',
            'jenis',
            'nama_bank',
            'pemegang_rekening',
            'nomor_rekening'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'Telepon',
            'Alamat',
            'Nama Toko',
            'Jenis',
            'Nama Bank',
            'Pemegang Rekening',
            'Nomor Rekening'
        ];
    }
} 