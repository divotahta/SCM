<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\PDF;
use App\Exports\FinancialReportExport;
use App\Models\Transaction;

class FinancialReportController extends Controller
{
    public function index(Request $request)
    {
        // Filter tanggal
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
        $reportType = $request->type ?? 'income_statement';

        // Data untuk laporan laba rugi
        $incomeStatement = $this->getIncomeStatement($startDate, $endDate);

        // Data untuk laporan arus kas
        $cashFlow = $this->getCashFlow($startDate, $endDate);

        // Data untuk laporan piutang
        $receivables = $this->getReceivables($startDate, $endDate);

        // Data untuk laporan hutang
        $payables = $this->getPayables($startDate, $endDate);

        // Data untuk laporan pajak
        $taxReport = $this->getTaxReport($startDate, $endDate);

        // Data untuk analisis break-even
        $breakEven = $this->getBreakEvenAnalysis($startDate, $endDate);

        // Data untuk perhitungan ROI
        $roiAnalysis = $this->getROIAnalysis($startDate, $endDate);

        if ($request->export) {
            if ($request->export === 'excel') {
                return Excel::download(new FinancialReportExport(
                    $incomeStatement,
                    $cashFlow,
                    $receivables,
                    $payables,
                    $taxReport,
                    $breakEven,
                    $roiAnalysis
                ), 'laporan-keuangan.xlsx');
            } else {
                $pdf = PDF::loadView('admin.reports.financial.pdf', compact(
                    'startDate',
                    'endDate',
                    'reportType',
                    'incomeStatement',
                    'cashFlow',
                    'receivables',
                    'payables',
                    'taxReport',
                    'breakEven',
                    'roiAnalysis'
                ));
                return $pdf->download('laporan-keuangan.pdf');
            }
        }

        return view('admin.reports.financial.index', compact(
            'startDate',
            'endDate',
            'reportType',
            'incomeStatement',
            'cashFlow',
            'receivables',
            'payables',
            'taxReport',
            'breakEven',
            'roiAnalysis'
        ));
    }

    private function getIncomeStatement($startDate, $endDate)
    {
        // Pendapatan
        $revenue = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        // Harga Pokok Penjualan
        $cogs = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_cost');

        // Beban Operasional
        $operationalExpenses = [
            'Gaji Karyawan' => 0, // Implementasi sesuai kebutuhan
            'Sewa' => 0,
            'Utilitas' => 0,
            'Pemeliharaan' => 0,
            'Lain-lain' => 0
        ];

        // Beban Non-Operasional
        $nonOperationalExpenses = [
            'Bunga' => 0,
            'Pajak' => 0,
            'Lain-lain' => 0
        ];

        // Perhitungan laba rugi
        $grossProfit = $revenue - $cogs;
        $operationalExpensesTotal = array_sum($operationalExpenses);
        $nonOperationalExpensesTotal = array_sum($nonOperationalExpenses);
        $operatingProfit = $grossProfit - $operationalExpensesTotal;
        $netProfit = $operatingProfit - $nonOperationalExpensesTotal;

        return [
            'revenue' => $revenue,
            'cogs' => $cogs,
            'gross_profit' => $grossProfit,
            'operational_expenses' => $operationalExpenses,
            'operational_expenses_total' => $operationalExpensesTotal,
            'operating_profit' => $operatingProfit,
            'non_operational_expenses' => $nonOperationalExpenses,
            'non_operational_expenses_total' => $nonOperationalExpensesTotal,
            'net_profit' => $netProfit
        ];
    }

    private function getCashFlow($startDate, $endDate)
    {
        // Arus Kas dari Aktivitas Operasi
        $operatingActivities = [
            'Pendapatan dari Penjualan' => Order::where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total_amount'),
            'Pembayaran ke Supplier' => Purchase::where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total_amount'),
            'Pembayaran Gaji' => 0, // Implementasi sesuai kebutuhan
            'Pembayaran Utilitas' => 0,
            'Pembayaran Pajak' => 0
        ];

        // Arus Kas dari Aktivitas Investasi
        $investingActivities = [
            'Pembelian Aset Tetap' => 0,
            'Penjualan Aset Tetap' => 0
        ];

        // Arus Kas dari Aktivitas Pendanaan
        $financingActivities = [
            'Penerimaan Modal' => 0,
            'Pembayaran Dividen' => 0,
            'Pembayaran Hutang' => 0
        ];

        return [
            'operating_activities' => $operatingActivities,
            'investing_activities' => $investingActivities,
            'financing_activities' => $financingActivities,
            'net_cash_flow' => array_sum($operatingActivities) + 
                             array_sum($investingActivities) + 
                             array_sum($financingActivities)
        ];
    }

