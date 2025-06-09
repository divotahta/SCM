<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            padding: 0;
        }
        .header p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .summary {
            margin-bottom: 20px;
        }
        .summary p {
            margin: 5px 0;
        }
        .top-suppliers {
            margin-bottom: 20px;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Pembelian</h2>
        <p>Periode: {{ request('date_start') ? date('d/m/Y', strtotime(request('date_start'))) : 'Semua' }} - 
           {{ request('date_end') ? date('d/m/Y', strtotime(request('date_end'))) : 'Semua' }}</p>
    </div>

    <div class="summary">
        <h3>Ringkasan</h3>
        <p>Total Pembelian: Rp {{ number_format($summary['total_purchases'], 0, ',', '.') }}</p>
        <p>Jumlah Transaksi: {{ $summary['transaction_count'] }}</p>
        <p>Rata-rata per Transaksi: Rp {{ number_format($summary['average_per_transaction'], 0, ',', '.') }}</p>
    </div>

    <div class="top-suppliers">
        <h3>Top 5 Pemasok</h3>
        <table>
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

    <h3>Detail Pembelian</h3>
    <table>
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
            @foreach($purchases as $purchase)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $purchase->purchase_date->format('d/m/Y') }}</td>
                <td>{{ $purchase->invoice_number }}</td>
                <td>{{ $purchase->supplier->name }}</td>
                <td>
                    @switch($purchase->status)
                        @case('draft')
                            Draft
                            @break
                        @case('pending')
                            Menunggu Persetujuan
                            @break
                        @case('approved')
                            Disetujui
                            @break
                        @case('rejected')
                            Ditolak
                            @break
                        @case('received')
                            Diterima
                            @break
                    @endswitch
                </td>
                <td>Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                <td>{{ $purchase->createdBy->name }}</td>
                <td>{{ $purchase->approvedBy ? $purchase->approvedBy->name : '-' }}</td>
                <td>{{ $purchase->rejectedBy ? $purchase->rejectedBy->name : '-' }}</td>
                <td>{{ $purchase->receivedBy ? $purchase->receivedBy->name : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
        <p>Oleh: {{ auth()->user()->name }}</p>
    </div>
</body>
</html> 