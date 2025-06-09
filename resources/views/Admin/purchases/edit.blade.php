<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Pembelian') }}
            </h2>
            <a href="{{ route('admin.purchases.show', $purchase) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form id="purchaseForm" class="space-y-6">
                        <!-- Informasi Pembelian -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="tanggal_pembelian" class="block text-sm font-medium text-gray-700">Tanggal Pembelian</label>
                                <input type="date" name="tanggal_pembelian" id="tanggal_pembelian" value="{{ $purchase->tanggal_pembelian->format('Y-m-d') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label for="pemasok_id" class="block text-sm font-medium text-gray-700">Pemasok</label>
                                <select name="pemasok_id" id="pemasok_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Pilih Pemasok</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ $purchase->pemasok_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Detail Produk -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900">Detail Produk</h3>
                            <div id="productList" class="mt-4 space-y-4">
                                @foreach($purchase->details as $index => $detail)
                                    <div class="product-row grid grid-cols-12 gap-4 items-end">
                                        <div class="col-span-4">
                                            <label class="block text-sm font-medium text-gray-700">Produk</label>
                                            <select name="products[{{ $index }}][id]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <option value="">Pilih Produk</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" data-price="{{ $product->harga_beli }}" {{ $detail->produk_id == $product->id ? 'selected' : '' }}>{{ $product->nama_produk }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                                            <input type="number" name="products[{{ $index }}][jumlah]" value="{{ $detail->jumlah }}" required min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-sm font-medium text-gray-700">Harga Satuan</label>
                                            <input type="number" name="products[{{ $index }}][harga_satuan]" value="{{ $detail->harga_satuan }}" required min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-sm font-medium text-gray-700">Subtotal</label>
                                            <input type="text" readonly value="Rp {{ number_format($detail->total, 0, ',', '.') }}" class="subtotal mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm">
                                        </div>
                                        <div class="col-span-2">
                                            <button type="button" class="remove-product bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" id="addProduct" class="mt-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Tambah Produk
                            </button>
                        </div>

                        <!-- Catatan -->
                        <div class="mt-6">
                            <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan</label>
                            <textarea name="catatan" id="catatan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ $purchase->catatan }}</textarea>
                        </div>

                        <!-- Total dan Tombol -->
                        <div class="mt-6 flex justify-between items-center">
                            <div>
                                <span class="text-lg font-medium text-gray-900">Total:</span>
                                <span id="totalAmount" class="text-2xl font-bold text-indigo-600">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="space-x-4">
                                <a href="{{ route('admin.purchases.show', $purchase) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Batal
                                </a>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Simpan
                                </button>
                            </div>
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
            const purchaseForm = document.getElementById('purchaseForm');
            let productCount = {{ count($purchase->details) }};

            // Fungsi untuk menghitung subtotal
            function calculateSubtotal(row) {
                const quantity = parseFloat(row.querySelector('input[name$="[jumlah]"]').value) || 0;
                const price = parseFloat(row.querySelector('input[name$="[harga_satuan]"]').value) || 0;
                const subtotal = quantity * price;
                row.querySelector('.subtotal').value = `Rp ${subtotal.toLocaleString('id-ID')}`;
                calculateTotal();
            }

            // Fungsi untuk menghitung total
            function calculateTotal() {
                let total = 0;
                document.querySelectorAll('.product-row').forEach(row => {
                    const quantity = parseFloat(row.querySelector('input[name$="[jumlah]"]').value) || 0;
                    const price = parseFloat(row.querySelector('input[name$="[harga_satuan]"]').value) || 0;
                    total += quantity * price;
                });
                document.getElementById('totalAmount').textContent = `Rp ${total.toLocaleString('id-ID')}`;
            }

            // Event listener untuk menambah produk
            addProductBtn.addEventListener('click', function() {
                const template = document.querySelector('.product-row').cloneNode(true);
                const newIndex = productCount++;
                
                // Update nama field
                template.querySelectorAll('[name]').forEach(input => {
                    input.name = input.name.replace(/\[\d+\]/, `[${newIndex}]`);
                    input.value = '';
                });
                
                // Reset subtotal
                template.querySelector('.subtotal').value = '';
                
                // Tambahkan event listeners
                template.querySelector('select').addEventListener('change', function() {
                    const option = this.options[this.selectedIndex];
                    const price = option.dataset.price;
                    template.querySelector('input[name$="[harga_satuan]"]').value = price;
                    calculateSubtotal(template);
                });
                
                template.querySelectorAll('input[type="number"]').forEach(input => {
                    input.addEventListener('input', () => calculateSubtotal(template));
                });
                
                template.querySelector('.remove-product').addEventListener('click', function() {
                    template.remove();
                    calculateTotal();
                });
                
                productList.appendChild(template);
            });

            // Event listener untuk semua produk
            document.querySelectorAll('.product-row').forEach(row => {
                row.querySelector('select').addEventListener('change', function() {
                    const option = this.options[this.selectedIndex];
                    const price = option.dataset.price;
                    this.closest('.product-row').querySelector('input[name$="[harga_satuan]"]').value = price;
                    calculateSubtotal(this.closest('.product-row'));
                });
                
                row.querySelectorAll('input[type="number"]').forEach(input => {
                    input.addEventListener('input', () => calculateSubtotal(input.closest('.product-row')));
                });
                
                row.querySelector('.remove-product').addEventListener('click', function() {
                    row.remove();
                    calculateTotal();
                });
            });

            // Event listener untuk form submit
            purchaseForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                fetch('{{ route("admin.purchases.update", $purchase) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memperbarui pembelian');
                });
            });
        });
    </script>
    @endpush
</x-app-layout> 