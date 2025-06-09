<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Pesanan') }} #{{ $order->invoice_number }}
            </h2>
            <a href="{{ route('admin.orders.show', $order->id) }}" 
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" id="orderForm">
                        @csrf
                        @method('PUT')
                        
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
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200" id="orderDetails">
                                        @foreach($order->details as $detail)
                                        <tr data-detail-id="{{ $detail->id }}">
                                            <td class="px-6 py-4">
                                                {{ $detail->product->name }}
                                            </td>
                                            <td class="px-6 py-4">
                                                Rp {{ number_format($detail->price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <input type="number" 
                                                    name="quantities[{{ $detail->id }}]" 
                                                    value="{{ $detail->quantity }}"
                                                    min="1"
                                                    class="w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                    onchange="updateSubtotal(this)">
                                            </td>
                                            <td class="px-6 py-4 subtotal">
                                                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <button type="button" 
                                                    onclick="removeDetail(this)"
                                                    class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-right font-medium">Total:</td>
                                            <td class="px-6 py-4 whitespace-nowrap font-medium" id="totalAmount">
                                                Rp {{ number_format($order->total, 0, ',', '.') }}
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function updateSubtotal(input) {
            const row = input.closest('tr');
            const price = parseFloat(row.querySelector('td:nth-child(2)').textContent.replace(/[^0-9.-]+/g, ''));
            const quantity = parseInt(input.value);
            const subtotal = price * quantity;
            
            row.querySelector('.subtotal').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
            updateTotal();
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.subtotal').forEach(cell => {
                total += parseFloat(cell.textContent.replace(/[^0-9.-]+/g, ''));
            });
            
            document.getElementById('totalAmount').textContent = `Rp ${total.toLocaleString('id-ID')}`;
        }

        function removeDetail(button) {
            if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                const row = button.closest('tr');
                row.remove();
                updateTotal();
            }
        }

        document.getElementById('orderForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const details = [];
            
            document.querySelectorAll('#orderDetails tr').forEach(row => {
                const detailId = row.dataset.detailId;
                const quantity = row.querySelector('input[type="number"]').value;
                
                details.push({
                    id: detailId,
                    quantity: quantity
                });
            });
            
            formData.append('details', JSON.stringify(details));
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan perubahan');
            });
        });
    </script>
    @endpush
</x-app-layout> 