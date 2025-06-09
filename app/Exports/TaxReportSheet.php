<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class TaxReportSheet implements FromArray, WithHeadings, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return [
            ['Pajak Penjualan (PPN)', number_format($this->data['sales_tax'], 0, ',', '.')],
            ['Pajak Pembelian (PPN Masukan)', number_format($this->data['purchase_tax'], 0, ',', '.')],
            ['Pajak Penghasilan', number_format($this->data['income_tax'], 0, ',', '.')],
            ['Pajak Terutang', number_format($this->data['tax_payable'], 0, ',', '.')],
        ];
    }

    public function headings(): array
    {
        return ['Keterangan', 'Jumlah'];
    }

    public function title(): string
    {
        return 'Pajak';
    }
} 