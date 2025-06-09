<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Point of Sale') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left Column - Product Search & Cart -->
                        <div class="lg:col-span-2">
                            <!-- Product Search -->
                            <div class="mb-6">
                                <div class="relative">
                                    <input type="text" id="product-search" 
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        placeholder="Cari produk (nama atau kode)...">
                                    <div id="search-results" class="absolute z-10 w-full mt-1 bg-white rounded-lg shadow-lg hidden">
                                    </div>
                                </div>
                            </div>

                            <!-- Cart -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-lg font-semibold mb-4">Keranjang</h3>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cart-items">
                                            <!-- Cart items will be added here dynamically -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Customer & Payment -->
                        <div class="lg:col-span-1">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <!-- Customer Selection -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pelanggan</label>
                                    <select id="customer-select" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">Pilih Pelanggan</option>
                                    </select>
                                </div>

                                <!-- Payment Summary -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold mb-4">Ringkasan Pembayaran</h3>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span>Subtotal:</span>
                                            <span id="subtotal">Rp 0</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>PPN (11%):</span>
                                            <span id="tax">Rp 0</span>
                                        </div>
                                        <div class="flex justify-between font-semibold">
                                            <span>Total:</span>
                                            <span id="total">Rp 0</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                                    <select id="payment-method" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="cash">Cash</option>
                                        <option value="transfer">Transfer</option>
                                        <option value="qris">QRIS</option>
                                    </select>
                                </div>

                                <!-- Payment Amount -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Bayar</label>
                                    <input type="number" id="payment-amount" 
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        placeholder="Masukkan jumlah bayar">
                                </div>

                                <!-- Change -->
                                <div class="mb-6">
                                    <div class="flex justify-between font-semibold">
                                        <span>Kembalian:</span>
                                        <span id="change">Rp 0</span>
                                    </div>
                                </div>

                                <!-- Process Button -->
                                <button id="process-payment" 
                                    class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Proses Pembayaran
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Item Template -->
    <template id="cart-item-template">
        <tr class="cart-item">
            <td class="px-4 py-2">
                <span class="product-name"></span>
                <input type="hidden" class="product-id">
            </td>
            <td class="px-4 py-2">
                <span class="product-price"></span>
            </td>
            <td class="px-4 py-2">
                <div class="flex items-center space-x-2">
                    <button class="decrease-qty px-2 py-1 bg-gray-200 rounded hover:bg-gray-300">-</button>
                    <input type="number" class="product-qty w-16 text-center rounded border-gray-300" min="1">
                    <button class="increase-qty px-2 py-1 bg-gray-200 rounded hover:bg-gray-300">+</button>
                </div>
            </td>
            <td class="px-4 py-2">
                <span class="product-subtotal"></span>
            </td>
            <td class="px-4 py-2">
                <button class="remove-item text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    </template>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cart = [];
            const TAX_RATE = 0.11;

            // Load customers
            fetch('/pos/customers')
                .then(response => response.json())
                .then(customers => {
                    const select = document.getElementById('customer-select');
                    customers.forEach(customer => {
                        const option = document.createElement('option');
                        option.value = customer.id;
                        option.textContent = customer.name;
                        select.appendChild(option);
                    });
                });

            // Product search
            const searchInput = document.getElementById('product-search');
            const searchResults = document.getElementById('search-results');
            let searchTimeout;

            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value;

                if (query.length < 2) {
                    searchResults.classList.add('hidden');
                    return;
                }

                searchTimeout = setTimeout(() => {
                    fetch(`/pos/search-products?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(products => {
                            searchResults.innerHTML = '';
                            products.forEach(product => {
                                const div = document.createElement('div');
                                div.className = 'p-2 hover:bg-gray-100 cursor-pointer';
                                div.textContent = `${product.name} (${product.code}) - Rp ${formatNumber(product.price)}`;
                                div.addEventListener('click', () => addToCart(product));
                                searchResults.appendChild(div);
                            });
                            searchResults.classList.remove('hidden');
                        });
                }, 300);
            });

            // Add to cart
            function addToCart(product) {
                const existingItem = cart.find(item => item.id === product.id);
                if (existingItem) {
                    existingItem.quantity++;
                } else {
                    cart.push({
                        id: product.id,
                        name: product.name,
                        price: product.price,
                        quantity: 1
                    });
                }
                updateCart();
                searchInput.value = '';
                searchResults.classList.add('hidden');
            }

            // Update cart
            function updateCart() {
                const tbody = document.getElementById('cart-items');
                tbody.innerHTML = '';
                const template = document.getElementById('cart-item-template');

                cart.forEach((item, index) => {
                    const clone = template.content.cloneNode(true);
                    clone.querySelector('.product-name').textContent = item.name;
                    clone.querySelector('.product-id').value = item.id;
                    clone.querySelector('.product-price').textContent = `Rp ${formatNumber(item.price)}`;
                    clone.querySelector('.product-qty').value = item.quantity;
                    clone.querySelector('.product-subtotal').textContent = `Rp ${formatNumber(item.price * item.quantity)}`;

                    // Quantity controls
                    clone.querySelector('.decrease-qty').addEventListener('click', () => {
                        if (item.quantity > 1) {
                            item.quantity--;
                            updateCart();
                        }
                    });

                    clone.querySelector('.increase-qty').addEventListener('click', () => {
                        item.quantity++;
                        updateCart();
                    });

                    clone.querySelector('.product-qty').addEventListener('change', (e) => {
                        const newQty = parseInt(e.target.value);
                        if (newQty > 0) {
                            item.quantity = newQty;
                            updateCart();
                        }
                    });

                    // Remove item
                    clone.querySelector('.remove-item').addEventListener('click', () => {
                        cart.splice(index, 1);
                        updateCart();
                    });

                    tbody.appendChild(clone);
                });

                updateTotals();
            }

            // Update totals
            function updateTotals() {
                const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                const tax = subtotal * TAX_RATE;
                const total = subtotal + tax;

                document.getElementById('subtotal').textContent = `Rp ${formatNumber(subtotal)}`;
                document.getElementById('tax').textContent = `Rp ${formatNumber(tax)}`;
                document.getElementById('total').textContent = `Rp ${formatNumber(total)}`;

                // Update change
                const paymentAmount = parseFloat(document.getElementById('payment-amount').value) || 0;
                const change = paymentAmount - total;
                document.getElementById('change').textContent = `Rp ${formatNumber(Math.max(0, change))}`;
            }

            // Payment amount change
            document.getElementById('payment-amount').addEventListener('input', updateTotals);

            // Process payment
            document.getElementById('process-payment').addEventListener('click', function() {
                const customerId = document.getElementById('customer-select').value;
                const paymentMethod = document.getElementById('payment-method').value;
                const paymentAmount = parseFloat(document.getElementById('payment-amount').value) || 0;
                const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0) * (1 + TAX_RATE);

                if (!customerId) {
                    alert('Pilih pelanggan terlebih dahulu');
                    return;
                }

                if (cart.length === 0) {
                    alert('Keranjang masih kosong');
                    return;
                }

                if (paymentAmount < total) {
                    alert('Jumlah pembayaran kurang');
                    return;
                }

                const data = {
                    customer_id: customerId,
                    payment_method: paymentMethod,
                    payment_amount: paymentAmount,
                    total: total,
                    items: cart.map(item => ({
                        id: item.id,
                        quantity: item.quantity,
                        price: item.price
                    }))
                };

                fetch('/pos/process-transaction', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        window.open(`/pos/receipt/${result.transaction_id}`, '_blank');
                        cart.length = 0;
                        updateCart();
                        document.getElementById('payment-amount').value = '';
                        document.getElementById('customer-select').value = '';
                    } else {
                        alert(result.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memproses pembayaran');
                });
            });

            // Helper function to format numbers
            function formatNumber(number) {
                return number.toLocaleString('id-ID');
            }
        });
    </script>
    @endpush
</x-app-layout> 