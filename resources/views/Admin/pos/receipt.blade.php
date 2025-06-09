<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Transaksi</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 300px;
            margin: 0 auto;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        .item {
            margin: 5px 0;
        }
        .total {
            font-weight: bold;
            margin-top: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
        }
        @media print {
            body {
                width: 100%;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>SCM Omahkulos</h2>
        <p>Jl. Contoh No. 123</p>
        <p>Telp: (123) 456-7890</p>
    </div>

    <div class="divider"></div>

    <div>
        <p>No: {{ $transaction->id }}</p>
        <p>Tanggal: {{ $transaction->created_at->format('d/m/Y H:i:s') }}</p>
        <p>Customer: {{ $transaction->customer->name }}</p>
    </div>

    <div class="divider"></div>

    <div>
        @foreach($transaction->details as $detail)
        <div class="item">
            <p>{{ $detail->product->name }}</p>
            <p>{{ $detail->quantity }} x {{ number_format($detail->price, 0, ',', '.') }} = {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
        </div>
        @endforeach
    </div>

    <div class="divider"></div>

    <div class="total">
        <p>Total: Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>
        <p>Bayar: Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</p>
        <p>Kembali: Rp {{ number_format($transaction->payment_amount - $transaction->total, 0, ',', '.') }}</p>
    </div>

    <div class="footer">
        <p>Terima kasih atas kunjungan Anda</p>
        <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</p>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()">Cetak Struk</button>
    </div>
</body>
</html> 