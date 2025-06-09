<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Stok') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header Buttons -->
                    <div class="flex justify-end space-x-2 mb-4">
                        <a href="{{ route('admin.stocks.history') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-150 ease-in-out">
                            <i class="fas fa-history"></i> History Stok
                        </a>
                        <a href="{{ route('admin.stocks.forecast') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md transition duration-150 ease-in-out">
                            <i class="fas fa-chart-line"></i> Forecast
                        </a>
                        <button type="button" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition duration-150 ease-in-out" onclick="openModal('exportModal')">
                            <i class="fas fa-file-export"></i> Export
                        </button>
                    </div>

                    <!-- Search Form -->
                    <form action="{{ route('admin.stocks.index') }}" method="GET" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <input type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    name="search" placeholder="Cari produk..." value="{{ request('search') }}">
                            </div>
                            <div>
                                <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="category_id">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="stock_status">
                                    <option value="">Semua Status</option>
                                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>
                                        Stok Menipis
                                    </option>
                                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>
                                        Stok Habis
                                    </option>
                                    <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>
                                        Stok Tersedia
                                    </option>
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="w-full bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md transition duration-150 ease-in-out">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Minimal Stok</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($products as $product)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->code }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->category->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->stock }} {{ $product->unit->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->min_stock }} {{ $product->unit->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($product->stock <= 0)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Habis
                                                </span>
                                            @elseif($product->stock <= $product->min_stock)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Menipis
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Tersedia
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button type="button" class="text-blue-600 hover:text-blue-900 mr-3 transition duration-150 ease-in-out" 
                                                onclick="openModal('adjustModal{{ $product->id }}')">
                                                <i class="fas fa-edit"></i> Sesuaikan
                                            </button>
                                            <button type="button" class="text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out" 
                                                onclick="openModal('barcodeModal{{ $product->id }}')">
                                                <i class="fas fa-barcode"></i> Barcode
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Adjust Stock -->
                                    <div id="adjustModal{{ $product->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
                                        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                            <div class="mt-3">
                                                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Sesuaikan Stok - {{ $product->name }}</h3>
                                                <form action="{{ route('admin.stocks.adjust', $product->id) }}" method="POST">
                                                    @csrf
                                                    <div class="mb-4">
                                                        <label class="block text-gray-700 text-sm font-bold mb-2">Tipe</label>
                                                        <select name="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                                            <option value="addition">Penambahan</option>
                                                            <option value="reduction">Pengurangan</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah</label>
                                                        <input type="number" name="quantity" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                                            required min="1">
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="block text-gray-700 text-sm font-bold mb-2">Alasan</label>
                                                        <textarea name="reason" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" rows="3" required></textarea>
                                                    </div>
                                                    <div class="flex justify-end space-x-2">
                                                        <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-150 ease-in-out" 
                                                            onclick="closeModal('adjustModal{{ $product->id }}')">
                                                            Batal
                                                        </button>
                                                        <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md transition duration-150 ease-in-out">
                                                            Simpan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Barcode -->
                                    <div id="barcodeModal{{ $product->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
                                        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                            <div class="mt-3">
                                                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Barcode - {{ $product->name }}</h3>
                                                <div class="text-center">
                                                    <img src="{{ route('admin.stocks.barcode', $product->id) }}" 
                                                        alt="Barcode {{ $product->code }}" class="mx-auto">
                                                    <p class="mt-2 text-gray-600">{{ $product->code }}</p>
                                                </div>
                                                <div class="flex justify-end space-x-2 mt-4">
                                                    <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-150 ease-in-out" 
                                                        onclick="closeModal('barcodeModal{{ $product->id }}')">
                                                        Tutup
                                                    </button>
                                                    <a href="{{ route('admin.stocks.barcode', $product->id) }}" 
                                                        class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md transition duration-150 ease-in-out" 
                                                        download>
                                                        <i class="fas fa-download"></i> Download
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            Tidak ada data
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div id="exportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Export Data Stok</h3>
                <form action="{{ route('admin.stocks.export') }}" method="GET">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Format</label>
                        <select name="format" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            <option value="excel">Excel</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-150 ease-in-out" 
                            onclick="closeModal('exportModal')">
                            Batal
                        </button>
                        <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md transition duration-150 ease-in-out">
                            Export
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Modal functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('fixed')) {
                event.target.classList.add('hidden');
            }
        }

        // Add hover effect to table rows
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.classList.add('bg-gray-50');
            });
            row.addEventListener('mouseleave', function() {
                this.classList.remove('bg-gray-50');
            });
        });
    </script>
    @endpush
</x-app-layout> 