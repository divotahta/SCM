<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Owner') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Notifikasi Pembelian -->
            @if($pendingPurchases->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-yellow-400 text-2xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">
                                Ada {{ $pendingPurchases->count() }} pembelian yang menunggu persetujuan
                            </h3>
                            <div class="mt-2">
                                <a href="{{ route('owner.purchases.index', ['status' => 'pending']) }}" 
                                    class="text-sm text-yellow-700 hover:text-yellow-600">
                                    Lihat pembelian yang menunggu persetujuan
                                    <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Ringkasan Keuangan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                                <i class="fas fa-money-bill-wave text-green-600 text-2xl"></i>
                            </div>
                            <div class="ml-5">
                                <h3 class="text-lg font-medium text-gray-900">Total Pendapatan</h3>
                                <p class="mt-1 text-2xl font-semibold text-gray-900">
                                    Rp {{ number_format($totalIncome, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                                <i class="fas fa-shopping-cart text-red-600 text-2xl"></i>
                            </div>
                            <div class="ml-5">
                                <h3 class="text-lg font-medium text-gray-900">Total Pengeluaran</h3>
                                <p class="mt-1 text-2xl font-semibold text-gray-900">
                                    Rp {{ number_format($totalExpense, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
                            </div>
                            <div class="ml-5">
                                <h3 class="text-lg font-medium text-gray-900">Laba Bersih</h3>
                                <p class="mt-1 text-2xl font-semibold text-gray-900">
                                    Rp {{ number_format($totalProfit, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik Penjualan vs Pembelian -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Perbandingan Penjualan vs Pembelian</h3>
                    <canvas id="salesVsPurchaseChart" height="100"></canvas>
                </div>
            </div>

            <!-- Produk Terlaris -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Produk Terlaris</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terjual</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($topProducts as $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $product->total_sold }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pelanggan Teratas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pelanggan Teratas</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Transaksi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pembelian</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($topCustomers as $customer)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $customer->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $customer->total_transactions }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data untuk grafik
        const salesData = @json($salesData);
        const purchaseData = @json($purchaseData);
        const labels = @json($chartLabels);

        // Inisialisasi grafik
        const ctx = document.getElementById('salesVsPurchaseChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Penjualan',
                        data: salesData,
                        borderColor: 'rgb(34, 197, 94)',
                        tension: 0.1
                    },
                    {
                        label: 'Pembelian',
                        data: purchaseData,
                        borderColor: 'rgb(239, 68, 68)',
                        tension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
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
    @endpush
</x-app-layout> 