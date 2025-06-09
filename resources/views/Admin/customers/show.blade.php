<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pelanggan') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.customers.edit', $customer) }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Edit
                </a>
                <a href="{{ route('admin.customers.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Informasi Dasar -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900">Informasi Dasar</h3>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $customer->nama }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $customer->email }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Telepon</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $customer->telepon }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $customer->alamat }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis</label>
                                <p class="mt-1 text-sm text-gray-900">{{ ucfirst($customer->jenis) }}</p>
                            </div>
                        </div>

                        <!-- Informasi Bank -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900">Informasi Bank</h3>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Bank</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $customer->nama_bank ?? '-' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pemegang Rekening</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $customer->pemegang_rekening ?? '-' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nomor Rekening</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $customer->nomor_rekening ?? '-' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Foto</label>
                                @if($customer->foto)
                                    <img src="{{ asset('storage/' . $customer->foto) }}" alt="Foto {{ $customer->nama }}" class="mt-2 h-32 w-32 object-cover rounded-lg">
                                @else
                                    <p class="mt-1 text-sm text-gray-500">Tidak ada foto</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Riwayat Transaksi -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Transaksi</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Transaksi</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($customer->orders as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $order->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $order->nomor_transaksi }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                Rp {{ number_format($order->total, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $order->status === 'selesai' ? 'bg-green-100 text-green-800' : 
                                                       ($order->status === 'proses' ? 'bg-yellow-100 text-yellow-800' : 
                                                       'bg-red-100 text-red-800') }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                Belum ada transaksi
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