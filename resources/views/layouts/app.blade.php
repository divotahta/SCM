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
        <aside
            class="fixed inset-y-0 left-0 bg-white shadow-lg max-h-screen w-64 transform transition-transform duration-300 ease-in-out"
            id="sidebar">
            <div class="flex flex-col justify-between h-full">
                <div class="flex-grow">
                    <div class="px-4 py-6 text-center border-b">
                        <h1 class="text-xl font-bold leading-none"><span class="text-blue-700">SCM</span> Omahkulos</h1>
                    </div>
                    <div class="p-4">
                        <nav class="space-y-1">
                            <!-- Menu Admin -->
                            @if (auth()->user()->role === 'admin')
                                <!-- Dashboard -->
                                <a href="{{ route('admin.dashboard') }}"
                                    class="flex items-center {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                    <i class="fas fa-home mr-4"></i>
                                    Dashboard
                                </a>

                                <!-- POS -->
                                <a href="{{ route('admin.pos') }}"
                                    class="flex items-center {{ request()->routeIs('admin.pos') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                    <i class="fas fa-cash-register mr-4"></i>
                                    POS
                                </a>

                                <!-- Manajemen Produk -->
                                <a href="{{ route('admin.purchases.index') }}"
                                    class="flex items-center {{ request()->routeIs('admin.purchases.*') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                    <i class="fas fa-shopping-basket mr-4"></i>
                                    Pembelian
                                </a>

                                <!-- Pembelian Dropdown -->
                                <a href="{{ route('admin.transactions.index') }}"
                                    class="flex items-center {{ request()->routeIs('admin.transactions.*') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                    <i class="fas fa-shopping-cart mr-4"></i>
                                    Transaksi
                                </a>
                                <!-- Manajemen Produk -->
                                <a href="{{ route('admin.products.index') }}"
                                    class="flex items-center {{ request()->routeIs('admin.products.*') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                    <i class="fas fa-box mr-4"></i>
                                    Manajemen Produk
                                </a>

                                <!-- Manajemen Stok -->
                                <a href="{{ route('admin.stocks.index') }}"
                                    class="flex items-center {{ request()->routeIs('admin.stocks.*') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                    <i class="fas fa-box mr-4"></i>
                                    Manajemen Stok
                                </a>

                                <!-- Kalkulator EOQ -->
                                <a href="{{ route('admin.eoq.calculator') }}"
                                    class="flex items-center {{ request()->routeIs('admin.eoq.*') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                    <i class="fas fa-calculator mr-4"></i>
                                    Kalkulator EOQ
                                </a>

                                <!-- Manajemen Pelanggan -->
                                <a href="{{ route('admin.customers.index') }}"
                                    class="flex items-center {{ request()->routeIs('admin.customers.*') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                    <i class="fas fa-users mr-4"></i>
                                    Manajemen Pelanggan
                                </a>

                                <!-- Manajemen Pemasok -->
                                <a href="{{ route('admin.suppliers.index') }}"
                                    class="flex items-center {{ request()->routeIs('admin.suppliers.*') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                    <i class="fas fa-user-tie mr-4"></i>
                                    Manajemen Pemasok
                                </a>

                                <!-- Menu Owner -->
                            @else
                                <!-- Dashboard -->
                                <a href="{{ route('owner.dashboard') }}"
                                    class="flex items-center {{ request()->routeIs('owner.dashboard') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                    <i class="fas fa-home mr-4"></i>
                                    Dashboard
                                </a>

                                <!-- Laporan Pembelian -->
                                <a href="{{ route('owner.purchases.report') }}"
                                    class="flex items-center {{ request()->routeIs('owner.purchases.report') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                    <i class="fas fa-file-invoice mr-4"></i>
                                    Laporan Pembelian
                                </a>

                                <!-- Manajemen Produk -->
                                <a href="{{ route('owner.products.index') }}"
                                    class="flex items-center {{ request()->routeIs('owner.products.*') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                    <i class="fas fa-box mr-4"></i>
                                    Manajemen Produk
                                </a>

                                <!-- Manajemen Pelanggan -->
                                <a href="{{ route('owner.customers.index') }}"
                                    class="flex items-center {{ request()->routeIs('owner.customers.*') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
                                    <i class="fas fa-users mr-4"></i>
                                    Manajemen Pelanggan
                                </a>

                                <!-- Manajemen Pemasok -->
                                <a href="{{ route('owner.suppliers.index') }}"
                                    class="flex items-center {{ request()->routeIs('owner.suppliers.*') ? 'bg-blue-100 text-blue-900' : 'bg-white hover:bg-blue-50 text-gray-900' }} rounded-xl font-bold text-sm py-3 px-4">
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
                        <button type="submit"
                            class="inline-flex items-center justify-center h-9 px-4 rounded-xl bg-gray-900 text-gray-300 hover:text-white text-sm font-semibold transition">
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
                                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('owner.dashboard') }}"
                                    class="text-gray-700 hover:text-blue-600">
                                    <i class="fas fa-home mr-2"></i>
                                    Home
                                </a>
                            </li>
                            @if (isset($breadcrumbs))
                                @foreach ($breadcrumbs as $breadcrumb)
                                    <li>
                                        <div class="flex items-center">
                                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                            <a href="{{ $breadcrumb['url'] }}"
                                                class="text-gray-700 hover:text-blue-600">
                                                {{ $breadcrumb['label'] }}
                                            </a>
                                        </div>
                                    </li>
                                @endforeach
                            @endif
                        </ol>
                    </nav>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Notifications Dropdown -->
                    <div class="relative" x-data="notifications()" x-init="init()">
                        <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span x-show="unreadCount > 0" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full" x-text="unreadCount"></span>
                        </button>

                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-lg overflow-hidden z-50">
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Notifikasi</h3>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <template x-if="notifications.length === 0">
                                    <div class="p-4 text-center text-gray-500">
                                        Tidak ada notifikasi
                                    </div>
                                </template>
                                <template x-for="notification in notifications" :key="notification.id">
                                    <div class="p-4 hover:bg-gray-50 border-b border-gray-100" :class="{ 'bg-blue-50': !notification.dibaca }">
                                        <div class="flex items-start">
                                            <div class="flex-1">
                                                <a :href="notification.link" class="block hover:bg-gray-50 -m-4 p-4 rounded-lg transition-colors duration-150">
                                                    <div class="flex items-center justify-between">
                                                        <p class="text-sm font-medium text-gray-900" x-text="notification.judul"></p>
                                                        <span x-show="!notification.dibaca" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            Baru
                                                        </span>
                                                    </div>
                                                    <p class="text-sm text-gray-600 mt-1" x-text="notification.pesan"></p>
                                                    <p class="text-xs text-gray-500 mt-1" x-text="formatDate(notification.created_at)"></p>
                                                </a>
                                            </div>
                                            <button x-show="!notification.dibaca" @click="markAsRead(notification.id)" class="ml-4 text-sm text-blue-600 hover:text-blue-800">
                                                Tandai dibaca
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div class="p-4 border-t border-gray-200">
                                <a href="{{ route('admin.notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                                    Lihat semua notifikasi
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <span class="text-gray-700 mr-4">{{ auth()->user()->nama }}</span>
                        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white">
                            <i class="fas fa-user"></i>
                        </div>
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
    <button class="lg:hidden fixed bottom-4 right-4 bg-blue-600 text-white p-3 rounded-full shadow-lg"
        id="mobile-menu-button">
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

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('notifications', () => ({
                open: false,
                notifications: [],
                unreadCount: 0,

                init() {
                    this.loadNotifications();
                    setInterval(() => this.loadNotifications(), 30000);
                },

                loadNotifications() {
                    fetch('/admin/admin/notifications')
                        .then(response => response.json())
                        .then(data => {
                            console.log('Notifications loaded:', data);
                            this.notifications = data.notifications;
                            this.unreadCount = data.unreadCount;
                        })
                        .catch(error => {
                            console.error('Error loading notifications:', error);
                        });
                },

                markAsRead(id) {
                    fetch(`/admin/notifications/${id}/mark-as-read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const notification = this.notifications.find(n => n.id === id);
                            if (notification && !notification.dibaca) {
                                notification.dibaca = true;
                                this.unreadCount--;
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error marking notification as read:', error);
                    });
                },

                formatDate(dateString) {
                    const date = new Date(dateString);
                    const now = new Date();
                    const diff = now - date;

                    if (diff < 60000) return 'Baru saja';
                    if (diff < 3600000) return `${Math.floor(diff / 60000)} menit yang lalu`;
                    if (diff < 86400000) return `${Math.floor(diff / 3600000)} jam yang lalu`;
                    if (diff < 604800000) return `${Math.floor(diff / 86400000)} hari yang lalu`;

                    return date.toLocaleDateString('id-ID', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
            }));
        });
    </script>

</body>

</html>
