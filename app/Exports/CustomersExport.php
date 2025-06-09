<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Customer::select(
            'name',
            'email',
            'phone',
            'address',
            'notes',
            'loyalty_level',
            'points',
            'total_purchase'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'Telepon',
            'Alamat',
            'Catatan',
            'Level Loyalitas',
            'Poin',
            'Total Pembelian'
        ];
    }
} 