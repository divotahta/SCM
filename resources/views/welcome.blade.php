<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SCM Omahkulos - Sistem Manajemen Supply Chain</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        }
        .feature-card {
            transition: transform 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="antialiased bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center">
                        <i class="fas fa-box-open text-blue-600 text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-gray-900">SCM Omahkulos</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Register</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-gradient pt-24 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white sm:text-5xl md:text-6xl">
                    Sistem Manajemen Supply Chain Terpadu
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-blue-100 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    Kelola inventori, transaksi, dan supply chain Anda dengan lebih efisien menggunakan SCM Omahkulos
                </p>
                <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
                    <div class="rounded-md shadow">
                        <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10">
                            Mulai Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">
                    Fitur Utama
                </h2>
                <p class="mt-4 text-lg text-gray-600">
                    Solusi lengkap untuk manajemen supply chain Anda
                </p>
            </div>

            <div class="mt-12 grid gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                <!-- POS -->
                <div class="feature-card bg-white rounded-lg shadow-lg p-6">
                    <div class="text-blue-600 text-3xl mb-4">
                        <i class="fas fa-cash-register"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Point of Sale</h3>
                    <p class="text-gray-600">Sistem kasir modern dengan fitur pembayaran cepat dan manajemen pelanggan terintegrasi</p>
                </div>

                <!-- Inventory Management -->
                <div class="feature-card bg-white rounded-lg shadow-lg p-6">
                    <div class="text-blue-600 text-3xl mb-4">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Manajemen Inventori</h3>
                    <p class="text-gray-600">Pantau stok produk, kelola gudang, dan optimalkan level inventori Anda</p>
                </div>

                <!-- Supplier Management -->
                <div class="feature-card bg-white rounded-lg shadow-lg p-6">
                    <div class="text-blue-600 text-3xl mb-4">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Manajemen Pemasok</h3>
                    <p class="text-gray-600">Kelola hubungan dengan pemasok dan optimalkan proses pembelian</p>
                </div>

                <!-- Customer Management -->
                <div class="feature-card bg-white rounded-lg shadow-lg p-6">
                    <div class="text-blue-600 text-3xl mb-4">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Manajemen Pelanggan</h3>
                    <p class="text-gray-600">Kelola data pelanggan, riwayat transaksi, dan program loyalitas</p>
                </div>

                <!-- Purchase Management -->
                <div class="feature-card bg-white rounded-lg shadow-lg p-6">
                    <div class="text-blue-600 text-3xl mb-4">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Manajemen Pembelian</h3>
                    <p class="text-gray-600">Proses pembelian terstruktur dengan sistem persetujuan dan pelacakan</p>
                </div>

                <!-- Reports -->
                <div class="feature-card bg-white rounded-lg shadow-lg p-6">
                    <div class="text-blue-600 text-3xl mb-4">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Laporan & Analisis</h3>
                    <p class="text-gray-600">Laporan detail dan analisis data untuk pengambilan keputusan yang lebih baik</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Benefits Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">
                    Keuntungan Menggunakan SCM Omahkulos
                </h2>
                <p class="mt-4 text-lg text-gray-600">
                    Tingkatkan efisiensi bisnis Anda dengan solusi terpadu
                </p>
            </div>

            <div class="mt-12 grid gap-8 grid-cols-1 md:grid-cols-2">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Efisiensi Operasional</h3>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Otomatisasi proses bisnis
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Pengurangan kesalahan manual
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Optimasi alur kerja
                        </li>
                    </ul>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Peningkatan Profitabilitas</h3>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Pengurangan biaya operasional
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Optimasi inventori
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Peningkatan layanan pelanggan
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-blue-600">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
            <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                <span class="block">Siap untuk memulai?</span>
                <span class="block text-blue-200">Daftar sekarang dan optimalkan bisnis Anda.</span>
            </h2>
            <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                <div class="inline-flex rounded-md shadow">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50">
                        Daftar Sekarang
                    </a>
                </div>
                <div class="ml-3 inline-flex rounded-md shadow">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-500 hover:bg-blue-400">
                        Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-white text-lg font-semibold mb-4">SCM Omahkulos</h3>
                    <p class="text-gray-400">
                        Solusi terpadu untuk manajemen supply chain modern
                    </p>
                </div>
                <div>
                    <h3 class="text-white text-lg font-semibold mb-4">Kontak</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li>
                            <i class="fas fa-envelope mr-2"></i>
                            info@omahkulos.com
                        </li>
                        <li>
                            <i class="fas fa-phone mr-2"></i>
                            +62 123 4567 890
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white text-lg font-semibold mb-4">Ikuti Kami</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-800 pt-8">
                <p class="text-center text-gray-400">
                    &copy; {{ date('Y') }} SCM Omahkulos. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
