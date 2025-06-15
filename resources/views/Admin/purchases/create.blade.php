<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tambah Pembelian Baru
        </h2>

        </div>
    </x-slot>


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <a href="{{ route('admin.purchases.index') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.purchases.store') }}" method="POST" id="purchaseForm">
                        @csrf
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                            <label for="pemasok_id" class="block text-sm font-medium text-gray-700">Supplier</label>
                            <select name="pemasok_id" id="pemasok_id"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md @error('pemasok_id') border-red-300 @enderror"
                                required>
                                        <option value="">Pilih Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ old('pemasok_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->nama }} - {{ $supplier->nama_toko }}
                                            </option>
                                        @endforeach
                                    </select>
                            @error('pemasok_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                            <label for="tanggal_pembelian" class="block text-sm font-medium text-gray-700">Tanggal
                                Pembelian</label>
                            <input type="date" name="tanggal_pembelian" id="tanggal_pembelian"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('tanggal_pembelian') border-red-300 @enderror"
                                value="{{ old('tanggal_pembelian', date('Y-m-d')) }}" required>
                                    @error('tanggal_pembelian')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                    <div class="mt-6">
                        <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea name="catatan" id="catatan" rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('catatan') border-red-300 @enderror">{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        </div>

                    <div class="mt-6">
                            <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Detail Produk</h3>
                            <button type="button" id="addRow"
                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-plus mr-2"></i> Tambah Produk
                                </button>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Produk</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jumlah</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Harga Satuan</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi</th>
                                        </tr>
                                    </thead>
                                <tbody id="productTable" class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <select name="products[0][produk_id]"
                                                class="product-select block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                                required>
                                                <option value="">Pilih Produk</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}"
                                                        data-price="{{ $product->harga_beli }}">
                                                        {{ $product->nama_produk }} ({{ $product->kode_produk }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="number" name="products[0][jumlah]"
                                                class="quantity block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                min="1" value="1" required>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="number" name="products[0][harga_satuan]"
                                                class="price block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                min="0" step="0.01" required>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="text"
                                                class="subtotal block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                readonly>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button type="button" class="text-red-600 hover:text-red-900 remove-row">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                <tfoot>
                                    <tr class="bg-gray-50">
                                        <td colspan="3" class="px-6 py-4 text-right font-semibold">Total Keseluruhan:</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="text" id="grandTotal" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm font-semibold" readonly>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                                </table>
                            </div>
                        </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Simpan
                                </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let rowCount = 1;

            // Fungsi untuk menghitung subtotal
            function calculateSubtotal(row) {
                const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                const price = parseFloat(row.querySelector('.price').value) || 0;
                const subtotal = quantity * price;
                row.querySelector('.subtotal').value = subtotal.toFixed(2);
                calculateGrandTotal();
            }

            // Fungsi untuk menghitung total keseluruhan
            function calculateGrandTotal() {
                const subtotals = document.querySelectorAll('.subtotal');
                let grandTotal = 0;
                subtotals.forEach(subtotal => {
                    grandTotal += parseFloat(subtotal.value) || 0;
                });
                document.getElementById('grandTotal').value = grandTotal.toFixed(2);
            }

            // Event handler untuk perubahan quantity dan price
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('quantity') || e.target.classList.contains('price')) {
                    calculateSubtotal(e.target.closest('tr'));
                }
            });

            // Event handler untuk pemilihan produk
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('product-select')) {
                    const selectedOption = e.target.options[e.target.selectedIndex];
                    const price = selectedOption.dataset.price;
                    const row = e.target.closest('tr');
                    row.querySelector('.price').value = price;
                    calculateSubtotal(row);
                }
            });

            // Tambah baris baru
            document.getElementById('addRow').addEventListener('click', function() {
                const newRow = `
                    <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                            <select name="products[${rowCount}][produk_id]" class="product-select block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" required>
                        <option value="">Pilih Produk</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->harga_beli }}">
                                        {{ $product->nama_produk }} ({{ $product->kode_produk }})
                            </option>
                                @endforeach
                    </select>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                            <input type="number" name="products[${rowCount}][jumlah]" class="quantity block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" min="1" value="1" required>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                            <input type="number" name="products[${rowCount}][harga_satuan]" class="price block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" min="0" step="0.01" required>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                            <input type="text" class="subtotal block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" readonly>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button type="button" class="text-red-600 hover:text-red-900 remove-row">
                                <i class="fas fa-trash"></i>
                            </button>
                </td>
            </tr>
        `;
                document.getElementById('productTable').insertAdjacentHTML('beforeend', newRow);
                rowCount++;
            });

            // Hapus baris
            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-row')) {
                    const productTable = document.getElementById('productTable');
                    if (productTable.children.length > 1) {
                        e.target.closest('tr').remove();
                        calculateGrandTotal();
                    } else {
                        alert('Minimal harus ada satu produk');
                    }
                }
            });

        // Validasi form sebelum submit
        document.getElementById('purchaseForm').addEventListener('submit', function(e) {
                const productRows = document.querySelectorAll('#productTable tr');
            if (productRows.length === 0) {
                e.preventDefault();
                    alert('Minimal harus ada satu produk');
                    return false;
            }

            let isValid = true;
                productRows.forEach(function(row) {
                    const productId = row.querySelector('.product-select').value;
                    const quantity = row.querySelector('.quantity').value;
                    const price = row.querySelector('.price').value;

                    if (!productId || !quantity || !price) {
                    isValid = false;
                }
            });

            if (!isValid) {
                e.preventDefault();
                    alert('Semua field harus diisi');
                    return false;
            }
            });

            // Hitung total awal
            calculateGrandTotal();
        });
    </script>
</x-app-layout> 
