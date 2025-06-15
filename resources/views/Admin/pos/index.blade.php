<x-app-layout>
    <div class="min-h-screen bg-gray-100">
        <!-- Header -->
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900">Point of Sale</h1>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" id="search" placeholder="Cari produk..."
                                class="w-64 pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <div class="absolute left-3 top-2.5">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-12 gap-6">
                <!-- Products Section -->
                <div class="col-span-8">
                    <!-- Categories -->
                    <div class="mb-6">
                        <div class="flex space-x-2 overflow-x-auto pb-2">
                            <button
                                class="category-button px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                data-category="all">
                                Semua
                            </button>
                            @foreach ($categories as $categoryName => $categoryProducts)
                                <button
                                    class="category-button px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                                    data-category="{{ strtolower(str_replace(' ', '-', $categoryName)) }}">
                                    {{ $categoryName }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach ($products as $product)
                            <div class="product-card p-4 bg-white rounded-lg border hover:shadow-md transition-all duration-200"
                                data-category="{{ strtolower(str_replace(' ', '-', $product->category->nama_kategori)) }}">
                                <div class="aspect-w-1 aspect-h-1 mb-3 bg-gray-100 rounded-lg overflow-hidden">
                                    @if ($product->gambar)
                                        <img src="{{ asset('storage/' . $product->gambar) }}"
                                            alt="{{ $product->nama_produk }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <h3 class="product-name text-lg font-medium text-gray-900 truncate">
                                    {{ $product->nama_produk }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ $product->category->nama_kategori }}</p>
                                <div class="mt-2 flex items-center justify-between">
                                    <span class="text-lg font-medium text-gray-900">Rp
                                        {{ number_format($product->harga_jual, 0, ',', '.') }}</span>
                                    <button
                                        onclick="addToCart({{ $product->id }}, '{{ addslashes($product->nama_produk) }}', {{ $product->harga_jual }}, {{ $product->stok }})"
                                        class="ml-2 rounded-md bg-blue-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                                        + Keranjang
                                    </button>
                                </div>
                                <div class="mt-2 text-sm text-gray-500">
                                    Stok: {{ $product->stok }} {{ $product->unit->nama_unit }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Cart Section -->
                <div class="col-span-4">
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="p-4 border-b">
                            <h2 class="text-lg font-semibold text-gray-900">Keranjang Belanja</h2>
                        </div>

                        <!-- Cart Items -->
                        <div class="p-4 space-y-4 max-h-[calc(100vh-400px)] overflow-y-auto cart-items">
                            <!-- Cart items will be updated via JavaScript -->
                        </div>

                        <!-- Cart Summary -->
                        <div class="p-4 border-t bg-gray-50">
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium subtotal">Rp 0</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">PPN (11%)</span>
                                    <span class="font-medium tax">Rp 0</span>
                                </div>
                                <div class="flex justify-between text-lg font-semibold">
                                    <span>Total</span>
                                    <span class="total">Rp 0</span>
                                </div>
                            </div>

                            <!-- Payment Form -->
                            <form id="checkoutForm" class="mt-4 space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                                    <select name="payment_method"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="cash">Tunai</option>
                                        <option value="card">Kartu</option>
                                        <option value="qris">QRIS</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Pelanggan</label>
                                    <div class="mt-1 space-y-2">
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="existing_customer" name="customer_type"
                                                value="existing" checked
                                                class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                            <label for="existing_customer" class="text-sm text-gray-700">Pelanggan yang
                                                sudah ada</label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="new_customer" name="customer_type" value="new"
                                                class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                            <label for="new_customer" class="text-sm text-gray-700">Pelanggan
                                                baru</label>
                                        </div>

                                        <div id="existing_customer_select">
                                            <select name="customer_id"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div id="new_customer_input" class="hidden">
                                            <input type="text" name="new_customer_name"
                                                placeholder="Nama pelanggan baru"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Catatan</label>
                                    <textarea name="notes" rows="2"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Tambahkan catatan untuk pesanan ini..."></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jumlah Bayar</label>
                                    <input type="number" name="amount_paid"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        step="0.01" min="0" required>
                                </div>

                                <button type="submit"
                                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Proses Pembayaran
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- @push('styles') --}}
    <style>
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .category-button.active {
            background-color: #2563eb;
            color: white;
        }

        .product-card {
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-2px);
        }

        /* Custom scrollbar */
        .cart-items::-webkit-scrollbar {
            width: 6px;
        }

        .cart-items::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .cart-items::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        .cart-items::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
    {{-- @endpush --}}

    {{-- @push('scripts') --}}
    <script>
        // Cart management
        let cart = [];

        // Format currency function
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount).replace('IDR', 'Rp');
        }

        // Define functions globally first
        window.addToCart = function(productId, name, price, stock) {
            const existingItem = cart.find(item => item.id === productId);

            if (existingItem) {
                if (existingItem.quantity >= stock) {
                    alert('Stok tidak mencukupi!');
                    return;
                }
                existingItem.quantity++;
            } else {
                cart.push({
                    id: productId,
                    name: name,
                    price: price,
                    quantity: 1,
                    stock: stock
                });
            }

            updateCart();
        }

        // Remove from cart function
        window.removeFromCart = function(productId) {
            cart = cart.filter(item => item.id !== productId);
            updateCart();
        }

        // Update quantity function
        window.updateQuantity = function(productId, newQuantity) {
            const item = cart.find(item => item.id === productId);
            if (item) {
                if (newQuantity <= 0) {
                    removeFromCart(productId);
                } else if (newQuantity > item.stock) {
                    alert('Stok tidak mencukupi!');
                } else {
                    item.quantity = newQuantity;
                    updateCart();
                }
            }
        }

        // Update cart display
        function updateCart() {
            const cartItemsContainer = document.querySelector('.cart-items');
            cartItemsContainer.innerHTML = '';

            cart.forEach(item => {
                const itemElement = document.createElement('div');
                itemElement.className = 'flex items-center justify-between p-2 bg-white rounded-lg shadow-sm';
                itemElement.innerHTML = `
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-gray-900">${item.name}</h4>
                        <div class="mt-1 flex items-center space-x-2">
                            <input type="number" 
                                   value="${item.quantity}" 
                                   min="1" 
                                   max="${item.stock}"
                                   onchange="updateQuantity(${item.id}, this.value)"
                                   class="w-16 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <span class="text-sm text-gray-500">x ${formatCurrency(item.price)}</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium">${formatCurrency(item.price * item.quantity)}</span>
                        <button onclick="removeFromCart(${item.id})" class="text-red-600 hover:text-red-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                `;
                cartItemsContainer.appendChild(itemElement);
            });

            updateTotals();
        }

        // Fungsi untuk memperbarui total
        function updateTotals() {
            const subtotalElement = document.querySelector('.subtotal');
            const taxElement = document.querySelector('.tax');
            const totalElement = document.querySelector('.total');

            // Hitung total
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = subtotal * 0.11;
            const total = subtotal + tax;

            // Update tampilan total
            subtotalElement.textContent = formatCurrency(subtotal);
            taxElement.textContent = formatCurrency(tax);
            totalElement.textContent = formatCurrency(total);

            // Update input jumlah bayar
            const amountPaidInput = document.querySelector('input[name="amount_paid"]');
            if (amountPaidInput && total > 0) {
                amountPaidInput.value = total;
            }

            // Update status tombol checkout
            const checkoutButton = document.querySelector('#checkoutForm button[type="submit"]');
            if (checkoutButton) {
                checkoutButton.disabled = cart.length === 0;
            }
        }

        // Search functionality
        function initializeSearch() {
            const searchInput = document.getElementById('search');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    const productCards = document.querySelectorAll('.product-card');

                    productCards.forEach(card => {
                        const productName = card.querySelector('.product-name');
                        if (productName) {
                            const name = productName.textContent.toLowerCase();
                            card.style.display = name.includes(searchTerm) ? 'block' : 'none';
                        }
                    });
                });
            }
        }

        // Category filter functionality
        function initializeCategoryFilter() {
            const categoryButtons = document.querySelectorAll('.category-button');
            categoryButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Update active button
                    categoryButtons.forEach(btn => btn.classList.remove('active', 'bg-blue-600',
                        'text-white'));
                    categoryButtons.forEach(btn => btn.classList.add('bg-gray-100', 'text-gray-700'));

                    this.classList.remove('bg-gray-100', 'text-gray-700');
                    this.classList.add('active', 'bg-blue-600', 'text-white');

                    // Filter products
                    const category = this.dataset.category;
                    const productCards = document.querySelectorAll('.product-card');

                    productCards.forEach(card => {
                        if (category === 'all' || card.dataset.category === category) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        }

        // Checkout form handling
        function initializeCheckout() {
            const checkoutForm = document.getElementById('checkoutForm');
            if (checkoutForm) {
                checkoutForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    if (cart.length === 0) {
                        alert('Keranjang belanja kosong!');
                        return;
                    }

                    const formData = new FormData(this);
                    const amountPaid = parseFloat(formData.get('amount_paid'));
                    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0) * 1.1;

                    if (amountPaid < total) {
                        alert('Jumlah bayar kurang dari total!');
                        return;
                    }

                    // Disable form during processing
                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalText = submitButton.textContent;
                    submitButton.disabled = true;
                    submitButton.textContent = 'Memproses...';

                    try {
                        // Prepare data
                        const customerType = formData.get('customer_type');
                        const customerData = {
                            items: cart,
                            payment_method: formData.get('payment_method'),
                            amount_paid: amountPaid,
                            notes: formData.get('notes')
                        };

                        // Handle customer data
                        if (customerType === 'existing') {
                            customerData.pelanggan_id = formData.get('customer_id') || null;
                        } else {
                            // Jika pelanggan baru, kirim data pelanggan baru
                            const newCustomerName = formData.get('new_customer_name');
                            if (newCustomerName) {
                                customerData.pelanggan = {
                                    nama: newCustomerName
                                };
                            } else {
                                customerData.pelanggan_id = null; // Set null jika tidak ada nama pelanggan
                            }
                        }

                        const response = await fetch('{{ route('admin.pos.checkout') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(customerData)
                        });

                        const result = await response.json();

                        if (result.success) {
                            // Reset cart
                            cart = [];
                            updateCart();
                            this.reset();

                            // Show success message
                            const change = result.data.paid - result.data.total;
                            alert(
                                `Transaksi berhasil!\nKode: ${result.data.code}\nTotal: ${formatCurrency(result.data.total)}\nBayar: ${formatCurrency(result.data.paid)}\nKembali: ${formatCurrency(change)}`);

                            // Print receipt if route exists
                            if (result.data.transaction_id) {
                                const printUrl = '{{ url('admin/pos/receipt') }}/' + result.data
                                .transaction_id;
                                window.location.href = printUrl;

                            }
                        } else {
                            alert(result.message || 'Terjadi kesalahan saat memproses pembayaran');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memproses pembayaran');
                    } finally {
                        // Re-enable form
                        submitButton.disabled = false;
                        submitButton.textContent = originalText;
                    }
                });
            }
        }

        // Handle customer type change
        document.querySelectorAll('input[name="customer_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const existingSelect = document.getElementById('existing_customer_select');
                const newInput = document.getElementById('new_customer_input');

                if (this.value === 'existing') {
                    existingSelect.classList.remove('hidden');
                    newInput.classList.add('hidden');
                } else {
                    existingSelect.classList.add('hidden');
                    newInput.classList.remove('hidden');
                }
            });
        });

        // Initialize all functionality when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializeSearch();
            initializeCategoryFilter();
            initializeCheckout();
            updateCart(); // Initialize cart display
        });
    </script>
    {{-- @endpush --}}
</x-app-layout>
