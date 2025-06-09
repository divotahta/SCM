<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Manajemen Produk</h2>
           
        </div>
    </x-slot>

    {{-- <div class="py-4"> --}}
        {{-- <div class="mx-auto sm:px-6 lg:px-8"> --}}
            {{-- <div class="bg-white shadow rounded p-6"> --}}
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-700">Manajemen Produk</h3>
                    <a href="{{ route('admin.products.create') }}" class="px-4 py-2 text-sm text-white bg-green-600 rounded hover:bg-green-700">
                        Tambah Produk
                    </a>
                </div>

                <!-- Filter Form -->
                <form action="{{ route('admin.products.index') }}" method="GET" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pencarian</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama/kode produk..."
                                class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select name="kategori_id" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('kategori_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status Stok</label>
                            <select name="stock_status" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                <option value="">Semua Status</option>
                                <option value="low" {{ request('status_stok') == 'low' ? 'selected' : '' }}>Stok
                                    Rendah</option>
                                <option value="out" {{ request('status_stok') == 'out' ? 'selected' : '' }}>Stok
                                    Habis</option>
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                <i class="fas fa-search mr-1"></i> Filter
                            </button>
                            <a href="{{ route('admin.products.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                <i class="fas fa-sync mr-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Products Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-100 text-left text-sm font-semibold text-gray-700">
                                <th class="p-2">
                                    No
                                </th>
                                <th class="p-2">Gambar</th>
                                <th class="p-2">Kode</th>
                                <th class="p-2">Nama Produk</th>
                                <th class="p-2">Kategori</th>
                                <th class="p-2">Satuan</th>
                                <th class="p-2">Harga Beli</th>
                                <th class="p-2">Harga Jual</th>
                                <th class="p-2">Stok</th>
                                <th class="p-2">Status</th>
                                <th class="p-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-2">{{ $loop->iteration }}</td>
                                    <td class="p-2">
                                        @if ($product->image)
                                            <img src="{{ Storage::url('products/' . $product->image) }}"
                                                alt="{{ $product->nama_produk }}" class="w-12 h-12 object-cover rounded">
                                        @else
                                            <img src="{{ asset('images/no-image.png') }}" alt="No Image"
                                                class="w-12 h-12 object-cover rounded">
                                        @endif
                                    </td>
                                    <td class="p-2">{{ $product->kode_produk }}</td>
                                    <td class="p-2">{{ $product->nama_produk }}</td>
                                    <td class="p-2">{{ $product->category->nama_kategori }}</td>
                                    <td class="p-2">{{ $product->unit->nama_satuan }}</td>
                                    <td class="p-2">Rp {{ number_format($product->harga_beli, 0, ',', '.') }}
                                    </td>
                                    <td class="p-2">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}
                                    </td>
                                    <td class="p-2">{{ $product->stok }}</td>
                                    <td class="p-2">
                                        @if ($product->stok <= 0)
                                            <span
                                                class="inline-block px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded">Habis</span>
                                        @elseif($product->stok <= 10)
                                            <span
                                                class="inline-block px-2 py-1 text-xs font-semibold text-white bg-yellow-500 rounded">Rendah</span>
                                        @else
                                            <span
                                                class="inline-block px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">Tersedia</span>
                                        @endif
                                    </td>
                                    <td class="p-2 space-x-1">
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                            class="inline-block px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-block px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="p-4 text-center text-gray-500">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $products->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
