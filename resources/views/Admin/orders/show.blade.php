<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pesanan') }} #{{ $order->invoice_number }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.orders.edit', $order->id) }}" 
                    class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700">
                    <i class="fas fa-edit mr-2"></i> Edit Pesanan
                </a>
                <a href="{{ route('admin.pos.receipt', $order->id) }}" 
                    target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                    <i class="fas fa-print mr-2"></i> Cetak Faktur
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Informasi Pesanan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Informasi Pesanan</h3>
                            <div class="space-y-2">
                                <p><span class="font-medium">No. Faktur:</span> {{ $order->invoice_number }}</p>
                                <p><span class="font-medium">Tanggal:</span> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                <p><span class="font-medium">Status:</span> 
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($order->status == 'completed') bg-green-100 text-green-800
                                        @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Informasi Pelanggan</h3>
                            <div class="space-y-2">
                                <p><span class="font-medium">Nama:</span> {{ $order->customer->name }}</p>
                                <p><span class="font-medium">Email:</span> {{ $order->customer->email }}</p>
                                <p><span class="font-medium">Telepon:</span> {{ $order->customer->phone }}</p>
                                <p><span class="font-medium">Alamat:</span> {{ $order->customer->address }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Produk -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Detail Produk</h3>
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
                                    @foreach($order->details as $detail)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $detail->product->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            Rp {{ number_format($detail->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $detail->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right font-medium">Total:</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium">
                                            Rp {{ number_format($order->total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Update Status -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Update Status</h3>
                        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="max-w-md">
                            @csrf
                            @method('PUT')
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Diproses</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Update Status
                            </button>
                        </form>
                    </div>

                    <!-- History Status -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">History Status</h3>
                        <div class="space-y-4">
                            @foreach($order->logs()->where('action', 'like', '%status%')->latest()->get() as $log)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium">{{ $log->description }}</p>
                                        <p class="text-sm text-gray-600">
                                            Dari: <span class="font-medium">{{ ucfirst($log->old_data['status']) }}</span>
                                            Ke: <span class="font-medium">{{ ucfirst($log->new_data['status']) }}</span>
                                        </p>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $log->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 