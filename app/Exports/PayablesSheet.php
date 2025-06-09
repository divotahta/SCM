<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class PayablesSheet implements FromArray, WithHeadings, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data->map(function ($item) {
            return [
                $item['supplier']->name,
                number_format($item['total_amount'], 0, ',', '.'),
                number_format($item['total_paid'], 0, ',', '.'),
                number_format($item['total_due'], 0, ',', '.'),
            ];
        })->toArray();
    }

    public function headings(): array
    {
        return ['Supplier', 'Total Tagihan', 'Total Dibayar', 'Sisa Hutang'];
    }

    public function title(): string
    {
        return 'Hutang';
    }
} 