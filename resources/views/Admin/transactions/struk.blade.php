<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $transaksi->kode_transaksi }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page {
                size: 58mm 297mm;
                margin: 0;
            }
            body {
                width: 58mm;
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 4px 0;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
        .font-bold {
            font-weight: bold;
        }
        .mb-1 {
            margin-bottom: 0.15rem;
        }
        .mb-2 {
            margin-bottom: 0.3rem;
        }
        .mb-4 {
            margin-bottom: 0.5rem;
        }
        .mt-4 {
            margin-top: 0.5rem;
        }
        .p-2 {
            padding: 0.5rem;
        }
        .text-sm {
            font-size: 0.75rem;
        }
        .text-xs {
            font-size: 0.65rem;
        }
        .text-xl {
            font-size: 1rem;
        }
        .grid {
            display: grid;
        }
        .grid-cols-12 {
            grid-template-columns: repeat(12, minmax(0, 1fr));
        }
        .col-span-6 {
            grid-column: span 6 / span 6;
        }
        .col-span-2 {
            grid-column: span 2 / span 2;
        }
        .col-span-4 {
            grid-column: span 4 / span 4;
        }
        .gap-1 {
            gap: 0.15rem;
        }
        .flex {
            display: flex;
        }
        .justify-between {
            justify-content: space-between;
        }
        .bg-white {
            background-color: white;
        }
        .text-gray-500 {
            color: #6b7280;
        }
        .bg-blue-600 {
            background-color: #2563eb;
        }
        .text-white {
            color: white;
        }
        .rounded-lg {
            border-radius: 0.5rem;
        }
        .hover\:bg-blue-700:hover {
            background-color: #1d4ed8;
        }
        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        .py-2 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
    </style>
</head>
<body class="bg-white p-2 max-w-[58mm] mx-auto">
    <!-- Header -->
    <div class="text-center mb-4">
        <h1 class="text-xl font-bold mb-1">OMAH KULOS</h1>
        <p class="text-xs mb-1">Jl. Contoh No. 123</p>
        <p class="text-xs">Telp: (123) 456-7890</p>
    </div>

    <div class="divider"></div>

    <!-- Transaction Info -->
    <div class="text-center mb-4">
        <p class="text-sm font-bold mb-1">{{ $transaksi->kode_transaksi }}</p>
        <p class="text-xs mb-1">{{ $tanggal }}</p>
        <p class="text-xs">Kasir: {{ $kasir }}</p>
    </div>

    <div class="divider"></div>

    <!-- Customer Info -->
    @if($transaksi->customer)
    <div class="mb-4">
        <p class="text-xs font-bold mb-1">INFORMASI PELANGGAN</p>
        <p class="text-xs mb-1">Nama: {{ $transaksi->customer->nama }}</p>
        <p class="text-xs">Telp: {{ $transaksi->customer->telepon }}</p>
    </div>
    @endif

    <div class="divider"></div>

    <!-- Items -->
    <div class="mb-4">
        <p class="text-xs font-bold mb-2">DETAIL PEMBELIAN</p>
        <div class="text-xs">
            <div class="grid grid-cols-12 gap-1 mb-1 font-bold">
                <div class="col-span-6 text-left">Item</div>
                <div class="col-span-2 text-right">Qty</div>
                <div class="col-span-4 text-right">Total</div>
            </div>
            @foreach($items as $item)
            <div class="grid grid-cols-12 gap-1 mb-1">
                <div class="col-span-6 text-left">
                    <div class="font-bold">{{ $item['nama'] }}</div>
                    <div class="text-xs text-gray-500">@ {{ number_format($item['harga'], 0, ',', '.') }}</div>
                </div>
                <div class="col-span-2 text-right">{{ $item['jumlah'] }}</div>
                <div class="col-span-4 text-right">{{ number_format($item['subtotal'], 0, ',', '.') }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="divider"></div>

    <!-- Totals -->
    <div class="mb-4">
        <p class="text-xs font-bold mb-2">RINCIAN BIAYA</p>
        <div class="text-xs">
            <div class="flex justify-between mb-1">
                <span>Subtotal</span>
                <span>{{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between mb-1">
                <span>PPN (11%)</span>
                <span>{{ number_format($ppn, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between font-bold">
                <span>Total</span>
                <span>{{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    <!-- Payment Info -->
    <div class="mb-4">
        <p class="text-xs font-bold mb-2">INFORMASI PEMBAYARAN</p>
        <div class="text-xs">
            <div class="flex justify-between mb-1">
                <span>Metode</span>
                <span>{{ $metode }}</span>
            </div>
            <div class="flex justify-between mb-1">
                <span>Bayar</span>
                <span>{{ number_format($bayar, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between font-bold">
                <span>Kembali</span>
                <span>{{ number_format($kembali, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    <!-- Footer -->
    <div class="text-center text-xs">
        <p class="mb-1 font-bold">TERIMA KASIH</p>
        <p class="mb-1">Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</p>
        <p class="mb-1">www.omahkulos.com</p>
    </div>

    <!-- Print Button -->
    <div class="no-print mt-4 text-center">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Cetak Struk
        </button>
    </div>
</body>
</html> 