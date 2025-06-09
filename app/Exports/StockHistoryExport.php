<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockHistoryExport implements FromCollection, WithHeadings, WithMapping
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
            'Referensi',
            'Keterangan',
            'Dibuat Oleh'
        ];
    }

    public function map($history): array
    {
        return [
            $history->created_at->format('d/m/Y H:i'),
            $history->product->name,
            $this->getTypeLabel($history->type),
            $history->quantity . ' ' . $history->product->unit->name,
            $this->getReferenceLabel($history),
            $history->notes,
            $history->createdBy->name
        ];
    }

    protected function getTypeLabel($type)
    {
        return [
            'in' => 'Masuk',
            'out' => 'Keluar',
            'adjustment' => 'Penyesuaian'
        ][$type] ?? $type;
    }

    protected function getReferenceLabel($history)
    {
        if ($history->reference_type == 'purchase') {
            return 'Pembelian #' . $history->reference_id;
        } elseif ($history->reference_type == 'sale') {
            return 'Penjualan #' . $history->reference_id;
        }
        return 'Penyesuaian Manual';
    }
} 