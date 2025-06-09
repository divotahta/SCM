<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Daftar Transaksi</h1>
        <p>Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Kode Transaksi</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th class="text-right">Total Harga</th>
                <th class="text-right">Total Bayar</th>
                <th class="text-right">Kembalian</th>
                <th>Metode Pembayaran</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->kode_transaksi }}</td>
                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $transaction->customer->nama}}</td>
                    <td class="text-right">Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($transaction->total_kembali, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($transaction->metode_pembayaran) }}</td>
                    <td>{{ ucfirst($transaction->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 