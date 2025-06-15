<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Pembelian
            </h2>

        </div>
    </x-slot>


    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex space-x-2">
            <a href="{{ route('admin.purchases.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            @if ($purchase->status_pembelian === 'pending')
                <a href="{{ route('admin.purchases.edit', $purchase) }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
            @endif
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- Informasi Pembelian -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pembelian</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Nomor Pembelian</p>
                            <p class="text-base font-medium">{{ $purchase->nomor_pembelian }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Pembelian</p>
                            <p class="text-base font-medium">{{ $purchase->tanggal_pembelian->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Supplier</p>
                            <p class="text-base font-medium">{{ $purchase->supplier->nama }} -
                                {{ $purchase->supplier->nama_toko }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <p class="text-base font-medium">
                                @switch($purchase->status_pembelian)
                                    @case('pending')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                                    @break

                                    @case('approved')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>
                                    @break

                                    @case('rejected')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                    @break

                                    @case('received')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Diterima</span>
                                    @break
                                @endswitch
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Informasi Persetujuan -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Persetujuan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Dibuat Oleh</p>
                            <p class="text-base font-medium">{{ $purchase->createdBy->name ?? '-' }}</p>
                        </div>
                        @if ($purchase->status_pembelian === 'approved' || $purchase->status_pembelian === 'received')
                            <div>
                                <p class="text-sm text-gray-600">Disetujui Oleh</p>
                                <p class="text-base font-medium">{{ $purchase->approvedBy->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Persetujuan</p>
                                <p class="text-base font-medium">
                                    {{ $purchase->disetujui_pada ? $purchase->disetujui_pada->format('d/m/Y H:i') : '-' }}
                                </p>
                            </div>
                        @endif
                        @if ($purchase->status_pembelian === 'rejected')
                            <div>
                                <p class="text-sm text-gray-600">Ditolak Oleh</p>
                                <p class="text-base font-medium">{{ $purchase->rejectedBy->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Penolakan</p>
                                <p class="text-base font-medium">
                                    {{ $purchase->ditolak_pada ? $purchase->ditolak_pada->format('d/m/Y H:i') : '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Alasan Penolakan</p>
                                <p class="text-base font-medium">{{ $purchase->alasan_penolakan ?? '-' }}</p>
                            </div>
                        @endif
                        @if ($purchase->status_pembelian === 'received')
                            <div>
                                <p class="text-sm text-gray-600">Diterima Oleh</p>
                                <p class="text-base font-medium">{{ $purchase->receivedBy->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Penerimaan</p>
                                <p class="text-base font-medium">
                                    {{ $purchase->diterima_pada ? $purchase->diterima_pada->format('d/m/Y H:i') : '-' }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Detail Item -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Item</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Produk</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Harga Satuan</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($purchase->details as $detail)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $detail->product->nama_produk }}</div>
                                            <div class="text-sm text-gray-500">{{ $detail->product->kode_produk }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $detail->jumlah }}
                                                {{ $detail->product->unit->nama }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">Rp
                                                {{ number_format($detail->harga_satuan, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">Rp
                                                {{ number_format($detail->total, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $detail->catatan ?? '-' }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                        Total</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Catatan -->
                @if ($purchase->catatan)
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Catatan</h3>
                        <p class="text-sm text-gray-600">{{ $purchase->catatan }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>

    <!-- Modal Tolak Pembelian -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden" x-data="{ show: false }" x-show="show"
        @click.away="show = false">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Pembelian</h3>
                <form action="{{ route('admin.purchases.reject', $purchase) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="alasan_penolakan" class="block text-sm font-medium text-gray-700">Alasan
                            Penolakan</label>
                        <textarea name="alasan_penolakan" id="alasan_penolakan" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required></textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="hideRejectModal()"
                            class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                            Batal
                        </button>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                            Tolak Pembelian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function showRejectModal() {
                document.getElementById('rejectModal').classList.remove('hidden');
            }

            function hideRejectModal() {
                document.getElementById('rejectModal').classList.add('hidden');
            }
        </script>
    @endpush
</x-app-layout>
