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
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                        <i class="fas fa-home mr-4"></i>
                                        Dashboard
                                    </a>

                                    <!-- POS -->
                                    <a href="{{ route('admin.pos') }}" class="flex items-center {{ request()->routeIs('admin.pos') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                        <i class="fas fa-cash-register mr-4"></i>
                                        POS
                                    </a>

                                    <!-- Transaksi Dropdown -->
                                    <div x-data="{ open: {{ request()->routeIs('admin.transactions.*') ? 'true' : 'false' }} }" class="relative">
                                        <button @click="open = !open" class="w-full flex items-center justify-between {{ request()->routeIs('admin.transactions.*') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-shopping-cart mr-4"></i>
                                                Transaksi
                                            </div>
                                        </button>
                                       
                                    </div>

                                    <!-- Pembelian Dropdown -->
                                    <div x-data="{ open: {{ request()->routeIs('admin.purchases.*') ? 'true' : 'false' }} }" class="relative">
                                        <button @click="open = !open" class="w-full flex items-center justify-between {{ request()->routeIs('admin.purchases.*') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-shopping-basket mr-4"></i>
                                                Pembelian
                                            </div>
                                            <i class="fas fa-chevron-down transition-transform" :class="{ 'transform rotate-180': open }"></i>
                                        </button>
                                        <div x-show="open" class="mt-2 space-y-1 pl-4">
                                            <a href="{{ route('admin.purchases.index') }}" class="block text-sm {{ request()->routeIs('admin.purchases.index') ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }} py-2">Semua Pembelian</a>
                                            <a href="{{ route('admin.purchases.index', ['status' => 'pending']) }}" class="block text-sm {{ request()->routeIs('admin.purchases.index') && request()->status === 'pending' ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }} py-2">Proses Persetujuan</a>
                                            <a href="{{ route('admin.purchases.index', ['status' => 'completed']) }}" class="block text-sm {{ request()->routeIs('admin.purchases.index') && request()->status === 'completed' ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }} py-2">Laporan Pembelian</a>
                                        </div>
                                    </div>

                                    <!-- Manajemen Produk -->
                                    <a href="{{ route('admin.products.index') }}" class="flex items-center {{ request()->routeIs('admin.products.*') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                        <i class="fas fa-box mr-4"></i>
                                        Manajemen Produk
                                    </a>

                                    <!-- Manajemen Stok -->
                                    <a href="{{ route('admin.stocks.index') }}" class="flex items-center {{ request()->routeIs('admin.stocks.*') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                        <i class="fas fa-box mr-4"></i>
                                        Manajemen Stok
                                    </a>

                                    <!-- Manajemen Pelanggan -->
                                    <a href="{{ route('admin.customers.index') }}" class="flex items-center {{ request()->routeIs('admin.customers.*') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                        <i class="fas fa-users mr-4"></i>
                                        Manajemen Pelanggan
                                    </a>

                                    <!-- Manajemen Pemasok -->
                                    <a href="{{ route('admin.suppliers.index') }}" class="flex items-center {{ request()->routeIs('admin.suppliers.*') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                        <i class="fas fa-user-tie mr-4"></i>
                                        Manajemen Pemasok
                                    </a>

                                <!-- Menu Owner -->
                                @else
                                    <!-- Dashboard -->
                                    <a href="{{ route('owner.dashboard') }}" class="flex items-center {{ request()->routeIs('owner.dashboard') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                        <i class="fas fa-home mr-4"></i>
                                        Dashboard
                                    </a>

                                    <!-- Laporan Pembelian -->
                                    <a href="{{ route('owner.purchases.report') }}" class="flex items-center {{ request()->routeIs('owner.purchases.report') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                        <i class="fas fa-file-invoice mr-4"></i>
                                        Laporan Pembelian
                                    </a>

                                    <!-- Manajemen Produk -->
                                    <a href="{{ route('owner.products.index') }}" class="flex items-center {{ request()->routeIs('owner.products.*') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                        <i class="fas fa-box mr-4"></i>
                                        Manajemen Produk
                                    </a>

                                    <!-- Manajemen Pelanggan -->
                                    <a href="{{ route('owner.customers.index') }}" class="flex items-center {{ request()->routeIs('owner.customers.*') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                        <i class="fas fa-users mr-4"></i>
                                        Manajemen Pelanggan
                                    </a>

                                    <!-- Manajemen Pemasok -->
                                    <a href="{{ route('owner.suppliers.index') }}" class="flex items-center {{ request()->routeIs('owner.suppliers.*') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
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
                                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('owner.dashboard') }}" class="text-gray-700 hover:text-blue-600">
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
    </body>
</html>