    private function getReceivables($startDate, $endDate)
    {
        return Order::with('customer')
            ->where('status', 'completed')
            ->where('payment_status', '!=', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy('customer_id')
            ->map(function ($orders) {
                return [
                    'customer' => $orders->first()->customer,
                    'total_amount' => $orders->sum('total_amount'),
                    'total_paid' => $orders->sum('total_paid'),
                    'total_due' => $orders->sum('total_amount') - $orders->sum('total_paid'),
                    'orders' => $orders
                ];
            });
    }

    private function getPayables($startDate, $endDate)
    {
        return Purchase::with('supplier')
            ->where('status', 'completed')
            ->where('payment_status', '!=', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy('supplier_id')
            ->map(function ($purchases) {
                return [
                    'supplier' => $purchases->first()->supplier,
                    'total_amount' => $purchases->sum('total_amount'),
                    'total_paid' => $purchases->sum('total_paid'),
                    'total_due' => $purchases->sum('total_amount') - $purchases->sum('total_paid'),
                    'purchases' => $purchases
                ];
            });
    }

    private function getTaxReport($startDate, $endDate)
    {
        // Pajak Penjualan (PPN)
        $salesTax = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('tax_amount');

        // Pajak Pembelian (PPN Masukan)
        $purchaseTax = Purchase::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('tax_amount');

        // Pajak Penghasilan
        $incomeTax = 0; // Implementasi sesuai kebutuhan

        return [
            'sales_tax' => $salesTax,
            'purchase_tax' => $purchaseTax,
            'income_tax' => $incomeTax,
            'tax_payable' => $salesTax - $purchaseTax + $incomeTax
        ];
    }

    private function getBreakEvenAnalysis($startDate, $endDate)
    {
        // Total Fixed Cost
        $fixedCosts = [
            'Gaji Karyawan' => 0, // Implementasi sesuai kebutuhan
            'Sewa' => 0,
            'Utilitas' => 0,
            'Pemeliharaan' => 0
        ];

        // Variable Cost per Unit
        $variableCosts = Product::with(['orderDetails' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->get()
            ->map(function ($product) {
                $totalQuantity = $product->orderDetails->sum('quantity');
                return [
                    'product' => $product,
                    'variable_cost' => $totalQuantity > 0 ? 
                        $product->orderDetails->sum('subtotal') / $totalQuantity : 0
                ];
            });

        // Selling Price per Unit
        $sellingPrices = Product::with(['orderDetails' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->get()
            ->map(function ($product) {
                $totalQuantity = $product->orderDetails->sum('quantity');
                return [
                    'product' => $product,
                    'selling_price' => $totalQuantity > 0 ? 
                        $product->orderDetails->sum('subtotal') / $totalQuantity : 0
                ];
            });

        // Break-Even Point Calculation
        $breakEvenPoints = $sellingPrices->map(function ($item) use ($variableCosts, $fixedCosts) {
            $variableCost = $variableCosts->firstWhere('product.id', $item['product']->id)['variable_cost'];
            $contributionMargin = $item['selling_price'] - $variableCost;
            $totalFixedCost = array_sum($fixedCosts);
            
            return [
                'product' => $item['product'],
                'break_even_units' => $contributionMargin > 0 ? 
                    $totalFixedCost / $contributionMargin : 0,
                'break_even_sales' => $contributionMargin > 0 ? 
                    $totalFixedCost / $contributionMargin * $item['selling_price'] : 0
            ];
        });

        return [
            'fixed_costs' => $fixedCosts,
            'variable_costs' => $variableCosts,
            'selling_prices' => $sellingPrices,
            'break_even_points' => $breakEvenPoints
        ];
    }

    private function getROIAnalysis($startDate, $endDate)
    {
        return Product::with(['orderDetails' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->get()
            ->map(function ($product) {
                $totalRevenue = $product->orderDetails->sum('subtotal');
                $totalCost = $product->orderDetails->sum(function ($detail) {
                    return $detail->quantity * $detail->purchase_price;
                });
                $investment = $product->orderDetails->sum(function ($detail) {
                    return $detail->quantity * $detail->purchase_price;
                });
                
                return [
                    'product' => $product,
                    'total_revenue' => $totalRevenue,
                    'total_cost' => $totalCost,
                    'investment' => $investment,
                    'profit' => $totalRevenue - $totalCost,
                    'roi' => $investment > 0 ? 
                        (($totalRevenue - $totalCost) / $investment) * 100 : 0
                ];
            });
    }
} 