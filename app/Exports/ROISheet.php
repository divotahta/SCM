<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ROISheet implements FromArray, WithHeadings, WithTitle
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
                $item['product']->name,
                number_format($item['total_revenue'], 0, ',', '.'),
                number_format($item['total_cost'], 0, ',', '.'),
                number_format($item['investment'], 0, ',', '.'),
                number_format($item['profit'], 0, ',', '.'),
                number_format($item['roi'], 2, ',', '.') . '%',
            ];
        })->toArray();
    }

    public function headings(): array
    {
        return ['Produk', 'Total Pendapatan', 'Total Biaya', 'Investasi', 'Laba', 'ROI'];
    }

    public function title(): string
    {
        return 'ROI';
    }
} 