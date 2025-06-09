@props(['product'])

<div class="product-card bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200" data-category="{{ $product->category->nama_kategori }}">
    <div class="aspect-w-1 aspect-h-1">
        @if($product->gambar_produk)
            <img src="{{ Storage::url($product->gambar_produk) }}" alt="{{ $product->nama_produk }}" class="w-full h-48 object-cover">
        @else
            <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        @endif
    </div>
    <div class="p-4">
        <h3 class="product-name text-sm font-medium text-gray-900 mb-1">{{ $product->nama_produk }}</h3>
        <p class="text-sm text-gray-500 mb-2">{{ $product->category->nama_kategori }}</p>
        <div class="flex items-center justify-between">
            <span class="text-lg font-semibold text-gray-900">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</span>
            <button onclick="addToCart({{ $product->id }})" 
                    class="bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </button>
        </div>
    </div>
</div> 