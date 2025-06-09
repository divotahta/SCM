<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Forecast Kebutuhan Stok') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg">
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-700">Forecast Kebutuhan Stok</h3>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.stocks.index') }}" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded hover:bg-gray-200">
                            ← Kembali
                        </a>
                        <button type="button" class="px-4 py-2 text-sm text-white bg-green-600 rounded hover:bg-green-700" onclick="document.getElementById('exportModal').classList.remove('hidden')">
                            Export
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <form action="{{ route('admin.stocks.forecast') }}" method="GET" class="mb-6">
                        <div class="grid grid-cols-5 gap-4">
                            <div class="col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select name="category_id" class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-2 flex items-end">
                                <button type="submit" class="w-full px-4 py-2 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="overflow-auto rounded-md">
                        <table class="min-w-full text-sm text-left text-gray-700 border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 border">Kode</th>
                                    <th class="px-4 py-2 border">Produk</th>
                                    <th class="px-4 py-2 border">Kategori</th>
                                    <th class="px-4 py-2 border">Stok Saat Ini</th>
                                    <th class="px-4 py-2 border">Minimal Stok</th>
                                    <th class="px-4 py-2 border">Rata-rata Penjualan/Bulan</th>
                                    <th class="px-4 py-2 border">Lead Time (Hari)</th>
                                    <th class="px-4 py-2 border">Safety Stock</th>
                                    <th class="px-4 py-2 border">Reorder Point</th>
                                    <th class="px-4 py-2 border">EOQ</th>
                                    <th class="px-4 py-2 border">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @forelse($products as $product)
                                    <tr>
                                        <td class="px-4 py-2 border">{{ $product->code }}</td>
                                        <td class="px-4 py-2 border">{{ $product->name }}</td>
                                        <td class="px-4 py-2 border">{{ $product->category->name }}</td>
                                        <td class="px-4 py-2 border">{{ $product->stock }} {{ $product->unit->name }}</td>
                                        <td class="px-4 py-2 border">{{ $product->min_stock }} {{ $product->unit->name }}</td>
                                        <td class="px-4 py-2 border">{{ number_format($product->forecast['monthly_sales'], 2) }} {{ $product->unit->name }}</td>
                                        <td class="px-4 py-2 border">{{ $product->forecast['lead_time'] }} hari</td>
                                        <td class="px-4 py-2 border">{{ number_format($product->forecast['safety_stock'], 2) }} {{ $product->unit->name }}</td>
                                        <td class="px-4 py-2 border">{{ number_format($product->forecast['reorder_point'], 2) }} {{ $product->unit->name }}</td>
                                        <td class="px-4 py-2 border">{{ number_format($product->forecast['eoq'], 2) }} {{ $product->unit->name }}</td>
                                        <td class="px-4 py-2 border">
                                            @if($product->stock <= $product->forecast['reorder_point'])
                                                <span class="inline-block px-2 py-1 text-xs text-yellow-800 bg-yellow-200 rounded">Perlu Order</span>
                                            @else
                                                <span class="inline-block px-2 py-1 text-xs text-green-800 bg-green-200 rounded">Stok Aman</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="px-4 py-3 text-center border text-gray-500">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Export -->
    <div id="exportModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
        <div class="w-full max-w-md p-4 bg-white rounded shadow">
            <form action="{{ route('admin.stocks.forecast') }}" method="GET">
                <div class="flex items-center justify-between pb-3 border-b">
                    <h2 class="text-lg font-semibold">Export Forecast Stok</h2>
                    <button type="button" class="text-gray-500 hover:text-gray-700" onclick="document.getElementById('exportModal').classList.add('hidden')">
                        ✕
                    </button>
                </div>
                <div class="py-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Format</label>
                    <select name="export" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="excel">Excel</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded hover:bg-gray-300" onclick="document.getElementById('exportModal').classList.add('hidden')">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">
                        Export
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
