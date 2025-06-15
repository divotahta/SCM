<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Transaksi
            </h2>
        </div>
    </x-slot>


    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <a href="{{ route('admin.transactions.show', $transaction) }}"
            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
            Kembali
        </a>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form action="{{ route('admin.transactions.update', $transaction) }}" method="POST" id="transactionForm">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Pelanggan -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pelanggan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="customer_id"
                                    class="block text-sm font-medium text-gray-700">Pelanggan</label>
                                <select name="customer_id" id="customer_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="{{ $transaction->pelanggan_id }}">
                                        {{ $transaction->customer->nama }}
                                    </option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id}}"
                                            {{ old('customer_id', $transaction->customer_id) == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Produk -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Daftar Produk</h3>
                            <button type="button" id="addProduct"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Tambah Produk
                            </button>
                        </div>

                        <div id="products">
                            @foreach ($transaction->details as $index => $detail)
                                <div class="product-row grid grid-cols-12 gap-4 mb-4">
                                    <div class="col-span-5">
                                        <select name="products[{{ $index }}][product_id]" required
                                            class="product-select block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="{{ $detail->produk_id }}">
                                                {{ $detail->product->nama_produk }}
                                            </option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    data-price="{{ $product->harga_jual }}"
                                                    {{ old("products.{$index}.product_id", $detail->product_id) == $product->id ? 'selected' : '' }}>
                                                    {{ $product->nama_produk }} (Stok: {{ $product->stok }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-span-2">
                                        <input type="number" name="products[{{ $index }}][quantity]" required
                                            min="1"
                                            value="{{ old("products.{$index}.quantity", $detail->jumlah) }}"
                                            class="quantity-input block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            placeholder="Jumlah">
                                    </div>
                                    <div class="col-span-2">
                                        <input type="number" name="products[{{ $index }}][price]" required
                                            readonly value="{{ old("products.{$index}.price", $detail->harga) }}"
                                            class="price-input block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm"
                                            placeholder="Harga">
                                    </div>
                                    <div class="col-span-2">
                                        <input type="number" name="products[{{ $index }}][subtotal]" required
                                            readonly value="{{ old("products.{$index}.subtotal", $detail->subtotal) }}"
                                            class="subtotal-input block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm"
                                            placeholder="Subtotal">
                                    </div>
                                    <div class="col-span-1">
                                        <button type="button"
                                            class="remove-product inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Informasi Pembayaran -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pembayaran</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700">Metode
                                    Pembayaran</label>
                                <select name="payment_method" id="payment_method" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="cash"
                                        {{ old('payment_method', $transaction->payment_method) == 'cash' ? 'selected' : '' }}>
                                        Tunai</option>
                                    <option value="transfer"
                                        {{ old('payment_method', $transaction->payment_method) == 'transfer' ? 'selected' : '' }}>
                                        Transfer</option>
                                    <option value="qris"
                                        {{ old('payment_method', $transaction->payment_method) == 'qris' ? 'selected' : '' }}>
                                        QRIS</option>
                                </select>
                            </div>
                            <div>
                                <label for="total_bayar" class="block text-sm font-medium text-gray-700">Total
                                    Dibayar</label>
                                <input type="number" name="total_bayar" id="total_bayar" required min="0"
                                    step="0.01" value="{{ old('total_bayar', $transaction->total_bayar) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="total_harga" class="block text-sm font-medium text-gray-700">Total
                                    Harga</label>
                                <input type="number" name="total_harga" id="total_harga" required readonly
                                    value="{{ old('total_harga', $transaction->total_harga) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm">
                            </div>
                            <div>
                                <label for="total_kembali" class="block text-sm font-medium text-gray-700">Kembalian</label>
                                <input type="number" name="total_kembali" id="total_kembali" required readonly
                                    value="{{ old('total_kembali', $transaction->total_kembali) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm">
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="batal"
                                        {{ old('status', $transaction->status) == 'batal' ? 'selected' : '' }}>
                                        Batal</option>
                                    <option value="selesai"
                                        {{ old('status', $transaction->status) == 'selesai' ? 'selected' : '' }}>
                                        Selesai</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <!-- Catatan -->
                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea name="notes" id="notes" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $transaction->notes) }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productsContainer = document.getElementById('products');
            const addProductButton = document.getElementById('addProduct');
            let productCount = {{ count($transaction->details) }};

            // Fungsi untuk menambah baris produk
            function addProductRow() {
                const template = document.querySelector('.product-row').cloneNode(true);
                const newIndex = productCount++;

                // Update nama field
                template.querySelectorAll('[name]').forEach(input => {
                    input.name = input.name.replace(/\[\d+\]/, `[${newIndex}]`);
                });

                // Reset nilai
                template.querySelectorAll('input').forEach(input => input.value = '');
                template.querySelector('select').selectedIndex = 0;

                // Tambahkan event listener baru
                addProductRowEventListeners(template);

                productsContainer.appendChild(template);
            }

            // Fungsi untuk menambahkan event listener ke baris produk
            function addProductRowEventListeners(row) {
                const productSelect = row.querySelector('.product-select');
                const quantityInput = row.querySelector('.quantity-input');
                const priceInput = row.querySelector('.price-input');
                const subtotalInput = row.querySelector('.subtotal-input');
                const removeButton = row.querySelector('.remove-product');

                // Event untuk memilih produk
                productSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const price = selectedOption.dataset.price || 0;
                    priceInput.value = price;
                    calculateSubtotal(row);
                });

                // Event untuk mengubah jumlah
                quantityInput.addEventListener('input', function() {
                    calculateSubtotal(row);
                });

                // Event untuk menghapus baris
                removeButton.addEventListener('click', function() {
                    if (document.querySelectorAll('.product-row').length > 1) {
                        row.remove();
                        calculateTotal();
                    }
                });
            }

            // Fungsi untuk menghitung subtotal
            function calculateSubtotal(row) {
                const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                const subtotal = quantity * price;
                row.querySelector('.subtotal-input').value = subtotal;
                calculateTotal();
            }

            // Fungsi untuk menghitung total
            function calculateTotal() {
                let total = 0;
                document.querySelectorAll('.subtotal-input').forEach(input => {
                    total += parseFloat(input.value) || 0;
                });
                document.getElementById('total_amount').value = total;
                calculateChange();
            }

            // Fungsi untuk menghitung kembalian
            function calculateChange() {
                const totalPaid = parseFloat(document.getElementById('total_paid').value) || 0;
                const totalAmount = parseFloat(document.getElementById('total_amount').value) || 0;
                const change = totalPaid - totalAmount;
                document.getElementById('change').value = change;
            }

            // Event listener untuk tombol tambah produk
            addProductButton.addEventListener('click', addProductRow);

            // Event listener untuk total pembayaran
            document.getElementById('total_paid').addEventListener('input', calculateChange);

            // Inisialisasi event listener untuk semua baris produk
            document.querySelectorAll('.product-row').forEach(row => {
                addProductRowEventListeners(row);
            });

            // Hitung total awal
            calculateTotal();
        });
    </script>
</x-app-layout>
