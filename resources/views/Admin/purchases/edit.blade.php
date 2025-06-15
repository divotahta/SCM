<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pembelian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.purchases.update', $purchase) }}" method="POST" id="purchaseForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Informasi Pemasok -->
                            <div class="space-y-4">
                                <div>
                                    <label for="pemasok_id" class="block text-sm font-medium text-gray-700">Pemasok</label>
                                    <select name="pemasok_id" id="pemasok_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Pilih Pemasok</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ $purchase->pemasok_id == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pemasok_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tanggal_pembelian" class="block text-sm font-medium text-gray-700">Tanggal Pembelian</label>
                                    <input type="date" name="tanggal_pembelian" id="tanggal_pembelian" required
                                        value="{{ old('tanggal_pembelian', $purchase->tanggal_pembelian->format('Y-m-d')) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('tanggal_pembelian')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status Pembelian -->
                            <div class="space-y-4">
                                <div>
                                    <label for="status_pembelian" class="block text-sm font-medium text-gray-700">Status Pembelian</label>
                                    <select name="status_pembelian" id="status_pembelian" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="pending" {{ $purchase->status_pembelian == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ $purchase->status_pembelian == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                        <option value="rejected" {{ $purchase->status_pembelian == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                        <option value="received" {{ $purchase->status_pembelian == 'received' ? 'selected' : '' }}>Diterima</option>
                                    </select>
                                    @error('status_pembelian')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                @if($purchase->status_pembelian == 'rejected')
                                <div>
                                    <label for="alasan_penolakan" class="block text-sm font-medium text-gray-700">Alasan Penolakan</label>
                                    <textarea name="alasan_penolakan" id="alasan_penolakan" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('alasan_penolakan', $purchase->alasan_penolakan) }}</textarea>
                                    @error('alasan_penolakan')
                                        {{-- <p class="mt-1 text-sm text-red-600">{{ $message }}</p> --}}
                                    @enderror
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Detail Item -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Item</h3>
                            <div id="items-container" class="space-y-4">
                                @foreach($purchase->details as $index => $detail)
                                <div class="item-row bg-gray-50 p-4 rounded-lg">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Produk</label>
                                            <select name="items[{{ $index }}][produk_id]" required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">Pilih Produk</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" 
                                                        {{ $detail->produk_id == $product->id ? 'selected' : '' }}
                                                        data-harga="{{ $product->harga_beli }}">
                                                        {{ $product->nama_produk }} ({{ $product->unit->nama_satuan }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                                            <input type="number" name="items[{{ $index }}][jumlah]" required min="1"
                                                value="{{ old("items.{$index}.jumlah", $detail->jumlah) }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Harga Satuan</label>
                                            <input type="number" name="items[{{ $index }}][harga_satuan]" required min="0"
                                                value="{{ old("items.{$index}.harga_satuan", $detail->harga_satuan) }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Total</label>
                                            <input type="number" name="items[{{ $index }}][total]" required readonly
                                                value="{{ old("items.{$index}.total", $detail->total) }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Catatan</label>
                                            <input type="text" name="items[{{ $index }}][catatan]"
                                                value="{{ old("items.{$index}.catatan", $detail->catatan) }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                    </div>
                                    @if($index > 0)
                                    <div class="mt-2">
                                        <button type="button" class="remove-item text-red-600 hover:text-red-800 text-sm font-medium">
                                            Hapus Item
                                        </button>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>

                            <div class="mt-4">
                                <button type="button" id="add-item" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Tambah Item
                                </button>
                            </div>
                        </div>

                        <!-- Total Pembelian -->
                        <div class="mb-6">
                            <div class="flex justify-end">
                                <div class="w-full md:w-1/3">
                                    <label for="total" class="block text-sm font-medium text-gray-700">Total Pembelian</label>
                                    <input type="number" name="total" id="total" required readonly
                                        value="{{ old('total', $purchase->total_amount) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('admin.purchases.index') }}" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25">
                                Batal
                            </a>
                            <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
            const itemsContainer = document.getElementById('items-container');
            const addItemButton = document.getElementById('add-item');
            const totalInput = document.getElementById('total');
            let itemCount = {{ count($purchase->details) }};

            // Template untuk item baru
            const itemTemplate = `
                <div class="item-row bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Produk</label>
                            <select name="items[INDEX][produk_id]" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Pilih Produk</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                        data-harga="{{ $product->harga_beli }}">
                                        {{ $product->nama_produk }} ({{ $product->unit->nama_satuan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                            <input type="number" name="items[INDEX][jumlah]" required min="1" value="1"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Harga Satuan</label>
                            <input type="number" name="items[INDEX][harga_satuan]" required min="0"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Total</label>
                            <input type="number" name="items[INDEX][total]" required readonly
                                class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Catatan</label>
                            <input type="text" name="items[INDEX][catatan]"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="button" class="remove-item text-red-600 hover:text-red-800 text-sm font-medium">
                            Hapus Item
                        </button>
                    </div>
                </div>
            `;

            // Fungsi untuk menambah item baru
            function addItem() {
                const newItem = itemTemplate.replace(/INDEX/g, itemCount);
                itemsContainer.insertAdjacentHTML('beforeend', newItem);
                itemCount++;
                updateTotal();
            }

            // Event listener untuk tombol tambah item
            addItemButton.addEventListener('click', addItem);

            // Event delegation untuk tombol hapus item
            itemsContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item')) {
                    e.target.closest('.item-row').remove();
                    updateTotal();
                }
            });

            // Event delegation untuk update total item
            itemsContainer.addEventListener('input', function(e) {
                const input = e.target;
                if (input.name && input.name.includes('[jumlah]') || input.name.includes('[harga_satuan]')) {
                    const row = input.closest('.item-row');
                    const jumlah = row.querySelector('input[name$="[jumlah]"]').value;
                    const hargaSatuan = row.querySelector('input[name$="[harga_satuan]"]').value;
                    const total = jumlah * hargaSatuan;
                    row.querySelector('input[name$="[total]"]').value = total;
                    updateTotal();
                }
            });

            // Event delegation untuk update harga satuan saat produk dipilih
            itemsContainer.addEventListener('change', function(e) {
                const select = e.target;
                if (select.name && select.name.includes('[produk_id]')) {
                    const option = select.options[select.selectedIndex];
                    const harga = option.dataset.harga;
                    const row = select.closest('.item-row');
                    row.querySelector('input[name$="[harga_satuan]"]').value = harga;
                    updateItemTotal(row);
                }
            });

            // Fungsi untuk update total item
            function updateItemTotal(row) {
                const jumlah = row.querySelector('input[name$="[jumlah]"]').value;
                const hargaSatuan = row.querySelector('input[name$="[harga_satuan]"]').value;
                const total = jumlah * hargaSatuan;
                row.querySelector('input[name$="[total]"]').value = total;
                updateTotal();
            }

            // Fungsi untuk update total keseluruhan
            function updateTotal() {
                let total = 0;
                document.querySelectorAll('input[name$="[total]"]').forEach(input => {
                    total += parseFloat(input.value) || 0;
                });
                totalInput.value = total;
            }

            // Inisialisasi total saat halaman dimuat
            updateTotal();
        });
    </script>
</x-app-layout>
