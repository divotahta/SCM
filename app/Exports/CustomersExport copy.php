<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Customer::all();
    }

    public function headings(): array
    {
        return [
            'Kode',
            'Nama',
            'Email',
            'Telepon',
            'Alamat',
            'Poin',
            'Level',
            'Total Pembelian',
            'Pembelian Terakhir',
            'Catatan'
        ];
    }

    public function map($customer): array
    {
        return [
            $customer->code,
            $customer->name,
            $customer->email,
            $customer->phone,
            $customer->address,
            $customer->points,
            ucfirst($customer->loyalty_level),
            number_format($customer->total_purchase, 0, ',', '.'),
            $customer->last_purchase_at ? $customer->last_purchase_at->format('d/m/Y') : '-',
            $customer->notes
        ];
    }
} 