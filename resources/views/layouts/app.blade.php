<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SCM Omahkulos') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen">
            <!-- Sidebar -->
            <aside class="fixed inset-y-0 left-0 bg-white shadow-lg max-h-screen w-64 transform transition-transform duration-300 ease-in-out" id="sidebar">
                <div class="flex flex-col justify-between h-full">
                    <div class="flex-grow">
                        <div class="px-4 py-6 text-center border-b">
                            <h1 class="text-xl font-bold leading-none"><span class="text-blue-700">SCM</span> Omahkulos</h1>
                        </div>
                        <div class="p-4">
                            <nav class="space-y-1">
                                <!-- Menu Admin -->
                                @if(auth()->user()->role === 'admin')
                                    <!-- Dashboard -->
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center bg-blue-100 rounded-xl font-bold text-sm text-blue-900 py-3 px-4">
                                        <i class="fas fa-home mr-4"></i>
                                        Dashboard
                                    </a>

                                    <!-- POS -->
                                    <a href="{{ route('admin.pos') }}" class="flex bg-white hover:bg-blue-50 rounded-xl font-bold text-sm text-gray-900 py-3 px-4">
                                        <i class="fas fa-cash-register mr-4"></i>
                                        POS
                                    </a>

                                    <!-- Pesanan Dropdown -->
                                    <div x-data="{ open: false }" class="relative">
                                        <button @click="open = !open" class="w-full flex items-center justify-between bg-white hover:bg-blue-50 rounded-xl font-bold text-sm text-gray-900 py-3 px-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-shopping-cart mr-4"></i>
                                                Pesanan
                                            </div>
                                            <i class="fas fa-chevron-down transition-transform" :class="{ 'transform rotate-180': open }"></i>
                                        </button>
                                        <div x-show="open" class="mt-2 space-y-1 pl-4">
                                            <a href="#" class="block text-sm text-gray-600 hover:text-blue-600 py-2">Semua Pesanan</a>
                                            <a href="#" class="block text-sm text-gray-600 hover:text-blue-600 py-2">Pesanan Tertunda</a>
                                            <a href="#" class="block text-sm text-gray-600 hover:text-blue-600 py-2">Pesanan Lengkap</a>
                                            <a href="#" class="block text-sm text-gray-600 hover:text-blue-600 py-2">Pembayaran Tertunda</a>
                                        </div>
                                    </div>

                                    <!-- Pembelian Dropdown -->
                                    <div x-data="{ open: false }" class="relative">
                                        <button @click="open = !open" class="w-full flex items-center justify-between bg-white hover:bg-blue-50 rounded-xl font-bold text-sm text-gray-900 py-3 px-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-shopping-basket mr-4"></i>
                                                Pembelian
                                            </div>
                                            <i class="fas fa-chevron-down transition-transform" :class="{ 'transform rotate-180': open }"></i>
                                        </button>
                                        <div x-show="open" class="mt-2 space-y-1 pl-4">
                                            <a href="{{ route('admin.orders.index') }}" class="block text-sm text-gray-600 hover:text-blue-600 py-2">Semua Pembelian</a>
                                            <a href="#" class="block text-sm text-gray-600 hover:text-blue-600 py-2">Proses Persetujuan</a>
                                            <a href="#" class="block text-sm text-gray-600 hover:text-blue-600 py-2">Laporan Pembelian</a>
                                        </div>
                                    </div>

                                    <!-- Manajemen Produk -->
                                    <a href="{{ route('admin.products.index') }}" class="flex bg-white hover:bg-blue-50 rounded-xl font-bold text-sm text-gray-900 py-3 px-4">
                                        <i class="fas fa-box mr-4"></i>
                                        Manajemen Produk
                                    </a>

                                    <!-- Manajemen Stok -->
                                    <a href="{{ route('admin.stocks.index') }}" class="flex bg-white hover:bg-blue-50 rounded-xl font-bold text-sm text-gray-900 py-3 px-4">
                                        <i class="fas fa-box mr-4"></i>
                                        Manajemen Stok
                                    </a>

                                    <!-- Catatan Pelanggan -->
                                    <a href="{{ route('admin.customers.index') }}" class="flex bg-white hover:bg-blue-50 rounded-xl font-bold text-sm text-gray-900 py-3 px-4">
                                        <i class="fas fa-users mr-4"></i>
                                        Catatan Pelanggan
                                    </a>

                                    <!-- Manajemen Pemasok -->
                                    <a href="{{ route('admin.suppliers.index') }}" class="flex bg-white hover:bg-blue-50 rounded-xl font-bold text-sm text-gray-900 py-3 px-4">
                                        <i class="fas fa-user-tie mr-4"></i>
                                        Manajemen Pemasok
                                    </a>

                                <!-- Menu Owner -->
                                @else
                                    <!-- Dashboard -->
                                    <a href="{{ route('owner.dashboard') }}" class="flex items-center bg-blue-100 rounded-xl font-bold text-sm text-blue-900 py-3 px-4">
                                        <i class="fas fa-home mr-4"></i>
                                        Dashboard
                                    </a>

                                    <!-- Laporan Pembelian -->
                                    <a href="" class="flex bg-white hover:bg-blue-50 rounded-xl font-bold text-sm text-gray-900 py-3 px-4">
                                        <i class="fas fa-file-invoice mr-4"></i>
                                        Laporan Pembelian
                                    </a>

                                    <!-- Manajemen Produk -->
                                    <a href="#" class="flex bg-white hover:bg-blue-50 rounded-xl font-bold text-sm text-gray-900 py-3 px-4">
                                        <i class="fas fa-box mr-4"></i>
                                        Manajemen Produk
                                    </a>

                                    <!-- Catatan Pelanggan -->
                                    <a href="#" class="flex bg-white hover:bg-blue-50 rounded-xl font-bold text-sm text-gray-900 py-3 px-4">
                                        <i class="fas fa-users mr-4"></i>
                                        Catatan Pelanggan
                                    </a>

                                    <!-- Manajemen Pemasok -->
                                    <a href="#" class="flex bg-white hover:bg-blue-50 rounded-xl font-bold text-sm text-gray-900 py-3 px-4">
                                        <i class="fas fa-user-tie mr-4"></i>
                                        Manajemen Pemasok
                                    </a>
                                @endif
                            </nav>
                        </div>
                    </div>
                    <div class="p-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center h-9 px-4 rounded-xl bg-gray-900 text-gray-300 hover:text-white text-sm font-semibold transition">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="p-4 ml-64">
                <!-- Header -->
                <header class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $header ?? 'Dashboard' }}</h1>
                        <nav class="flex" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                                <li class="inline-flex items-center">
                                    <a href="#" class="text-gray-700 hover:text-blue-600">
                                        <i class="fas fa-home mr-2"></i>
                                        Home
                                    </a>
                                </li>
                                @if(isset($breadcrumbs))
                                    @foreach($breadcrumbs as $breadcrumb)
                                        <li>
                                            <div class="flex items-center">
                                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                                <a href="{{ $breadcrumb['url'] }}" class="text-gray-700 hover:text-blue-600">
                                                    {{ $breadcrumb['label'] }}
                                                </a>
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            </ol>
                        </nav>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-700 mr-4">{{ auth()->user()->nama }}</span>
                        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                </header>

            <!-- Page Content -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                {{ $slot }}
                </div>

                <!-- Footer -->
                <footer class="mt-8 text-center text-gray-500 text-sm">
                    <p>&copy; {{ date('Y') }} SCM Omahkulos. All rights reserved.</p>
                </footer>
            </main>
        </div>

        <!-- Mobile Menu Button -->
        <button class="lg:hidden fixed bottom-4 right-4 bg-blue-600 text-white p-3 rounded-full shadow-lg" id="mobile-menu-button">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Alpine.js -->
        <script src="//unpkg.com/alpinejs" defer></script>

        <script>
            // Mobile menu toggle
            document.getElementById('mobile-menu-button').addEventListener('click', function() {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.toggle('-translate-x-full');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const sidebar = document.getElementById('sidebar');
                const mobileButton = document.getElementById('mobile-menu-button');
                
                if (window.innerWidth < 1024 && // Only on mobile
                    !sidebar.contains(event.target) && 
                    !mobileButton.contains(event.target)) {
                    sidebar.classList.add('-translate-x-full');
                }
            });
        </script>

        @push('scripts')
        <script>
            function updateOrderCounts() {
                fetch('{{ route("admin.orders.counts") }}')
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('pendingCount').textContent = data.pending;
                        document.getElementById('unpaidCount').textContent = data.unpaid;
                    });
            }

            // Update counts every 5 minutes
            setInterval(updateOrderCounts, 300000);
            
            // Initial update
            updateOrderCounts();
        </script>
        @endpush
    </body>
</html>
