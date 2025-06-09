<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Perubahan Stok</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
        }
        th {
            background-color: #f0f0f0;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Perubahan Stok</h2>
        <p>Periode: {{ request('date_from') }} s/d {{ request('date_to') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Produk</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Referensi</th>
                <th>Keterangan</th>
                <th>Dibuat Oleh</th>
            </tr>
        </thead>
        <tbody>
            @foreach($histories as $history)
                <tr>
                    <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $history->produk->nama_produk }}</td>
                    <td>
                        @if($history->type == 'in')
                            Masuk
                        @elseif($history->type == 'out')
                            Keluar
                        @else
                            Penyesuaian
                        @endif
                    </td>
                    <td>{{ $history->quantity }} {{ $history->produk->unit->nama }}</td>
                    <td>
                        @if($history->reference_type == 'purchase')
                            Pembelian #{{ $history->reference_id }}
                        @elseif($history->reference_type == 'sale')
                            Penjualan #{{ $history->reference_id }}
                        @else
                            Penyesuaian Manual
                        @endif
                    </td>
                    <td>{{ $history->notes }}</td>
                    <td>{{ $history->createdBy->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Dicetak oleh: {{ auth()->user()->name }}</p>
    </div>
</body>
</html> 