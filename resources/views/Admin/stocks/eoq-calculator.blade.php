<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kalkulator EOQ') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <a href="{{ route('admin.stocks.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mb-4">
                    Kembali
                </a>

                <!-- Form Kalkulator -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <form id="eoqForm" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="product_name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                                <input type="text" name="product_name" id="product_name" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="product_unit" class="block text-sm font-medium text-gray-700">Satuan</label>
                                <input type="text" name="product_unit" id="product_unit" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Contoh: pcs, kg, unit">
                            </div>

                            <div>
                                <label for="purchase_price" class="block text-sm font-medium text-gray-700">Harga Beli per Unit (Rp)</label>
                                <input type="number" name="purchase_price" id="purchase_price" required min="0" step="1000"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="period" class="block text-sm font-medium text-gray-700">Periode Permintaan</label>
                                <select name="period" id="period" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="weekly">Mingguan</option>
                                    <option value="monthly">Bulanan</option>
                                    <option value="yearly">Tahunan</option>
                                </select>
                            </div>

                            <div>
                                <label for="demand" class="block text-sm font-medium text-gray-700">Jumlah Permintaan</label>
                                <input type="number" name="demand" id="demand" required min="0" step="0.01"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="order_cost" class="block text-sm font-medium text-gray-700">Biaya Pemesanan (Rp)</label>
                                <input type="number" name="order_cost" id="order_cost" required min="0" step="1000"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="holding_cost_percentage" class="block text-sm font-medium text-gray-700">Persentase Biaya Penyimpanan (%)</label>
                                <input type="number" name="holding_cost_percentage" id="holding_cost_percentage" required min="0" max="100" step="0.01"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Hitung EOQ
                            </button>
                        </div>
                    </form>

                    <!-- Hasil Perhitungan -->
                    <div id="result" class="mt-8 hidden">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Hasil Perhitungan EOQ</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Produk</p>
                                    <p class="text-base font-medium" id="result-product"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Economic Order Quantity (EOQ)</p>
                                    <p class="text-base font-medium" id="result-eoq"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Jumlah Order per Tahun</p>
                                    <p class="text-base font-medium" id="result-orders-per-year"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Waktu Antar Order</p>
                                    <p class="text-base font-medium" id="result-time-between-orders"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Total Biaya</p>
                                    <p class="text-base font-medium" id="result-total-cost"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Biaya Pemesanan</p>
                                    <p class="text-base font-medium" id="result-ordering-cost"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Biaya Penyimpanan</p>
                                    <p class="text-base font-medium" id="result-holding-cost"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Permintaan Tahunan</p>
                                    <p class="text-base font-medium" id="result-annual-demand"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('eoqForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route("admin.eoq.calculate") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('result').classList.remove('hidden');
                document.getElementById('result-product').textContent = data.product.nama_produk;
                document.getElementById('result-eoq').textContent = `${data.eoq} ${data.product.unit.nama_satuan}`;
                document.getElementById('result-orders-per-year').textContent = `${data.orders_per_year} kali`;
                document.getElementById('result-time-between-orders').textContent = `${data.time_between_orders} hari`;
                document.getElementById('result-total-cost').textContent = `Rp ${data.total_cost.toLocaleString('id-ID')}`;
                document.getElementById('result-ordering-cost').textContent = `Rp ${data.ordering_cost.toLocaleString('id-ID')}`;
                document.getElementById('result-holding-cost').textContent = `Rp ${data.holding_cost.toLocaleString('id-ID')}`;
                document.getElementById('result-annual-demand').textContent = `${data.annual_demand} ${data.product.unit.nama_satuan}`;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghitung EOQ');
            });
        });
    </script>
</x-app-layout> 