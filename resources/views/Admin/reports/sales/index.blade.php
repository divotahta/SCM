@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Penjualan</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exportModal">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form action="{{ route('admin.reports.sales') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Periode</label>
                                    <select name="period" class="form-control">
                                        <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Harian</option>
                                        <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                                        <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                        <option value="yearly" {{ $period == 'yearly' ? 'selected' : '' }}>Tahunan</option>
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Produk</label>
                                    <select name="product_id" class="form-control select2">
                                        <option value="">Semua Produk</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select name="category_id" class="form-control select2">
                                        <option value="">Semua Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Pelanggan</label>
                                    <select name="customer_id" class="form-control select2">
                                        <option value="">Semua Pelanggan</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Summary Cards -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}</h3>
                                    <p>Total Penjualan</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="small-box-footer">
                                    Pertumbuhan: {{ number_format($comparison['growth']['sales'], 1) }}%
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>Rp {{ number_format($summary['total_profit'], 0, ',', '.') }}</h3>
                                    <p>Total Profit</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="small-box-footer">
                                    Pertumbuhan: {{ number_format($comparison['growth']['profit'], 1) }}%
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $summary['total_orders'] }}</h3>
                                    <p>Total Order</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-file-invoice"></i>
                                </div>
                                <div class="small-box-footer">
                                    Pertumbuhan: {{ number_format($comparison['growth']['orders'], 1) }}%
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ number_format($summary['profit_margin'], 1) }}%</h3>
                                    <p>Profit Margin</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-percentage"></i>
                                </div>
                                <div class="small-box-footer">
                                    Rata-rata Order: Rp {{ number_format($summary['average_order_value'], 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Tren Penjualan</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="salesTrendChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Top Kategori</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="topCategoriesChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Products & Customers -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Top 10 Produk</h3>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Kode</th>
                                                <th>Produk</th>
                                                <th>Qty</th>
                                                <th>Penjualan</th>
                                                <th>Profit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($topProducts as $product)
                                                <tr>
                                                    <td>{{ $product->code }}</td>
                                                    <td>{{ $product->name }}</td>
                                                    <td>{{ $product->total_quantity }}</td>
                                                    <td>Rp {{ number_format($product->total_sales, 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($product->total_profit, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Top 10 Pelanggan</h3>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Kode</th>
                                                <th>Pelanggan</th>
                                                <th>Order</th>
                                                <th>Penjualan</th>
                                                <th>Profit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($topCustomers as $customer)
                                                <tr>
                                                    <td>{{ $customer->code }}</td>
                                                    <td>{{ $customer->name }}</td>
                                                    <td>{{ $customer->total_orders }}</td>
                                                    <td>Rp {{ number_format($customer->total_sales, 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($customer->total_profit, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profit Analysis -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Analisis Profit per Kategori</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="profitAnalysisChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
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
                <form action="{{ route('admin.reports.sales') }}" method="GET">
                    <input type="hidden" name="export" value="excel">
                    <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                    <input type="hidden" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                    <input type="hidden" name="period" value="{{ $period }}">
                    <input type="hidden" name="product_id" value="{{ request('product_id') }}">
                    <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                    <input type="hidden" name="customer_id" value="{{ request('customer_id') }}">
                    
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2();

    // Sales Trend Chart
    const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
    new Chart(salesTrendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($salesTrend->pluck('date')) !!},
            datasets: [{
                label: 'Penjualan',
                data: {!! json_encode($salesTrend->pluck('total_sales')) !!},
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }, {
                label: 'Profit',
                data: {!! json_encode($salesTrend->pluck('total_profit')) !!},
                borderColor: 'rgb(255, 99, 132)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Top Categories Chart
    const topCategoriesCtx = document.getElementById('topCategoriesChart').getContext('2d');
    new Chart(topCategoriesCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($topCategories->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($topCategories->pluck('total_sales')) !!},
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)'
                ]
            }]
        },
        options: {
            responsive: true
        }
    });

    // Profit Analysis Chart
    const profitAnalysisCtx = document.getElementById('profitAnalysisChart').getContext('2d');
    new Chart(profitAnalysisCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($profitAnalysis->pluck('name')) !!},
            datasets: [{
                label: 'Penjualan',
                data: {!! json_encode($profitAnalysis->pluck('total_sales')) !!},
                backgroundColor: 'rgb(75, 192, 192)'
            }, {
                label: 'Profit',
                data: {!! json_encode($profitAnalysis->pluck('total_profit')) !!},
                backgroundColor: 'rgb(255, 99, 132)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush 