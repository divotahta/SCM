<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Pesanan') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.orders.export.excel', request()->query()) }}" 
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                    <i class="fas fa-file-excel mr-2"></i> Export Excel
                </a>
                <a href="{{ route('admin.orders.export.pdf', request()->query()) }}" 
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Filters -->
                    <form action="{{ route('admin.orders.index') }}" method="GET" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                                <input type="date" name="date_from" value="{{ request('date_from') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                                <input type="date" name="date_to" value="{{ request('date_to') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pelanggan</label>
                                <select name="customer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Semua Pelanggan</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            <div class="flex-1 mr-4">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Cari nomor faktur atau nama pelanggan..."
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                <i class="fas fa-search mr-2"></i> Filter
                            </button>
                        </div>
                    </form>

                    <!-- Bulk Actions -->
                    <form id="bulk-action-form" action="{{ route('admin.orders.bulk-update') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="flex items-center space-x-4">
                            <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Pilih Status</option>
                                <option value="pending">Pending</option>
                                <option value="processing">Diproses</option>
                                <option value="completed">Selesai</option>
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Update Status Terpilih
                            </button>
                        </div>
                    </form>

                    <!-- Orders Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left">
                                        <input type="checkbox" id="select-all" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No. Faktur
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pelanggan
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($orders as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" name="order_ids[]" value="{{ $order->id }}" 
                                                class="order-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $order->invoice_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $order->customer->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            Rp {{ number_format($order->total, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($order->status == 'completed') bg-green-100 text-green-800
                                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                                @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.orders.show', $order->id) }}" 
                                                    class="text-indigo-600 hover:text-indigo-900">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.orders.edit', $order->id) }}" 
                                                    class="text-yellow-600 hover:text-yellow-900">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.pos.receipt', $order->id) }}" 
                                                    target="_blank"
                                                    class="text-green-600 hover:text-green-900">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                <form action="{{ route('admin.orders.destroy', $order->id) }}" 
                                                    method="POST" 
                                                    class="inline-block"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            Tidak ada data pesanan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all checkbox
            const selectAll = document.getElementById('select-all');
            const orderCheckboxes = document.querySelectorAll('.order-checkbox');

            selectAll.addEventListener('change', function() {
                orderCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            // Bulk action form
            const bulkActionForm = document.getElementById('bulk-action-form');
            bulkActionForm.addEventListener('submit', function(e) {
                const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
                if (checkedBoxes.length === 0) {
                    e.preventDefault();
                    alert('Pilih minimal satu pesanan');
                    return;
                }

                const status = this.querySelector('select[name="status"]').value;
                if (!status) {
                    e.preventDefault();
                    alert('Pilih status yang akan diupdate');
                    return;
                }
            });
        });
    </script>
    @endpush
</x-app-layout> 