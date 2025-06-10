<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Transaksi Baru
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('admin.transactions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Kembali
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('admin.transactions.store') }}" method="POST" id="transactionForm">
                @csrf

                <!-- Informasi Pelanggan -->
                <div class="mb-6">
                    <label for="customer_id" class="block text-sm font-medium text-gray-700">Pelanggan</label>
                    <select name="customer_id" id="customer_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Pilih Pelanggan</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Daftar Produk -->
                <div class="mb-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Daftar Produk</h2>
                    <div id="products-container">
                        <div class="product-item grid grid-cols-12 gap-4 mb-4">
                            <div class="col-span-5">
                                <select name="products[0][id]" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Pilih Produk</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->harga_jual }}">
                                            {{ $product->nama_produk }} (Stok: {{ $product->stok }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-2">
                                <input type="number" name="products[0][quantity]" min="1" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Jumlah">
                            </div>
                            <div class="col-span-3">
                                <input type="text" readonly
                                    class="block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm"
                                    placeholder="Subtotal">
                            </div>
                            <div class="col-span-2">
                                <button type="button" class="remove-product inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-product" class="mt-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Tambah Produk
                    </button>
                </div>

                <!-- Informasi Pembayaran -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                        <select name="metode_pembayaran" id="metode_pembayaran" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Pilih Metode</option>
                            <option value="cash" {{ old('metode_pembayaran') == 'cash' ? 'selected' : '' }}>Tunai</option>
                            <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                            <option value="qris" {{ old('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS</option>
                        </select>
                        @error('metode_pembayaran')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="total_bayar" class="block text-sm font-medium text-gray-700">Total Bayar</label>
                        <input type="number" name="total_bayar" id="total_bayar" required min="0" step="0.01"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            value="{{ old('total_bayar') }}">
                        @error('total_bayar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Total dan Kembalian -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total Harga</label>
                        <input type="text" id="total_harga" readonly
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kembalian</label>
                        <input type="text" id="total_kembali" readonly
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm">
                    </div>
                </div>

                <!-- Catatan -->
                <div class="mb-6">
                    <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan</label>
                    <textarea name="catatan" id="catatan" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productsContainer = document.getElementById('products-container');
    const addProductBtn = document.getElementById('add-product');
    let productCount = 1;

    // Fungsi untuk menghitung subtotal
    function calculateSubtotal(row) {
        const select = row.querySelector('select');
        const quantity = row.querySelector('input[type="number"]');
        const subtotal = row.querySelector('input[type="text"]');
        
        if (select.value && quantity.value) {
            const price = select.options[select.selectedIndex].dataset.price;
            const total = price * quantity.value;
            subtotal.value = `Rp ${total.toLocaleString('id-ID')}`;
        } else {
            subtotal.value = '';
        }
        
        calculateTotal();
    }

    // Fungsi untuk menghitung total
    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.product-item').forEach(row => {
            const select = row.querySelector('select');
            const quantity = row.querySelector('input[type="number"]');
            
            if (select.value && quantity.value) {
                const price = select.options[select.selectedIndex].dataset.price;
                total += price * quantity.value;
            }
        });

        document.getElementById('total_harga').value = `Rp ${total.toLocaleString('id-ID')}`;
        calculateKembalian();
    }

    // Fungsi untuk menghitung kembalian
    function calculateKembalian() {
        const totalBayar = parseFloat(document.getElementById('total_bayar').value) || 0;
        const totalHarga = parseFloat(document.getElementById('total_harga').value.replace(/[^0-9.-]+/g, '')) || 0;
        const kembalian = totalBayar - totalHarga;
        
        document.getElementById('total_kembali').value = `Rp ${kembalian.toLocaleString('id-ID')}`;
    }

    // Event listener untuk menambah produk
    addProductBtn.addEventListener('click', function() {
        const template = document.querySelector('.product-item').cloneNode(true);
        const newIndex = productCount++;
        
        // Update nama field
        template.querySelectorAll('[name]').forEach(input => {
            input.name = input.name.replace('[0]', `[${newIndex}]`);
        });
        
        // Reset nilai
        template.querySelector('select').value = '';
        template.querySelector('input[type="number"]').value = '';
        template.querySelector('input[type="text"]').value = '';
        
        // Tambahkan event listener
        template.querySelector('select').addEventListener('change', () => calculateSubtotal(template));
        template.querySelector('input[type="number"]').addEventListener('input', () => calculateSubtotal(template));
        
        productsContainer.appendChild(template);
    });

    // Event listener untuk menghapus produk
    productsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-product')) {
            if (document.querySelectorAll('.product-item').length > 1) {
                e.target.closest('.product-item').remove();
                calculateTotal();
            }
        }
    });

    // Event listener untuk produk pertama
    const firstRow = document.querySelector('.product-item');
    firstRow.querySelector('select').addEventListener('change', () => calculateSubtotal(firstRow));
    firstRow.querySelector('input[type="number"]').addEventListener('input', () => calculateSubtotal(firstRow));

    // Event listener untuk total bayar
    document.getElementById('total_bayar').addEventListener('input', calculateKembalian);
});
</script>
 