<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pemasok') }}
            </h2>

    </x-slot>


    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex space-x-2">
            <a href="{{ route('admin.suppliers.edit', $supplier) }}"
                class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Edit
            </a>
            <a href="{{ route('admin.suppliers.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Kembali
            </a>
        </div>
    </div>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informasi Dasar -->
                <div class="space-y-6">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Dasar</h3>

                    <div class="flex items-center space-x-4">
                        @if ($supplier->foto)
                            <img src="{{ Storage::url($supplier->foto) }}" alt="{{ $supplier->nama }}"
                                class="h-32 w-32 object-cover rounded-lg">
                        @else
                            <div class="h-32 w-32 rounded-lg bg-gray-200 flex items-center justify-center">
                                <span class="text-4xl text-gray-500">{{ substr($supplier->nama, 0, 1) }}</span>
                            </div>
                        @endif
                        <div>
                            <h4 class="text-xl font-semibold text-gray-900">{{ $supplier->nama }}</h4>
                            <p class="text-sm text-gray-500">{{ $supplier->nama_toko }}</p>
                            <span
                                class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $supplier->jenis === 'distributor' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($supplier->jenis) }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Email</h5>
                            <p class="mt-1 text-sm text-gray-900">{{ $supplier->email }}</p>
                        </div>

                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Telepon</h5>
                            <p class="mt-1 text-sm text-gray-900">{{ $supplier->telepon }}</p>
                        </div>

                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Alamat</h5>
                            <p class="mt-1 text-sm text-gray-900">{{ $supplier->alamat }}</p>
                        </div>
                    </div>
                </div>

                <!-- Informasi Bank -->
                <div class="space-y-6">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Bank</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Nama Bank</h5>
                            <p class="mt-1 text-sm text-gray-900">{{ $supplier->nama_bank ?: '-' }}</p>
                        </div>

                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Pemegang Rekening</h5>
                            <p class="mt-1 text-sm text-gray-900">{{ $supplier->pemegang_rekening ?: '-' }}</p>
                        </div>

                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Nomor Rekening</h5>
                            <p class="mt-1 text-sm text-gray-900">{{ $supplier->nomor_rekening ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Pembelian -->
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Pembelian</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No. Pembelian</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($supplier->purchases as $purchase)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $purchase->tanggal_pembelian->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->nomor_pembelian }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp
                                        {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @switch($purchase->status_pembelian)
                                            @case('pending')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Menunggu
                                                </span>
                                            @break

                                            @case('completed')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Selesai
                                                </span>
                                            @break

                                            @case('cancelled')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Dibatalkan
                                                </span>
                                            @break
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.purchases.show', $purchase->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Belum ada riwayat pembelian
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    </x-app-layout>
