<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Struk #{{ $transaksi->kode_transaksi }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Struk Content -->
                    <div class="max-w-[80mm] mx-auto" id="struk-content">
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
                                <p class="text-sm">Kasir: {{ $transaksi->user->nama }}</p>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="mb-4">
                            <p class="text-sm">Pelanggan: {{ $transaksi->customer ? $transaksi->customer->nama : 'Umum' }}</p>
                            @if($transaksi->customer && $transaksi->customer->telepon)
                                <p class="text-sm">Telp: {{ $transaksi->customer->telepon }}</p>
                            @endif
                        </div>

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
                    </div>

                    <!-- Print Button -->
                    <div class="mt-4 text-center space-y-2">
                        <button onclick="printStruk()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 w-full">
                            Cetak Struk
                        </button>
                        <a href="/admin/pos" class="inline-block bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 w-full">
                            Kembali ke POS
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            @page {
                size: 80mm 297mm;
                margin: 0;
            }
            body > *:not(#struk-content) {
                display: none !important;
            }
            #struk-content {
                position: fixed;
                top: 0;
                left: 0;
                width: 80mm;
                margin: 0;
                padding: 0;
                background: white;
            }
            #struk-content * {
                visibility: visible !important;
            }
        }
    </style>

    <script>
        function printStruk() {
            const strukContent = document.getElementById('struk-content');
            const originalContent = document.body.innerHTML;
            
            document.body.innerHTML = strukContent.outerHTML;
            window.print();
            document.body.innerHTML = originalContent;
            
            // Reattach event listeners
            document.querySelector('button').onclick = printStruk;
        }
    </script>
</x-app-layout> 