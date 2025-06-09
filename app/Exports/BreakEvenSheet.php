<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class BreakEvenSheet implements FromArray, WithHeadings, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $rows = [
            ['Biaya Tetap', ''],
            ...collect($this->data['fixed_costs'])->map(fn($value, $key) => 
                [$key, number_format($value, 0, ',', '.')]
            )->toArray(),
            ['Total Biaya Tetap', number_format(array_sum($this->data['fixed_costs']), 0, ',', '.')],
            [''],
            ['Analisis Break Even per Produk', ''],
        ];

        foreach ($this->data['break_even_points'] as $item) {
            $rows[] = [
                $item['product']->name,
                number_format($item['break_even_units'], 0, ',', '.'),
                number_format($item['break_even_sales'], 0, ',', '.'),
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Keterangan', 'Jumlah Unit', 'Jumlah Penjualan'];
    }

    public function title(): string
    {
        return 'Break Even';
    }
} 