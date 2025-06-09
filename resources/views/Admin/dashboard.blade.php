<x-app-layout>
    <x-slot name="header">
        Admin Dashboard
    </x-slot>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Pesanan -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                    <i class="fas fa-shopping-cart text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Pesanan</p>
                    <div class="flex items-baseline">
                        <p class="text-2xl font-semibold text-gray-700">24</p>
                        <p class="ml-2 text-sm text-gray-500">hari ini</p>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">128 pesanan bulan ini</p>
                </div>
            </div>
        </div>

        <!-- Total Penjualan -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Penjualan</p>
                    <div class="flex items-baseline">
                        <p class="text-2xl font-semibold text-gray-700">Rp 2.5M</p>
                        <p class="ml-2 text-sm text-gray-500">hari ini</p>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Rp 15.8M bulan ini</p>
                </div>
            </div>
        </div>

        <!-- Total Pembelian -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                    <i class="fas fa-shopping-basket text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Pembelian</p>
                    <div class="flex items-baseline">
                        <p class="text-2xl font-semibold text-gray-700">Rp 1.2M</p>
                        <p class="ml-2 text-sm text-gray-500">bulan ini</p>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">45 transaksi</p>
                </div>
            </div>
        </div>

        <!-- Stok Menipis -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-500">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Stok Menipis</p>
                    <div class="flex items-baseline">
                        <p class="text-2xl font-semibold text-gray-700">8</p>
                        <p class="ml-2 text-sm text-gray-500">produk</p>
                    </div>
                    <p class="text-sm text-red-500 mt-1">Perlu restock</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart dan Tabel -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Chart Penjualan -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Penjualan Bulanan</h3>
            <canvas id="salesChart" height="300"></canvas>
        </div>

        <!-- Produk Terlaris -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Produk Terlaris</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Terjual</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700">Produk A</td>
                            <td class="px-4 py-3 text-sm text-gray-700">150</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Rp 15.000.000</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700">Produk B</td>
                            <td class="px-4 py-3 text-sm text-gray-700">120</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Rp 12.000.000</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700">Produk C</td>
                            <td class="px-4 py-3 text-sm text-gray-700">100</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Rp 10.000.000</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700">Produk D</td>
                            <td class="px-4 py-3 text-sm text-gray-700">80</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Rp 8.000.000</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700">Produk E</td>
                            <td class="px-4 py-3 text-sm text-gray-700">75</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Rp 7.500.000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pesanan Terbaru -->
    <div class="mt-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Pesanan Terbaru</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700">#ORD001</td>
                            <td class="px-4 py-3 text-sm text-gray-700">John Doe</td>
                            <td class="px-4 py-3 text-sm text-gray-700">2024-03-21</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Rp 1.500.000</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Selesai
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700">#ORD002</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Jane Smith</td>
                            <td class="px-4 py-3 text-sm text-gray-700">2024-03-21</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Rp 2.000.000</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Proses
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700">#ORD003</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Mike Johnson</td>
                            <td class="px-4 py-3 text-sm text-gray-700">2024-03-20</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Rp 3.500.000</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Pengiriman
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700">#ORD004</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Sarah Wilson</td>
                            <td class="px-4 py-3 text-sm text-gray-700">2024-03-20</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Rp 1.800.000</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Selesai
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700">#ORD005</td>
                            <td class="px-4 py-3 text-sm text-gray-700">David Brown</td>
                            <td class="px-4 py-3 text-sm text-gray-700">2024-03-19</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Rp 2.500.000</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Batal
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data untuk chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Penjualan',
                    data: [12, 19, 15, 17, 22, 25, 28, 24, 20, 18, 16, 14],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
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
                        grid: {
                            display: true,
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout> 