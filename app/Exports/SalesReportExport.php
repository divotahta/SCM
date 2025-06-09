<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SalesReportExport implements WithMultipleSheets
{
    protected $orders;
    protected $summary;
    protected $salesTrend;
    protected $comparison;
    protected $topProducts;
    protected $topCustomers;
    protected $topCategories;
    protected $profitAnalysis;

    public function __construct($orders, $summary, $salesTrend, $comparison, $topProducts, $topCustomers, $topCategories, $profitAnalysis)
    {
        $this->orders = $orders;
        $this->summary = $summary;
        $this->salesTrend = $salesTrend;
        $this->comparison = $comparison;
        $this->topProducts = $topProducts;
        $this->topCustomers = $topCustomers;
        $this->topCategories = $topCategories;
        $this->profitAnalysis = $profitAnalysis;
    }

    public function sheets(): array
    {
        return [
            'Ringkasan' => new SummarySheet($this->summary, $this->comparison),
            'Tren Penjualan' => new SalesTrendSheet($this->salesTrend),
            'Top Produk' => new TopProductsSheet($this->topProducts),
            'Top Pelanggan' => new TopCustomersSheet($this->topCustomers),
            'Top Kategori' => new TopCategoriesSheet($this->topCategories),
            'Analisis Profit' => new ProfitAnalysisSheet($this->profitAnalysis),
            'Detail Transaksi' => new TransactionDetailSheet($this->orders)
        ];
    }
}

class SummarySheet implements FromCollection, WithHeadings
{
    protected $summary;
    protected $comparison;

    public function __construct($summary, $comparison)
    {
        $this->summary = $summary;
        $this->comparison = $comparison;
    }

    public function collection()
    {
        return collect([
            [
                'Metrik' => 'Total Penjualan',
                'Nilai' => $this->summary['total_sales'],
                'Periode Sebelumnya' => $this->comparison['previous']->total_sales,
                'Pertumbuhan' => $this->comparison['growth']['sales'] . '%'
            ],
            [
                'Metrik' => 'Total Profit',
                'Nilai' => $this->summary['total_profit'],
                'Periode Sebelumnya' => $this->comparison['previous']->total_profit,
                'Pertumbuhan' => $this->comparison['growth']['profit'] . '%'
            ],
            [
                'Metrik' => 'Total Order',
                'Nilai' => $this->summary['total_orders'],
                'Periode Sebelumnya' => $this->comparison['previous']->total_orders,
                'Pertumbuhan' => $this->comparison['growth']['orders'] . '%'
            ],
            [
                'Metrik' => 'Rata-rata Order',
                'Nilai' => $this->summary['average_order_value'],
                'Periode Sebelumnya' => $this->comparison['previous']->total_sales / $this->comparison['previous']->total_orders,
                'Pertumbuhan' => '-'
            ],
            [
                'Metrik' => 'Profit Margin',
                'Nilai' => $this->summary['profit_margin'] . '%',
                'Periode Sebelumnya' => ($this->comparison['previous']->total_profit / $this->comparison['previous']->total_sales) * 100 . '%',
                'Pertumbuhan' => '-'
            ]
        ]);
    }

    public function headings(): array
    {
        return ['Metrik', 'Nilai', 'Periode Sebelumnya', 'Pertumbuhan'];
    }
}

class SalesTrendSheet implements FromCollection, WithHeadings
{
    protected $salesTrend;

    public function __construct($salesTrend)
    {
        $this->salesTrend = $salesTrend;
    }

    public function collection()
    {
        return $this->salesTrend;
    }

    public function headings(): array
    {
        return ['Tanggal', 'Total Penjualan', 'Total Order'];
    }
}

class TopProductsSheet implements FromCollection, WithHeadings
{
    protected $topProducts;

    public function __construct($topProducts)
    {
        $this->topProducts = $topProducts;
    }

    public function collection()
    {
        return $this->topProducts;
    }

    public function headings(): array
    {
        return ['Kode', 'Produk', 'Total Quantity', 'Total Penjualan', 'Total Profit', 'Profit Margin'];
    }
}

class TopCustomersSheet implements FromCollection, WithHeadings
{
    protected $topCustomers;

    public function __construct($topCustomers)
    {
        $this->topCustomers = $topCustomers;
    }

    public function collection()
    {
        return $this->topCustomers;
    }

    public function headings(): array
    {
        return ['Kode', 'Pelanggan', 'Total Order', 'Total Penjualan', 'Total Profit', 'Profit Margin'];
    }
}

class TopCategoriesSheet implements FromCollection, WithHeadings
{
    protected $topCategories;

    public function __construct($topCategories)
    {
        $this->topCategories = $topCategories;
    }

    public function collection()
    {
        return $this->topCategories;
    }

    public function headings(): array
    {
        return ['Kategori', 'Total Quantity', 'Total Penjualan', 'Total Profit', 'Profit Margin'];
    }
}

class ProfitAnalysisSheet implements FromCollection, WithHeadings
{
    protected $profitAnalysis;

    public function __construct($profitAnalysis)
    {
        $this->profitAnalysis = $profitAnalysis;
    }

    public function collection()
    {
        return $this->profitAnalysis;
    }

    public function headings(): array
    {
        return ['Kategori', 'Total Penjualan', 'Total Profit', 'Profit Margin'];
    }
}

class TransactionDetailSheet implements FromCollection, WithHeadings
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'No. Order',
            'Pelanggan',
            'Total Amount',
            'Total Profit',
            'Profit Margin',
            'Status'
        ];
    }
} 