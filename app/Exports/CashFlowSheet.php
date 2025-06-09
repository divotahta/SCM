<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class CashFlowSheet implements FromArray, WithHeadings, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return [
            ['Arus Kas dari Aktivitas Operasi', ''],
            ...collect($this->data['operating_activities'])->map(fn($value, $key) => 
                [$key, number_format($value, 0, ',', '.')]
            )->toArray(),
            ['Arus Kas dari Aktivitas Investasi', ''],
            ...collect($this->data['investing_activities'])->map(fn($value, $key) => 
                [$key, number_format($value, 0, ',', '.')]
            )->toArray(),
            ['Arus Kas dari Aktivitas Pendanaan', ''],
            ...collect($this->data['financing_activities'])->map(fn($value, $key) => 
                [$key, number_format($value, 0, ',', '.')]
            )->toArray(),
            ['Arus Kas Bersih', number_format($this->data['net_cash_flow'], 0, ',', '.')],
        ];
    }

    public function headings(): array
    {
        return ['Keterangan', 'Jumlah'];
    }

    public function title(): string
    {
        return 'Arus Kas';
    }
} 