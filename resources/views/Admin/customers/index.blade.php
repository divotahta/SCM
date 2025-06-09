<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pelanggan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header Buttons -->
                    <div class="flex justify-end space-x-2 mb-4">
                        <button type="button" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition duration-150 ease-in-out" onclick="openModal('importModal')">
                            <i class="fas fa-file-import"></i> Import
                        </button>
                        <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-150 ease-in-out" onclick="openModal('exportModal')">
                            <i class="fas fa-file-export"></i> Export
                        </button>
                        <button type="button" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md transition duration-150 ease-in-out" onclick="openModal('broadcastModal')">
                            <i class="fab fa-whatsapp"></i> Broadcast
                        </button>
                        <a href="{{ route('admin.customers.create') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md transition duration-150 ease-in-out">
                            <i class="fas fa-plus"></i> Tambah Pelanggan
                        </a>
                    </div>

                    <!-- Search Form -->
                    <form action="{{ route('admin.customers.index') }}" method="GET" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div>
                                <input type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    name="search" placeholder="Cari pelanggan..." value="{{ request('search') }}">
                            </div>
                            <div>
                                <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="loyalty_level">
                                    <option value="">Semua Level</option>
                                    <option value="bronze" {{ request('loyalty_level') == 'bronze' ? 'selected' : '' }}>Bronze</option>
                                    <option value="silver" {{ request('loyalty_level') == 'silver' ? 'selected' : '' }}>Silver</option>
                                    <option value="gold" {{ request('loyalty_level') == 'gold' ? 'selected' : '' }}>Gold</option>
                                    <option value="platinum" {{ request('loyalty_level') == 'platinum' ? 'selected' : '' }}>Platinum</option>
                                </select>
                            </div>
                            <div>
                                <input type="number" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    name="min_points" placeholder="Min. Poin" value="{{ request('min_points') }}">
                            </div>
                            <div>
                                <input type="number" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    name="min_purchase" placeholder="Min. Pembelian" value="{{ request('min_purchase') }}">
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <input type="checkbox" id="select-all" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poin</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pembelian</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembelian Terakhir</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($customers as $customer)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" class="customer-select rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                                value="{{ $customer->id }}">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $customer->code }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $customer->nama }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($customer->telepon)
                                                <a href="https://wa.me/{{ $customer->telepon }}" target="_blank" class="text-green-600 hover:text-green-900 transition duration-150 ease-in-out">
                                                    <i class="fab fa-whatsapp"></i> {{ $customer->telepon }}
                                                </a><br>
                                            @endif
                                            @if($customer->email)
                                                <a href="mailto:{{ $customer->email }}" class="text-blue-600 hover:text-blue-900 transition duration-150 ease-in-out">
                                                    <i class="fas fa-envelope"></i> {{ $customer->email }}
                                                </a>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($customer->points) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $customer->loyalty_level == 'bronze' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $customer->loyalty_level == 'silver' ? 'bg-gray-100 text-gray-800' : '' }}
                                                {{ $customer->loyalty_level == 'gold' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $customer->loyalty_level == 'platinum' ? 'bg-blue-100 text-blue-800' : '' }}">
                                                {{ ucfirst($customer->loyalty_level) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($customer->total_purchase, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $customer->last_purchase_at ? $customer->last_purchase_at->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($customer->outstanding_payment > 0)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Rp {{ number_format($customer->outstanding_payment, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Lunas
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.customers.show', $customer) }}" class="text-blue-600 hover:text-blue-900 mr-3 transition duration-150 ease-in-out">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.customers.edit', $customer) }}" class="text-yellow-600 hover:text-yellow-900 mr-3 transition duration-150 ease-in-out">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out" onclick="return confirm('Yakin ingin menghapus?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            Tidak ada data
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div id="importModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Import Data Pelanggan</h3>
                <form action="{{ route('admin.customers.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">File Excel/CSV</label>
                        <input type="file" name="file" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required accept=".xlsx,.csv">
                        <small class="text-gray-500">Format: Excel (.xlsx) atau CSV (.csv)</small>
                    </div>
                    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4">
                            <i class="fas fa-info-circle"></i> 
                            Download template Excel 
                            <a href="{{ route('admin.customers.export', ['format' => 'excel']) }}" class="text-blue-700 hover:text-blue-900 underline">
                                di sini
                            </a>
                        </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-150 ease-in-out" onclick="closeModal('importModal')">
                            Batal
                        </button>
                        <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md transition duration-150 ease-in-out">
                            Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div id="exportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Export Data Pelanggan</h3>
                <form action="{{ route('admin.customers.export') }}" method="GET">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Format</label>
                            <select name="format" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="excel">Excel</option>
                                <option value="pdf">PDF</option>
                            </select>
                        </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-150 ease-in-out" onclick="closeModal('exportModal')">
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

    <!-- Broadcast Modal -->
    <div id="broadcastModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Broadcast WhatsApp</h3>
                <form action="{{ route('admin.customers.broadcast') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Pelanggan</label>
                            <select name="customers[]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 select2" multiple required>
                                @foreach($customers as $customer)
                                    @if($customer->telepon)
                                        <option value="{{ $customer->id }}">
                                            {{ $customer->nama }} ({{ $customer->telepon }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pesan</label>
                            <textarea name="message" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" rows="5" required></textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-150 ease-in-out" onclick="closeModal('broadcastModal')">
                            Batal
                        </button>
                        <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md transition duration-150 ease-in-out">
                            Kirim
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

        // Select All functionality
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.getElementsByClassName('customer-select');
            for (let checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        });

        // Initialize Select2
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $.fn.select2 !== 'undefined') {
        $('.select2').select2({
            theme: 'bootstrap4',
                    placeholder: 'Pilih pelanggan...',
                    width: '100%'
                });
            }
        });

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