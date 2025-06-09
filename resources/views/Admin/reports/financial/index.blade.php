@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Keuangan</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exportModal">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form action="{{ route('admin.reports.financial') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jenis Laporan</label>
                                    <select name="type" class="form-control">
                                        <option value="income_statement" {{ $reportType == 'income_statement' ? 'selected' : '' }}>
                                            Laporan Laba Rugi
                                        </option>
                                        <option value="cash_flow" {{ $reportType == 'cash_flow' ? 'selected' : '' }}>
                                            Laporan Arus Kas
                                        </option>
                                        <option value="receivables" {{ $reportType == 'receivables' ? 'selected' : '' }}>
                                            Laporan Piutang
                                        </option>
                                        <option value="payables" {{ $reportType == 'payables' ? 'selected' : '' }}>
                                            Laporan Hutang
                                        </option>
                                        <option value="tax" {{ $reportType == 'tax' ? 'selected' : '' }}>
                                            Laporan Pajak
                                        </option>
                                        <option value="break_even" {{ $reportType == 'break_even' ? 'selected' : '' }}>
                                            Analisis Break-Even
                                        </option>
                                        <option value="roi" {{ $reportType == 'roi' ? 'selected' : '' }}>
                                            Analisis ROI
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tanggal Mulai</label>
                                    <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tanggal Selesai</label>
                                    <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Laporan Laba Rugi -->
                    @if($reportType == 'income_statement')
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Laporan Laba Rugi</h3>
                                        <p class="text-muted">Periode: {{ $startDate->format('d/m/Y') }} s/d {{ $endDate->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2">Pendapatan</th>
                                                <td class="text-right">Rp {{ number_format($incomeStatement['revenue'], 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Harga Pokok Penjualan</th>
                                                <td class="text-right">Rp {{ number_format($incomeStatement['cogs'], 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Laba Kotor</th>
                                                <td class="text-right">Rp {{ number_format($incomeStatement['gross_profit'], 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th rowspan="{{ count($incomeStatement['operational_expenses']) + 1 }}">Beban Operasional</th>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            @foreach($incomeStatement['operational_expenses'] as $expense => $amount)
                                                <tr>
                                                    <td>{{ $expense }}</td>
                                                    <td class="text-right">Rp {{ number_format($amount, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <th colspan="2">Total Beban Operasional</th>
                                                <td class="text-right">Rp {{ number_format($incomeStatement['operational_expenses_total'], 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Laba Operasional</th>
                                                <td class="text-right">Rp {{ number_format($incomeStatement['operating_profit'], 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th rowspan="{{ count($incomeStatement['non_operational_expenses']) + 1 }}">Beban Non-Operasional</th>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            @foreach($incomeStatement['non_operational_expenses'] as $expense => $amount)
                                                <tr>
                                                    <td>{{ $expense }}</td>
                                                    <td class="text-right">Rp {{ number_format($amount, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <th colspan="2">Total Beban Non-Operasional</th>
                                                <td class="text-right">Rp {{ number_format($incomeStatement['non_operational_expenses_total'], 0, ',', '.') }}</td>
                                            </tr>
                                            <tr class="font-weight-bold">
                                                <th colspan="2">Laba Bersih</th>
                                                <td class="text-right">Rp {{ number_format($incomeStatement['net_profit'], 0, ',', '.') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Laporan Arus Kas -->
                    @if($reportType == 'cash_flow')
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Laporan Arus Kas</h3>
                                        <p class="text-muted">Periode: {{ $startDate->format('d/m/Y') }} s/d {{ $endDate->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <tr class="font-weight-bold">
                                                <th colspan="2">Arus Kas dari Aktivitas Operasi</th>
                                                <td></td>
                                            </tr>
                                            @foreach($cashFlow['operating_activities'] as $activity => $amount)
                                                <tr>
                                                    <td colspan="2">{{ $activity }}</td>
                                                    <td class="text-right">Rp {{ number_format($amount, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                            <tr class="font-weight-bold">
                                                <th colspan="2">Arus Kas dari Aktivitas Investasi</th>
                                                <td></td>
                                            </tr>
                                            @foreach($cashFlow['investing_activities'] as $activity => $amount)
                                                <tr>
                                                    <td colspan="2">{{ $activity }}</td>
                                                    <td class="text-right">Rp {{ number_format($amount, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                            <tr class="font-weight-bold">
                                                <th colspan="2">Arus Kas dari Aktivitas Pendanaan</th>
                                                <td></td>
                                            </tr>
                                            @foreach($cashFlow['financing_activities'] as $activity => $amount)
                                                <tr>
                                                    <td colspan="2">{{ $activity }}</td>
                                                    <td class="text-right">Rp {{ number_format($amount, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                            <tr class="font-weight-bold">
                                                <th colspan="2">Kenaikan/Penurunan Kas</th>
                                                <td class="text-right">Rp {{ number_format($cashFlow['net_cash_flow'], 0, ',', '.') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Laporan Piutang -->
                    @if($reportType == 'receivables')
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Laporan Piutang</h3>
                                        <p class="text-muted">Periode: {{ $startDate->format('d/m/Y') }} s/d {{ $endDate->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Kode</th>
                                                    <th>Pelanggan</th>
                                                    <th>Total Tagihan</th>
                                                    <th>Total Dibayar</th>
                                                    <th>Sisa Piutang</th>
                                                    <th>Jatuh Tempo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($receivables as $receivable)
                                                    <tr>
                                                        <td>{{ $receivable['customer']->code }}</td>
                                                        <td>{{ $receivable['customer']->name }}</td>
                                                        <td class="text-right">Rp {{ number_format($receivable['total_amount'], 0, ',', '.') }}</td>
                                                        <td class="text-right">Rp {{ number_format($receivable['total_paid'], 0, ',', '.') }}</td>
                                                        <td class="text-right">Rp {{ number_format($receivable['total_due'], 0, ',', '.') }}</td>
                                                        <td>{{ $receivable['orders']->max('due_date')?->format('d/m/Y') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Laporan Hutang -->
                    @if($reportType == 'payables')
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Laporan Hutang</h3>
                                        <p class="text-muted">Periode: {{ $startDate->format('d/m/Y') }} s/d {{ $endDate->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Kode</th>
                                                    <th>Supplier</th>
                                                    <th>Total Tagihan</th>
                                                    <th>Total Dibayar</th>
                                                    <th>Sisa Hutang</th>
                                                    <th>Jatuh Tempo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($payables as $payable)
                                                    <tr>
                                                        <td>{{ $payable['supplier']->code }}</td>
                                                        <td>{{ $payable['supplier']->name }}</td>
                                                        <td class="text-right">Rp {{ number_format($payable['total_amount'], 0, ',', '.') }}</td>
                                                        <td class="text-right">Rp {{ number_format($payable['total_paid'], 0, ',', '.') }}</td>
                                                        <td class="text-right">Rp {{ number_format($payable['total_due'], 0, ',', '.') }}</td>
                                                        <td>{{ $payable['purchases']->max('due_date')?->format('d/m/Y') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Laporan Pajak -->
                    @if($reportType == 'tax')
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Laporan Pajak</h3>
                                        <p class="text-muted">Periode: {{ $startDate->format('d/m/Y') }} s/d {{ $endDate->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>PPN Keluaran</th>
                                                <td class="text-right">Rp {{ number_format($taxReport['sales_tax'], 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>PPN Masukan</th>
                                                <td class="text-right">Rp {{ number_format($taxReport['purchase_tax'], 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Pajak Penghasilan</th>
                                                <td class="text-right">Rp {{ number_format($taxReport['income_tax'], 0, ',', '.') }}</td>
                                            </tr>
                                            <tr class="font-weight-bold">
                                                <th>Pajak yang Harus Dibayar</th>
                                                <td class="text-right">Rp {{ number_format($taxReport['tax_payable'], 0, ',', '.') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Analisis Break-Even -->
                    @if($reportType == 'break_even')
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Analisis Break-Even</h3>
                                        <p class="text-muted">Periode: {{ $startDate->format('d/m/Y') }} s/d {{ $endDate->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="card-body">
                                        <h4>Biaya Tetap</h4>
                                        <table class="table table-bordered mb-4">
                                            <thead>
                                                <tr>
                                                    <th>Jenis Biaya</th>
                                                    <th>Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($breakEven['fixed_costs'] as $cost => $amount)
                                                    <tr>
                                                        <td>{{ $cost }}</td>
                                                        <td class="text-right">Rp {{ number_format($amount, 0, ',', '.') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <h4>Analisis Break-Even per Produk</h4>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Produk</th>
                                                    <th>Harga Jual</th>
                                                    <th>Biaya Variabel</th>
                                                    <th>Margin Kontribusi</th>
                                                    <th>BEP (Unit)</th>
                                                    <th>BEP (Rupiah)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($breakEven['break_even_points'] as $point)
                                                    <tr>
                                                        <td>{{ $point['product']->name }}</td>
                                                        <td class="text-right">Rp {{ number_format($point['product']->selling_price, 0, ',', '.') }}</td>
                                                        <td class="text-right">Rp {{ number_format($point['product']->variable_cost, 0, ',', '.') }}</td>
                                                        <td class="text-right">Rp {{ number_format($point['product']->selling_price - $point['product']->variable_cost, 0, ',', '.') }}</td>
                                                        <td class="text-right">{{ number_format($point['break_even_units'], 0, ',', '.') }}</td>
                                                        <td class="text-right">Rp {{ number_format($point['break_even_sales'], 0, ',', '.') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Analisis ROI -->
                    @if($reportType == 'roi')
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Analisis Return on Investment (ROI)</h3>
                                        <p class="text-muted">Periode: {{ $startDate->format('d/m/Y') }} s/d {{ $endDate->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Produk</th>
                                                    <th>Total Pendapatan</th>
                                                    <th>Total Biaya</th>
                                                    <th>Investasi</th>
                                                    <th>Profit</th>
                                                    <th>ROI</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($roiAnalysis as $roi)
                                                    <tr>
                                                        <td>{{ $roi['product']->name }}</td>
                                                        <td class="text-right">Rp {{ number_format($roi['total_revenue'], 0, ',', '.') }}</td>
                                                        <td class="text-right">Rp {{ number_format($roi['total_cost'], 0, ',', '.') }}</td>
                                                        <td class="text-right">Rp {{ number_format($roi['investment'], 0, ',', '.') }}</td>
                                                        <td class="text-right">Rp {{ number_format($roi['profit'], 0, ',', '.') }}</td>
                                                        <td class="text-right">{{ number_format($roi['roi'], 2) }}%</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Laporan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.reports.financial') }}" method="GET">
                    <input type="hidden" name="type" value="{{ $reportType }}">
                    <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                    <input type="hidden" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                    
                    <div class="form-group">
                        <label>Format Export</label>
                        <select name="export" class="form-control">
                            <option value="excel">Excel</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Download</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2();
});
</script>
@endpush 