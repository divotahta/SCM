<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Pesanan</title>
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
        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
        }
        .status-completed { background-color: #d4edda; color: #155724; }
        .status-processing { background-color: #cce5ff; color: #004085; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Daftar Pesanan</h2>
        <p>Tanggal Cetak: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No. Faktur</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->invoice_number }}</td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $order->customer->name }}</td>
                <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                <td>
                    <span class="status status-{{ $order->status }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem</p>
        <p>SCM Omahkulos &copy; {{ date('Y') }}</p>
    </div>
</body>
</html> 