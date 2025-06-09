<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pembelian Baru') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.purchases.index') }}" 
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form id="purchaseForm" class="space-y-6">
                        @csrf
                        
                        <!-- Informasi Pembelian -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pemasok</label>
                                <select name="supplier_id" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Pilih Pemasok</option>
                                    @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pembelian</label>
                                <input type="date" name="purchase_date" required value="{{ date('Y-m-d') }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea name="notes" rows="1"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                            </div>
                        </div>

                        <!-- Pencarian Produk -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Produk</label>
                            <div class="flex space-x-2">
                                <input type="text" id="productSearch" placeholder="Ketik nama produk..."
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <button type="button" onclick="showProductList()"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div id="productList" class="hidden absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg">
                                <ul class="max-h-60 rounded-md py-1 text-base overflow-auto focus:outline-none sm:text-sm">
                                    @foreach($products as $product)
                                    <li class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-50"
                                        onclick="addProduct({{ $product->id }}, '{{ $product->name }}', {{ $product->purchase_price }})">
                                        <div class="flex items-center">
                                            <span class="font-normal block truncate">{{ $product->name }}</span>
                                            <span class="ml-2 text-gray-500">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</span>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <!-- Tabel Produk -->
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
                                <tbody class="bg-white divide-y divide-gray-200" id="productTable">
                                    <!-- Produk akan ditambahkan di sini -->
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right font-medium">Total:</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium" id="totalAmount">
                                            Rp 0
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex justify-end space-x-2">
                            <button type="button" onclick="saveAsDraft()"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                <i class="fas fa-save mr-2"></i> Simpan Draft
                            </button>
                            <button type="button" onclick="submitPurchase()"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                <i class="fas fa-paper-plane mr-2"></i> Ajukan Pembelian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let products = [];
        let productList = document.getElementById('productList');
        let productSearch = document.getElementById('productSearch');

        function showProductList() {
            productList.classList.remove('hidden');
        }

        function hideProductList() {
            productList.classList.add('hidden');
        }

        productSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const items = productList.getElementsByTagName('li');
            
            Array.from(items).forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? '' : 'none';
            });
            
            if (searchTerm) {
                showProductList();
            }
        });

        document.addEventListener('click', function(e) {
            if (!productList.contains(e.target) && e.target !== productSearch) {
                hideProductList();
            }
        });

        function addProduct(id, name, price) {
            const existingProduct = products.find(p => p.id === id);
            if (existingProduct) {
                existingProduct.quantity++;
                updateProductRow(existingProduct);
            } else {
                const product = {
                    id: id,
                    name: name,
                    price: price,
                    quantity: 1
                };
                products.push(product);
                addProductRow(product);
            }
            updateTotal();
            hideProductList();
            productSearch.value = '';
        }

        function addProductRow(product) {
            const row = document.createElement('tr');
            row.id = `product-${product.id}`;
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">${product.name}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="number" value="${product.price}" 
                        onchange="updateProductPrice(${product.id}, this.value)"
                        class="w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="number" value="${product.quantity}" min="1"
                        onchange="updateProductQuantity(${product.id}, this.value)"
                        class="w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </td>
                <td class="px-6 py-4 whitespace-nowrap subtotal">
                    Rp ${(product.price * product.quantity).toLocaleString('id-ID')}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <button type="button" onclick="removeProduct(${product.id})"
                        class="text-red-600 hover:text-red-900">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            document.getElementById('productTable').appendChild(row);
        }

        function updateProductRow(product) {
            const row = document.getElementById(`product-${product.id}`);
            if (row) {
                row.querySelector('input[type="number"]:nth-child(2)').value = product.quantity;
                row.querySelector('.subtotal').textContent = `Rp ${(product.price * product.quantity).toLocaleString('id-ID')}`;
            }
        }

        function updateProductPrice(id, price) {
            const product = products.find(p => p.id === id);
            if (product) {
                product.price = parseFloat(price);
                updateProductRow(product);
                updateTotal();
            }
        }

        function updateProductQuantity(id, quantity) {
            const product = products.find(p => p.id === id);
            if (product) {
                product.quantity = parseInt(quantity);
                updateProductRow(product);
                updateTotal();
            }
        }

        function removeProduct(id) {
            if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                products = products.filter(p => p.id !== id);
                const row = document.getElementById(`product-${id}`);
                if (row) {
                    row.remove();
                }
                updateTotal();
            }
        }

        function updateTotal() {
            const total = products.reduce((sum, product) => sum + (product.price * product.quantity), 0);
            document.getElementById('totalAmount').textContent = `Rp ${total.toLocaleString('id-ID')}`;
        }

        function saveAsDraft() {
            submitForm('draft');
        }

        function submitPurchase() {
            submitForm('pending');
        }

        function submitForm(status) {
            if (products.length === 0) {
                alert('Tambahkan minimal satu produk');
                return;
            }

            const formData = new FormData(document.getElementById('purchaseForm'));
            formData.append('products', JSON.stringify(products));
            formData.append('status', status);

            fetch('{{ route("admin.purchases.store") }}', {
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
                alert('Terjadi kesalahan saat menyimpan pembelian');
            });
        }
    </script>
    @endpush
</x-app-layout> 