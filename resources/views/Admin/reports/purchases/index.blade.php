@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Pembelian</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.reports.purchases.export.pdf', request()->query()) }}" class="btn btn-sm btn-danger">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                        <a href="{{ route('admin.reports.purchases.export.excel', request()->query()) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form action="{{ route('admin.reports.purchases.index') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Pemasok</label>
                                    <select name="supplier_id" class="form-control">
                                        <option value="">Semua Pemasok</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="">Semua Status</option>
                                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                        <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Diterima</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tanggal Mulai</label>
                                    <input type="date" name="date_start" class="form-control" value="{{ request('date_start') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tanggal Selesai</label>
                                    <input type="date" name="date_end" class="form-control" value="{{ request('date_end') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <a href="{{ route('admin.reports.purchases.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-sync"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-shopping-cart"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Pembelian</span>
                                    <span class="info-box-number">Rp {{ number_format($summary['total_purchases'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-file-invoice"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Jumlah Transaksi</span>
                                    <span class="info-box-number">{{ $summary['transaction_count'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-chart-line"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Rata-rata per Transaksi</span>
                                    <span class="info-box-number">Rp {{ number_format($summary['average_per_transaction'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Pembelian per Bulan</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="monthlyChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Pembelian per Pemasok</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="supplierChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Suppliers -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Top 5 Pemasok</h3>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Pemasok</th>
                                                <th>Jumlah Transaksi</th>
                                                <th>Total Pembelian</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($topSuppliers as $index => $supplier)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $supplier->name }}</td>
                                                <td>{{ $supplier->transaction_count }}</td>
                                                <td>Rp {{ number_format($supplier->total_amount, 0, ',', '.') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Purchase List -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>No. Pembelian</th>
                                    <th>Pemasok</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Dibuat Oleh</th>
                                    <th>Disetujui Oleh</th>
                                    <th>Ditolak Oleh</th>
                                    <th>Diterima Oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchases as $purchase)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $purchase->purchase_date->format('d/m/Y') }}</td>
                                    <td>{{ $purchase->invoice_number }}</td>
                                    <td>{{ $purchase->supplier->name }}</td>
                                    <td>
                                        @switch($purchase->status)
                                            @case('draft')
                                                <span class="badge badge-secondary">Draft</span>
                                                @break
                                            @case('pending')
                                                <span class="badge badge-warning">Menunggu Persetujuan</span>
                                                @break
                                            @case('approved')
                                                <span class="badge badge-success">Disetujui</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge badge-danger">Ditolak</span>
                                                @break
                                            @case('received')
                                                <span class="badge badge-info">Diterima</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                                    <td>{{ $purchase->createdBy->name }}</td>
                                    <td>{{ $purchase->approvedBy ? $purchase->approvedBy->name : '-' }}</td>
                                    <td>{{ $purchase->rejectedBy ? $purchase->rejectedBy->name : '-' }}</td>
                                    <td>{{ $purchase->receivedBy ? $purchase->receivedBy->name : '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $purchases->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Chart
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
const monthlyData = @json($monthlyData);
const monthlyLabels = monthlyData.map(item => {
    const date = new Date(item.year, item.month - 1);
    return date.toLocaleString('id-ID', { month: 'long', year: 'numeric' });
});
const monthlyValues = monthlyData.map(item => item.total);

new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: monthlyLabels,
        datasets: [{
            label: 'Total Pembelian',
            data: monthlyValues,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});

// Supplier Chart
const supplierCtx = document.getElementById('supplierChart').getContext('2d');
const supplierData = @json($supplierData);
const supplierLabels = supplierData.map(item => item.name);
const supplierValues = supplierData.map(item => item.total);

new Chart(supplierCtx, {
    type: 'bar',
    data: {
        labels: supplierLabels,
        datasets: [{
            label: 'Total Pembelian',
            data: supplierValues,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgb(54, 162, 235)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});
</script>
@endpush 