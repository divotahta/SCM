<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class IncomeStatementSheet implements FromArray, WithHeadings, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return [
            ['Pendapatan', number_format($this->data['revenue'], 0, ',', '.')],
            ['Harga Pokok Penjualan', number_format($this->data['cogs'], 0, ',', '.')],
            ['Laba Kotor', number_format($this->data['gross_profit'], 0, ',', '.')],
            ['Beban Operasional', ''],
            ...collect($this->data['operational_expenses'])->map(fn($value, $key) => 
                [$key, number_format($value, 0, ',', '.')]
            )->toArray(),
            ['Total Beban Operasional', number_format($this->data['operational_expenses_total'], 0, ',', '.')],
            ['Laba Operasional', number_format($this->data['operating_profit'], 0, ',', '.')],
            ['Beban Non-Operasional', ''],
            ...collect($this->data['non_operational_expenses'])->map(fn($value, $key) => 
                [$key, number_format($value, 0, ',', '.')]
            )->toArray(),
            ['Total Beban Non-Operasional', number_format($this->data['non_operational_expenses_total'], 0, ',', '.')],
            ['Laba Bersih', number_format($this->data['net_profit'], 0, ',', '.')],
        ];
    }

    public function headings(): array
    {
        return ['Keterangan', 'Jumlah'];
    }

    public function title(): string
    {
        return 'Laba Rugi';
    }
} 