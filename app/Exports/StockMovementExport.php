<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockMovementExport implements FromCollection, WithHeadings, WithMapping
{
    protected $histories;

    public function __construct($histories)
    {
        $this->histories = $histories;
    }

    public function collection()
    {
        return $this->histories;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Produk',
            'Tipe',
            'Jumlah',
            'Stok Lama',
            'Stok Baru',
            'Keterangan',
            'User'
        ];
    }

    public function map($history): array
    {
        return [
            $history->created_at->format('d/m/Y H:i'),
            $history->produk->nama_produk,
            $history->type == 'addition' ? 'Penambahan' : 'Pengurangan',
            $history->quantity,
            $history->old_stock,
            $history->new_stock,
            $history->description,
            $history->user->name
        ];
    }
} 