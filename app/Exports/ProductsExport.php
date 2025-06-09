<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Product::with(['category', 'unit'])
            ->when($this->request->search, function($q) {
                return $q->where('name', 'like', "%{$this->request->search}%")
                    ->orWhere('code', 'like', "%{$this->request->search}%");
            })
            ->when($this->request->category_id, function($q) {
                return $q->where('category_id', $this->request->category_id);
            })
            ->when($this->request->stock_status, function($q) {
                if ($this->request->stock_status === 'low') {
                    return $q->where('stock', '<=', 10);
                } elseif ($this->request->stock_status === 'out') {
                    return $q->where('stock', 0);
                }
                return $q;
            });

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode',
            'Nama Produk',
            'Kategori',
            'Satuan',
            'Harga Beli',
            'Harga Jual',
            'Stok',
            'Status Stok'
        ];
    }

    public function map($product): array
    {
        static $rowNumber = 1;
        
        return [
            $rowNumber++,
            $product->code,
            $product->name,
            $product->category->name,
            $product->unit->name,
            number_format($product->purchase_price, 0, ',', '.'),
            number_format($product->selling_price, 0, ',', '.'),
            $product->stock,
            $this->getStockStatus($product->stock)
        ];
    }

    private function getStockStatus($stock)
    {
        if ($stock <= 0) {
            return 'Habis';
        } elseif ($stock <= 10) {
            return 'Rendah';
        }
        return 'Tersedia';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
} 