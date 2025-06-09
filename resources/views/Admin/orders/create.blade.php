<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Pesanan Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <a href="{{ route('admin.orders.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.orders.store') }}" method="POST" id="orderForm">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Informasi Dasar -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900">Informasi Dasar</h3>

                                <div>
                                    <label for="customer_id" class="block text-sm font-medium text-gray-700">Pelanggan
                                        <span class="text-red-500">*</span></label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <select name="customer_id" id="customer_id" required
                                            class="block w-full rounded-l-md border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="">Pilih Pelanggan</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" onclick="openCustomerModal()"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-r-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <i class="fas fa-plus mr-2"></i> Baru
                                        </button>
                                    </div>
                                    @error('customer_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tanggal_pesanan" class="block text-sm font-medium text-gray-700">Tanggal
                                        Pesanan <span class="text-red-500">*</span></label>
                                    <input type="datetime-local" name="tanggal_pesanan" id="tanggal_pesanan" required
                                        value="{{ old('tanggal_pesanan', now()->format('Y-m-d\TH:i')) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>

                                <div>
                                    <label for="status_pesanan" class="block text-sm font-medium text-gray-700">Status
                                        Pesanan <span class="text-red-500">*</span></label>
                                    <select name="status_pesanan" id="status_pesanan" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="pending"
                                            {{ old('status_pesanan') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing"
                                            {{ old('status_pesanan') == 'processing' ? 'selected' : '' }}>Processing
                                        </option>
                                        <option value="completed"
                                            {{ old('status_pesanan') == 'completed' ? 'selected' : '' }}>Completed
                                        </option>
                                        <option value="cancelled"
                                            {{ old('status_pesanan') == 'cancelled' ? 'selected' : '' }}>Cancelled
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Informasi Pembayaran -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900">Informasi Pembayaran</h3>

                                <div>
                                    <label for="metode_pembayaran"
                                        class="block text-sm font-medium text-gray-700">Metode Pembayaran <span
                                            class="text-red-500">*</span></label>
                                    <select name="metode_pembayaran" id="metode_pembayaran" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="cash"
                                            {{ old('metode_pembayaran') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="transfer"
                                            {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer
                                        </option>
                                        <option value="qris"
                                            {{ old('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="status_pembayaran"
                                        class="block text-sm font-medium text-gray-700">Status Pembayaran <span
                                            class="text-red-500">*</span></label>
                                    <select name="status_pembayaran" id="status_pembayaran" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="paid"
                                            {{ old('status_pembayaran') == 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="unpaid"
                                            {{ old('status_pembayaran') == 'unpaid' ? 'selected' : '' }}>Unpaid
                                        </option>
                                        <option value="partial"
                                            {{ old('status_pembayaran') == 'partial' ? 'selected' : '' }}>Partial
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label for="catatan"
                                        class="block text-sm font-medium text-gray-700">Catatan</label>
                                    <textarea name="catatan" id="catatan" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('catatan') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Daftar Produk -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Produk</h3>
                            <div id="productList" class="space-y-4">
                                <div class="product-item grid grid-cols-12 gap-4 items-end">
                                    <div class="col-span-5">
                                        <label class="block text-sm font-medium text-gray-700">Produk <span
                                                class="text-red-500">*</span></label>
                                        <select name="products[0][id]" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="">Pilih Produk</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    data-price="{{ $product->harga_jual }}">
                                                    {{ $product->nama_produk }} (Stok: {{ $product->stok }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Jumlah <span
                                                class="text-red-500">*</span></label>
                                        <input type="number" name="products[0][quantity]" required min="1"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Harga <span
                                                class="text-red-500">*</span></label>
                                        <input type="number" name="products[0][price]" required min="0"
                                            step="0.01"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Subtotal</label>
                                        <input type="text" readonly
                                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm">
                                    </div>
                                    <div class="col-span-1">
                                        <button type="button" class="remove-product text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="button" id="addProduct"
                                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    <i class="fas fa-plus mr-2"></i> Tambah Produk
                                </button>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <div class="text-right">
                                    <div class="text-2xl font-bold">
                                        Total: <span id="totalAmount">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                <i class="fas fa-save mr-2"></i> Simpan Pesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Pelanggan -->
    <div id="customerModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden" aria-hidden="true">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Tambah Pelanggan Baru</h3>
                    <form id="quickCustomerForm" class="space-y-4">
                        @csrf
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="nama" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                                Simpan
                            </button>
                            <button type="button" onclick="closeCustomerModal()"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const productList = document.getElementById('productList');
                const addProductBtn = document.getElementById('addProduct');
                let productCount = 1;

                // Fungsi untuk menghitung subtotal
                function calculateSubtotal(row) {
                    const quantity = parseFloat(row.querySelector('input[name$="[quantity]"]').value) || 0;
                    const price = parseFloat(row.querySelector('input[name$="[price]"]').value) || 0;
                    const subtotal = quantity * price;
                    row.querySelector('input[readonly]').value = `Rp ${subtotal.toLocaleString('id-ID')}`;
                    calculateTotal();
                }

                // Fungsi untuk menghitung total
                function calculateTotal() {
                    let total = 0;
                    document.querySelectorAll('.product-item').forEach(row => {
                        const quantity = parseFloat(row.querySelector('input[name$="[quantity]"]').value) || 0;
                        const price = parseFloat(row.querySelector('input[name$="[price]"]').value) || 0;
                        total += quantity * price;
                    });
                    document.getElementById('totalAmount').textContent = `Rp ${total.toLocaleString('id-ID')}`;
                }

                // Event listener untuk menambah produk
                addProductBtn.addEventListener('click', function() {
                    const template = productList.querySelector('.product-item').cloneNode(true);
                    const newIndex = productCount++;

                    // Update nama field
                    template.querySelectorAll('[name]').forEach(input => {
                        input.name = input.name.replace('[0]', `[${newIndex}]`);
                        input.value = '';
                    });

                    // Reset subtotal
                    template.querySelector('input[readonly]').value = '';

                    // Tambahkan event listeners
                    addProductEventListeners(template);

                    productList.appendChild(template);
                });

                // Fungsi untuk menambahkan event listeners ke baris produk
                function addProductEventListeners(row) {
                    // Event listener untuk menghapus produk
                    row.querySelector('.remove-product').addEventListener('click', function() {
                        if (document.querySelectorAll('.product-item').length > 1) {
                            row.remove();
                            calculateTotal();
                        }
                    });

                    // Event listener untuk perubahan quantity dan price
                    row.querySelector('input[name$="[quantity]"]').addEventListener('input', () => calculateSubtotal(
                        row));
                    row.querySelector('input[name$="[price]"]').addEventListener('input', () => calculateSubtotal(row));

                    // Event listener untuk perubahan produk
                    row.querySelector('select[name$="[id]"]').addEventListener('change', function() {
                        const option = this.options[this.selectedIndex];
                        const price = option.dataset.price;
                        row.querySelector('input[name$="[price]"]').value = price;
                        calculateSubtotal(row);
                    });
                }

                // Tambahkan event listeners ke baris produk pertama
                addProductEventListeners(productList.querySelector('.product-item'));

                // Event listener untuk form submit
                document.getElementById('orderForm').addEventListener('submit', function(e) {
                    const products = document.querySelectorAll('.product-item');
                    let isValid = true;

                    products.forEach(row => {
                        const quantity = parseInt(row.querySelector('input[name$="[quantity]"]').value);
                        const productSelect = row.querySelector('select[name$="[id]"]');
                        const selectedOption = productSelect.options[productSelect.selectedIndex];
                        const stock = parseInt(selectedOption.text.match(/Stok: (\d+)/)[1]);

                        if (quantity > stock) {
                            alert(`Stok tidak mencukupi untuk produk ${selectedOption.text}`);
                            isValid = false;
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                    }
                });
            });

            function openCustomerModal() {
                document.getElementById('customerModal').classList.remove('hidden');
            }

            function closeCustomerModal() {
                document.getElementById('customerModal').classList.add('hidden');
            }

            document.getElementById('quickCustomerForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch('{{ route('admin.customers.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Tambahkan option baru ke select
                            const select = document.getElementById('customer_id');
                            const option = new Option(data.customer.nama, data.customer.id);
                            select.add(option);
                            select.value = data.customer.id;

                            // Tutup modal
                            closeCustomerModal();

                            // Reset form
                            this.reset();
                        } else {
                            alert('Terjadi kesalahan: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menyimpan data');
                    });
            });
        </script>
    @endpush
</x-app-layout>
