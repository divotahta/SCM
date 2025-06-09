<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockForecastExport implements FromCollection, WithHeadings, WithMapping
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function collection()
    {
        return $this->products;
    }

    public function headings(): array
    {
        return [
            'Kode',
            'Produk',
            'Kategori',
            'Stok Saat Ini',
            'Minimal Stok',
            'Rata-rata Penjualan/Bulan',
            'Lead Time (Hari)',
            'Safety Stock',
            'Reorder Point',
            'EOQ'
        ];
    }

    public function map($product): array
    {
        return [
            $product->code,
            $product->name,
            $product->category->name,
            $product->stock,
            $product->min_stock,
            number_format($product->forecast['monthly_sales'], 2),
            $product->forecast['lead_time'],
            number_format($product->forecast['safety_stock'], 2),
            number_format($product->forecast['reorder_point'], 2),
            number_format($product->forecast['eoq'], 2)
        ];
    }
} 