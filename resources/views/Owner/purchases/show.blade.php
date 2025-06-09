<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pembelian') }} #{{ $purchase->invoice_number }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('owner.purchases.index') }}" 
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                @if($purchase->status === 'pending')
                <button onclick="showApprovalModal()"
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                    <i class="fas fa-check mr-2"></i> Setujui
                </button>
                <button onclick="showRejectionModal()"
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                    <i class="fas fa-times mr-2"></i> Tolak
                </button>
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
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $purchase->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $purchase->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $purchase->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($purchase->status) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Pembelian</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $purchase->purchase_date->format('d/m/Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Pemasok</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $purchase->supplier->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Catatan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $purchase->notes ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Persetujuan</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                @if($purchase->status === 'approved')
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Disetujui Oleh</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $purchase->approvedBy->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Persetujuan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $purchase->approved_at->format('d/m/Y H:i') }}</dd>
                                </div>
                                @elseif($purchase->status === 'rejected')
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Ditolak Oleh</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $purchase->rejectedBy->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Penolakan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $purchase->rejected_at->format('d/m/Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Alasan Penolakan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $purchase->rejection_reason }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Detail Produk -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Produk</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($purchase->details as $detail)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $detail->product->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $detail->quantity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
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

                    <!-- History Persetujuan -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">History Persetujuan</h3>
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @foreach($purchase->approvalHistory as $history)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                                    {{ $history->status === 'pending' ? 'bg-yellow-500' : '' }}
                                                    {{ $history->status === 'approved' ? 'bg-green-500' : '' }}
                                                    {{ $history->status === 'rejected' ? 'bg-red-500' : '' }}">
                                                    @if($history->status === 'pending')
                                                    <i class="fas fa-clock text-white"></i>
                                                    @elseif($history->status === 'approved')
                                                    <i class="fas fa-check text-white"></i>
                                                    @else
                                                    <i class="fas fa-times text-white"></i>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $history->status === 'pending' ? 'Pembelian dibuat' : '' }}
                                                        {{ $history->status === 'approved' ? 'Pembelian disetujui' : '' }}
                                                        {{ $history->status === 'rejected' ? 'Pembelian ditolak' : '' }}
                                                        oleh <span class="font-medium text-gray-900">{{ $history->createdBy->name }}</span>
                                                    </p>
                                                    @if($history->notes)
                                                    <p class="mt-1 text-sm text-gray-500">{{ $history->notes }}</p>
                                                    @endif
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    <time datetime="{{ $history->created_at }}">{{ $history->created_at->format('d/m/Y H:i') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Persetujuan -->
    <div id="approvalModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('owner.purchases.approve', $purchase) }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                    Konfirmasi Persetujuan
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Apakah Anda yakin ingin menyetujui pembelian ini?
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Setujui
                        </button>
                        <button type="button" onclick="hideApprovalModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Penolakan -->
    <div id="rejectionModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('owner.purchases.reject', $purchase) }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                    Alasan Penolakan
                                </h3>
                                <div class="mt-2">
                                    <textarea name="rejection_reason" rows="4" required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        placeholder="Masukkan alasan penolakan..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Tolak
                        </button>
                        <button type="button" onclick="hideRejectionModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function showApprovalModal() {
            document.getElementById('approvalModal').classList.remove('hidden');
        }

        function hideApprovalModal() {
            document.getElementById('approvalModal').classList.add('hidden');
        }

        function showRejectionModal() {
            document.getElementById('rejectionModal').classList.remove('hidden');
        }

        function hideRejectionModal() {
            document.getElementById('rejectionModal').classList.add('hidden');
        }
    </script>
    @endpush
</x-app-layout> 