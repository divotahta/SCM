<?php

namespace App\Exports;

use App\Exports\CashFlowSheet;
use App\Exports\TaxReportSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FinancialReportExport implements WithMultipleSheets
{
    protected $incomeStatement;
    protected $cashFlow;
    protected $receivables;
    protected $payables;
    protected $taxReport;
    protected $breakEven;
    protected $roiAnalysis;

    public function __construct(
        $incomeStatement,
        $cashFlow,
        $receivables,
        $payables,
        $taxReport,
        $breakEven,
        $roiAnalysis
    ) {
        $this->incomeStatement = $incomeStatement;
        $this->cashFlow = $cashFlow;
        $this->receivables = $receivables;
        $this->payables = $payables;
        $this->taxReport = $taxReport;
        $this->breakEven = $breakEven;
        $this->roiAnalysis = $roiAnalysis;
    }

    public function sheets(): array
    {
        return [
            'Laba Rugi' => new IncomeStatementSheet($this->incomeStatement),
            'Arus Kas' => new CashFlowSheet($this->cashFlow),
            'Piutang' => new ReceivablesSheet($this->receivables),
            'Hutang' => new PayablesSheet($this->payables),
            'Pajak' => new TaxReportSheet($this->taxReport),
            'Break Even' => new BreakEvenSheet($this->breakEven),
            'ROI' => new ROISheet($this->roiAnalysis),
        ];
    }
} 