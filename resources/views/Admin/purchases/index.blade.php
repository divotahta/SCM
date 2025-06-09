<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Pembelian') }}
            </h2>
        </div>
    </x-slot>


    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <a href="{{ route('admin.purchases.create') }}"
            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Tambah Pembelian
        </a>

        <div class="p-6 bg-white border-b border-gray-200">

            <!-- Filter -->
            <div class="mb-4">
                <form action="{{ route('admin.purchases.index') }}" method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nomor pembelian atau pemasok..."
                            class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <select name="status"
                            class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak
                            </option>
                            <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Diterima
                            </option>
                        </select>
                    </div>
                    <div>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <button type="submit"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Status Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4">
                    <p class="font-bold">Draft</p>
                    <p class="text-2xl">{{ $draftCount }}</p>
                </div>
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4">
                    <p class="font-bold">Pending</p>
                    <p class="text-2xl">{{ $pendingCount }}</p>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No. Pembelian</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pemasok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($purchases as $purchase)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->nomor_pembelian }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $purchase->tanggal_pembelian->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->supplier->nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">Rp
                                    {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if ($purchase->status_pembelian == 'draft') bg-gray-100 text-gray-800
                                                @elseif($purchase->status_pembelian == 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($purchase->status_pembelian == 'approved') bg-green-100 text-green-800
                                                @elseif($purchase->status_pembelian == 'rejected') bg-red-100 text-red-800
                                                @else bg-blue-100 text-blue-800 @endif">
                                        {{ ucfirst($purchase->status_pembelian) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.purchases.show', $purchase) }}"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3">Detail</a>
                                    @if ($purchase->status_pembelian == 'draft')
                                        <a href="{{ route('admin.purchases.edit', $purchase) }}"
                                            class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                                        <form action="{{ route('admin.purchases.destroy', $purchase) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus pembelian ini?')">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                    Tidak ada data pembelian
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $purchases->links() }}
            </div>
        </div>
    </div>
    </div>
    </div>
</x-app-layout>
