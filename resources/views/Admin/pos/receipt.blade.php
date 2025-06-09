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
                size: 80mm 297mm;
                margin: 0;
            }
            body {
                width: 80mm;
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-white p-4 max-w-[80mm] mx-auto">
    <!-- Header -->
    <div class="text-center mb-4">
        <h1 class="text-xl font-bold">OMAH KULOS</h1>
        <p class="text-sm">Jl. Contoh No. 123</p>
        <p class="text-sm">Telp: (123) 456-7890</p>
    </div>

    <!-- Transaction Info -->
    <div class="border-t border-b border-dashed border-gray-400 py-2 mb-4">
        <div class="text-center">
            <p class="text-sm font-bold">{{ $transaksi->kode_transaksi }}</p>
            <p class="text-sm">{{ $tanggal }}</p>
            <p class="text-sm">Kasir: {{ $kasir }}</p>
        </div>
    </div>

    <!-- Customer Info -->
    @if($transaksi->customer)
    <div class="mb-4">
        <p class="text-sm">Pelanggan: {{ $transaksi->customer->nama }}</p>
        <p class="text-sm">Telp: {{ $transaksi->customer->telepon }}</p>
    </div>
    @endif

    <!-- Items -->
    <div class="mb-4">
        <div class="text-sm">
            <div class="grid grid-cols-12 gap-1 mb-1 font-bold">
                <div class="col-span-6">Item</div>
                <div class="col-span-2 text-right">Qty</div>
                <div class="col-span-4 text-right">Total</div>
            </div>
            @foreach($items as $item)
            <div class="grid grid-cols-12 gap-1 mb-1">
                <div class="col-span-6">
                    <div class="font-medium">{{ $item['nama'] }}</div>
                    <div class="text-xs text-gray-500">@ {{ number_format($item['harga'], 0, ',', '.') }}</div>
                </div>
                <div class="col-span-2 text-right">{{ $item['jumlah'] }}</div>
                <div class="col-span-4 text-right">{{ number_format($item['subtotal'], 0, ',', '.') }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Totals -->
    <div class="border-t border-b border-dashed border-gray-400 py-2 mb-4">
        <div class="text-sm">
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

    <!-- Payment Info -->
    <div class="mb-4">
        <div class="text-sm">
            <div class="flex justify-between mb-1">
                <span>Metode Pembayaran</span>
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

    <!-- Footer -->
    <div class="text-center text-sm">
        <p class="mb-1">Terima kasih atas kunjungan Anda</p>
        <p class="mb-1">Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</p>
        <p class="font-bold">www.omahkulos.com</p>
    </div>

    <!-- Print Button -->
    <div class="no-print mt-4 text-center">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Cetak Struk
        </button>
    </div>
</body>
</html> 