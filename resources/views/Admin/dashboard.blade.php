<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Penjualan -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg transform transition duration-500 hover:scale-105">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-700">Total Penjualan</h3>
                                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Pembelian -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg transform transition duration-500 hover:scale-105">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-700">Total Pembelian</h3>
                                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Produk -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg transform transition duration-500 hover:scale-105">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-700">Total Produk</h3>
                                <p class="text-2xl font-bold text-gray-900">{{ $totalProduk }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Pelanggan -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg transform transition duration-500 hover:scale-105">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-700">Total Pelanggan</h3>
                                <p class="text-2xl font-bold text-gray-900">{{ $totalPelanggan }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik dan Tabel -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Grafik Penjualan -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Grafik Penjualan Bulanan</h3>
                        <canvas id="salesChart" class="w-full h-64"></canvas>
                    </div>
                </div>

                <!-- Grafik Pembelian -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Grafik Pembelian Bulanan</h3>
                        <canvas id="purchaseChart" class="w-full h-64"></canvas>
                    </div>
                </div>
            </div>

            <!-- Tabel dan List -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Produk Stok Menipis -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-700">Produk Stok Menipis</h3>
                            <a href="{{ route('admin.products.index') }}" class="text-sm text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                Lihat Semua
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($produkStokMenipis as $produk)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200 cursor-pointer" onclick="window.location='{{ route('admin.products.show', $produk->id) }}'">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $produk->nama_produk }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">{{ $produk->stok }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Produk Terlaris -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-700">Produk Terlaris</h3>
                            <a href="{{ route('admin.products.index') }}" class="text-sm text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                Lihat Semua
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Terjual</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($produkTerlaris as $produk)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200 cursor-pointer" onclick="window.location='{{ route('admin.products.show', $produk->id) }}'">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $produk->nama_produk }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $produk->total_terjual }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaksi dan Pembelian Terbaru -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
                <!-- Transaksi Terbaru -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-700">Transaksi Terbaru</h3>
                            <a href="{{ route('admin.transactions.index') }}" class="text-sm text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                Lihat Semua
                            </a>
                        </div>
                        <div class="space-y-4">
                            @foreach($transaksiTerbaru as $transaksi)
                            <div class="border-l-4 border-green-500 bg-green-50 p-4 rounded-r-lg transform transition duration-300 hover:scale-105 cursor-pointer" onclick="window.location='{{ route('admin.transactions.show', $transaksi->id) }}'">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $transaksi->customer->nama }}</p>
                                        <p class="text-sm text-gray-500">{{ $transaksi->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                    <p class="text-sm font-semibold text-green-600">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Pembelian Terbaru -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-700">Pembelian Terbaru</h3>
                            <a href="{{ route('admin.purchases.index') }}" class="text-sm text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                Lihat Semua
                            </a>
                        </div>
                        <div class="space-y-4">
                            @foreach($pembelianTerbaru as $pembelian)
                            <div class="border-l-4 border-blue-500 bg-blue-50 p-4 rounded-r-lg transform transition duration-300 hover:scale-105 cursor-pointer" onclick="window.location='{{ route('admin.purchases.show', $pembelian->id) }}'">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $pembelian->supplier->nama }}</p>
                                        <p class="text-sm text-gray-500">{{ $pembelian->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                    <p class="text-sm font-semibold text-blue-600">Rp {{ number_format($pembelian->total_amount, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data untuk grafik
        const salesData = @json($penjualanBulanan);
        const purchaseData = @json($pembelianBulanan);

        // Konfigurasi grafik penjualan
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: salesData.map(item => {
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                    return months[item.bulan - 1];
                }),
                datasets: [{
                    label: 'Penjualan',
                    data: salesData.map(item => item.total),
                    borderColor: 'rgb(34, 197, 94)',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(34, 197, 94, 0.1)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
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

        // Konfigurasi grafik pembelian
        const purchaseCtx = document.getElementById('purchaseChart').getContext('2d');
        new Chart(purchaseCtx, {
            type: 'line',
            data: {
                labels: purchaseData.map(item => {
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                    return months[item.bulan - 1];
                }),
                datasets: [{
                    label: 'Pembelian',
                    data: purchaseData.map(item => item.total),
                    borderColor: 'rgb(59, 130, 246)',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(59, 130, 246, 0.1)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
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
</x-app-layout> 