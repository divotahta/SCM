<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pembelian') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.purchases.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
                @if($purchase->status_pembelian == 'draft')
                    <a href="{{ route('admin.purchases.edit', $purchase) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        Edit
                    </a>
                    <form action="{{ route('admin.purchases.destroy', $purchase) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Apakah Anda yakin ingin menghapus pembelian ini?')">
                            Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Informasi Pembelian -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pembelian</h3>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">No. Pembelian:</span>
                                    <span class="ml-2">{{ $purchase->nomor_pembelian }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Tanggal:</span>
                                    <span class="ml-2">{{ $purchase->tanggal_pembelian->format('d/m/Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Status:</span>
                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($purchase->status_pembelian == 'draft') bg-gray-100 text-gray-800
                                        @elseif($purchase->status_pembelian == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($purchase->status_pembelian == 'approved') bg-green-100 text-green-800
                                        @elseif($purchase->status_pembelian == 'rejected') bg-red-100 text-red-800
                                        @else bg-blue-100 text-blue-800
                                        @endif">
                                        {{ ucfirst($purchase->status_pembelian) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pemasok</h3>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Nama:</span>
                                    <span class="ml-2">{{ $purchase->supplier->nama }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Email:</span>
                                    <span class="ml-2">{{ $purchase->supplier->email }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Telepon:</span>
                                    <span class="ml-2">{{ $purchase->supplier->telepon }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Alamat:</span>
                                    <span class="ml-2">{{ $purchase->supplier->alamat }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Produk -->
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Produk</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Satuan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($purchase->details as $detail)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $detail->product->nama_produk }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $detail->jumlah }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right font-medium">Total:</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium">
                                            Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Catatan -->
                    @if($purchase->catatan)
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Catatan</h3>
                            <p class="text-gray-600">{{ $purchase->catatan }}</p>
                        </div>
                    @endif

                    <!-- Status Approval -->
                    @if($purchase->status_pembelian != 'draft')
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Status Approval</h3>
                            <div class="space-y-4">
                                @if($purchase->disetujui_oleh)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Disetujui oleh:</span>
                                        <span class="ml-2">{{ $purchase->approvedBy->name }}</span>
                                        <span class="ml-2 text-sm text-gray-500">pada {{ $purchase->disetujui_pada->format('d/m/Y H:i') }}</span>
                                    </div>
                                @endif
                                @if($purchase->ditolak_oleh)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Ditolak oleh:</span>
                                        <span class="ml-2">{{ $purchase->rejectedBy->name }}</span>
                                        <span class="ml-2 text-sm text-gray-500">pada {{ $purchase->ditolak_pada->format('d/m/Y H:i') }}</span>
                                        @if($purchase->alasan_penolakan)
                                            <div class="mt-1 text-sm text-red-600">
                                                Alasan: {{ $purchase->alasan_penolakan }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                @if($purchase->diterima_oleh)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Diterima oleh:</span>
                                        <span class="ml-2">{{ $purchase->receivedBy->name }}</span>
                                        <span class="ml-2 text-sm text-gray-500">pada {{ $purchase->diterima_pada->format('d/m/Y H:i') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 