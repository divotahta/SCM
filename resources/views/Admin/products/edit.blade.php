<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Edit Produk</h2>
                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-sm font-medium text-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>

            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Produk -->
                    <div>
                        <label for="nama_produk" class="block font-medium text-sm text-gray-700">Nama Produk <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_produk" name="nama_produk" required
                            value="{{ old('nama_produk', $product->nama_produk) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('nama_produk') border-red-500 @enderror">
                        @error('nama_produk')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kode Produk -->
                    <div>
                        <label for="kode_produk" class="block font-medium text-sm text-gray-700">Kode Produk <span class="text-red-500">*</span></label>
                        <input type="text" id="kode_produk" name="kode_produk" required
                            value="{{ old('kode_produk', $product->kode_produk) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('kode_produk') border-red-500 @enderror">
                        <p class="text-sm text-red-600 mt-1" id="kode_produk-feedback"></p>
                        @error('kode_produk')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="kategori_id" class="block font-medium text-sm text-gray-700">Kategori <span class="text-red-500">*</span></label>
                        <select id="kategori_id" name="kategori_id" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('kategori_id') border-red-500 @enderror">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('kategori_id', $product->kategori_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Satuan -->
                    <div>
                        <label for="satuan_id" class="block font-medium text-sm text-gray-700">Satuan <span class="text-red-500">*</span></label>
                        <select id="satuan_id" name="satuan_id" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('satuan_id') border-red-500 @enderror">
                            <option value="">Pilih Satuan</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id', $product->unit_id) == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->nama_satuan }}
                                </option>
                            @endforeach
                        </select>
                        @error('satuan_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga Beli -->
                    <div>
                        <label for="harga_beli" class="block font-medium text-sm text-gray-700">Harga Beli <span class="text-red-500">*</span></label>
                        <input type="number" id="harga_beli" name="harga_beli" required min="0"
                            value="{{ old('harga_beli', $product->harga_beli) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('harga_beli') border-red-500 @enderror">
                        @error('harga_beli')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga Jual -->
                    <div>
                        <label for="harga_jual" class="block font-medium text-sm text-gray-700">Harga Jual <span class="text-red-500">*</span></label>
                        <input type="number" id="harga_jual" name="harga_jual" required min="0"
                            value="{{ old('harga_jual', $product->harga_jual) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('harga_jual') border-red-500 @enderror">
                        @error('harga_jual')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stok -->
                    <div>
                        <label for="stok" class="block font-medium text-sm text-gray-700">Stok <span class="text-red-500">*</span></label>
                        <input type="number" id="stok" name="stok" required min="0"
                            value="{{ old('stok', $product->stok) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('stok') border-red-500 @enderror">
                        @error('stok')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gambar -->
                    <div>
                        <label for="gambar_produk" class="block font-medium text-sm text-gray-700">Gambar Produk</label>
                        <input type="file" id="gambar_produk" name="gambar_produk" accept="image/*"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('gambar_produk') border-red-500 @enderror">
                        @error('gambar_produk')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <div id="image-preview" class="mt-2">
                            @if($product->gambar_produk)
                                <img src="{{ Storage::url('products/' . $product->gambar_produk) }}" alt="{{ $product->nama_produk }}"
                                    class="rounded-md shadow w-auto max-h-48">
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const imageInput = document.getElementById('image');
            const preview = document.getElementById('image-preview');

            imageInput.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        preview.innerHTML = `<img src="${e.target.result}" class="rounded-md shadow w-auto max-h-48" alt="Preview">`;
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Cek kode unik
            let timer;
            const codeInput = document.getElementById('code');
            const codeFeedback = document.getElementById('code-feedback');

            codeInput.addEventListener('keyup', function () {
                clearTimeout(timer);
                const code = this.value;

                if (code) {
                    timer = setTimeout(() => {
                        fetch(`{{ route('admin.products.check-code') }}?code=${code}&id={{ $product->id }}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.exists) {
                                    codeInput.classList.add('border-red-500');
                                    codeFeedback.textContent = 'Kode produk sudah digunakan';
                                } else {
                                    codeInput.classList.remove('border-red-500');
                                    codeFeedback.textContent = '';
                                }
                            });
                    }, 500);
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
