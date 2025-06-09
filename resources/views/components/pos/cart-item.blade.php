@props(['item'])

<div class="flex items-center space-x-4">
    <div class="flex-shrink-0">
        @if($item->product->gambar_produk)
            <img src="{{ Storage::url($item->product->gambar_produk) }}" 
                 alt="{{ $item->product->nama_produk }}" 
                 class="w-16 h-16 object-cover rounded-lg">
        @else
            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        @endif
    </div>
    <div class="flex-1 min-w-0">
        <h4 class="text-sm font-medium text-gray-900 truncate">{{ $item->product->nama_produk }}</h4>
        <p class="text-sm text-gray-500">Rp {{ number_format($item->product->harga_jual, 0, ',', '.') }}</p>
    </div>
    <div class="flex items-center space-x-2">
        <button onclick="decreaseQuantity({{ $item->id }})" 
                class="p-1 text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
        </button>
        <input type="number" 
               value="{{ $item->jumlah }}" 
               min="1" 
               max="{{ $item->product->stok }}" 
               onchange="updateQuantity({{ $item->id }}, this.value)"
               class="w-16 text-center border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
        <button onclick="increaseQuantity({{ $item->id }})" 
                class="p-1 text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
        </button>
        <button onclick="removeFromCart({{ $item->id }})" 
                class="p-1 text-red-600 hover:text-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 rounded">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
    </div>
</div> 