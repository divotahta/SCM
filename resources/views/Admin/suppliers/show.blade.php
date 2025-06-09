<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pemasok') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700">
                    Edit
                </a>
                <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700" onclick="return confirm('Apakah Anda yakin ingin menghapus pemasok ini?')">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Informasi Dasar -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">Informasi Dasar</h3>
                            
                            <div class="flex items-center space-x-4">
                                @if($supplier->foto)
                                    <img src="{{ Storage::url($supplier->foto) }}" alt="{{ $supplier->nama }}" class="h-24 w-24 object-cover rounded-lg">
                                @else
                                    <div class="h-24 w-24 rounded-lg bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500 text-2xl">{{ substr($supplier->nama, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <h4 class="text-xl font-semibold">{{ $supplier->nama }}</h4>
                                    <p class="text-gray-600">{{ $supplier->nama_toko }}</p>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $supplier->jenis === 'distributor' ? 'bg-purple-100 text-purple-800' : 
                                           ($supplier->jenis === 'grosir' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                        {{ ucfirst($supplier->jenis) }}
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Email:</span>
                                    <p>{{ $supplier->email }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Telepon:</span>
                                    <p>{{ $supplier->telepon }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Alamat:</span>
                                    <p>{{ $supplier->alamat }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Bank -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">Informasi Bank</h3>
                            
                            <div class="space-y-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Nama Bank:</span>
                                    <p>{{ $supplier->nama_bank ?? '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Pemegang Rekening:</span>
                                    <p>{{ $supplier->pemegang_rekening ?? '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Nomor Rekening:</span>
                                    <p>{{ $supplier->nomor_rekening ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('admin.suppliers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 