<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SCM Omahkulos - Supply Chain Management Terpadu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Figtree', sans-serif; background: #f8fafc; }
        .hero-bg { background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); }
        .section-title { font-size: 2.25rem; font-weight: bold; color: #1e293b; }
        .section-subtitle { color: #64748b; }
        .feature-card { transition: transform 0.3s; }
        .feature-card:hover { transform: translateY(-5px); box-shadow: 0 8px 24px 0 rgba(59,130,246,0.10); }
        .stat-card { background: #fff; border-radius: 1rem; box-shadow: 0 2px 8px 0 rgba(30,64,175,0.07); }
        .promo-banner { background: linear-gradient(90deg, #1e40af 60%, #3b82f6 100%); color: #fff; border-radius: 1.5rem; }
        .perk-card { background: #fff; border-radius: 1rem; box-shadow: 0 2px 8px 0 rgba(30,64,175,0.07); }
        .footer-bg { background: #1e293b; color: #cbd5e1; }
        .pixabay-img { border-radius: 1rem; box-shadow: 0 2px 8px 0 rgba(30,64,175,0.07); }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="bg-white shadow-lg fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="/" class="flex items-center">
                    <i class="fas fa-box-open text-blue-600 text-2xl mr-2"></i>
                    <span class="text-xl font-bold text-gray-900">SCM Omahkulos</span>
                </a>
                <div class="flex items-center space-x-4">
                    <a href="#fitur" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Fitur</a>
                    <a href="#tentang" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Tentang</a>
                    <a href="#kontak" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Kontak</a>
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Register</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-bg pt-40 pb-40 bg-cover bg-center" style="background-image: url('https://cdn.pixabay.com/photo/2014/08/02/11/40/high-bay-408222_1280.jpg') ; background-size: cover; background-position: center;">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center gap-8">
            <div class="flex-1 text-center md:text-left bg-black/50 p-8 rounded-lg text-white">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Satu Solusi Untuk Semua Kebutuhan Supply Chain</h1>
                <p class="text-blue-100 text-lg mb-6">SCM Omahkulos adalah bisnis cafe yang menggunakan solusi terpadu untuk manajemen supply chain modern.</p>
                <a href="{{ route('register') }}" class="inline-block bg-white text-blue-700 font-bold px-8 py-3 rounded-lg shadow hover:bg-blue-50 transition">Mulai Sekarang</a>
            </div>
            {{-- <div class="flex-1 flex justify-center">
                <img src="https://cdn.pixabay.com/photo/2014/08/02/11/40/high-bay-408222_1280.jpg" alt="Supply Chain Illustration" class="w-full max-w-md pixabay-img">
            </div> --}}
        </div>
    </section>

    <!-- Fitur Utama -->
    <section id="fitur" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="section-title">Fitur Utama</h2>
                <p class="section-subtitle mt-2">Solusi lengkap untuk manajemen supply chain modern</p>
            </div>
            <div class="grid gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
                <div class="feature-card p-6 bg-blue-50 rounded-xl text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/2596/2596480.png" alt="POS" class="h-24 mx-auto mb-4 pixabay-img">
                    <h3 class="font-bold text-lg mb-2">Point of Sale</h3>
                    <p class="text-gray-600">Sistem kasir modern dengan pembayaran cepat dan integrasi pelanggan.</p>
                </div>
                <div class="feature-card p-6 bg-blue-50 rounded-xl text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/10951/10951884.png" alt="Inventori" class="h-24 mx-auto mb-4 pixabay-img">
                    <h3 class="font-bold text-lg mb-2">Manajemen Inventori</h3>
                    <p class="text-gray-600">Pantau stok produk, kelola gudang, dan optimalkan level inventori Anda.</p>
                </div>
                <div class="feature-card p-6 bg-blue-50 rounded-xl text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/2166/2166907.png" alt="Supplier" class="h-24 mx-auto mb-4 pixabay-img">
                    <h3 class="font-bold text-lg mb-2">Manajemen Pemasok</h3>
                    <p class="text-gray-600">Kelola pemasok dan proses pembelian dengan mudah dan transparan.</p>
                </div>
                <div class="feature-card p-6 bg-blue-50 rounded-xl text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/9746/9746772.png" alt="Pelanggan" class="h-24 mx-auto mb-4 pixabay-img">
                    <h3 class="font-bold text-lg mb-2">Manajemen Pelanggan</h3>
                    <p class="text-gray-600">Kelola data pelanggan, riwayat transaksi, dan program loyalitas.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistik/Keunggulan -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="stat-card p-8">
                    <div class="text-blue-600 text-3xl mb-2"><i class="fas fa-users"></i></div>
                    <div class="text-2xl font-bold">5.000+</div>
                    <div class="text-gray-500">Pelanggan Terdaftar</div>
                </div>
                <div class="stat-card p-8">
                    <div class="text-blue-600 text-3xl mb-2"><i class="fas fa-box"></i></div>
                    <div class="text-2xl font-bold">10.000+</div>
                    <div class="text-gray-500">Produk Dikelola</div>
                </div>
                <div class="stat-card p-8">
                    <div class="text-blue-600 text-3xl mb-2"><i class="fas fa-store"></i></div>
                    <div class="text-2xl font-bold">100+</div>
                    <div class="text-gray-500">Mitra Toko & Supplier</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Promo Banner -->
    <section class="py-12">
        <div class="max-w-5xl mx-auto px-4">
            <div class="promo-banner flex flex-col md:flex-row items-center justify-between p-8">
                <div class="flex-1 mb-6 md:mb-0">
                    <h2 class="text-2xl md:text-3xl font-bold mb-2">Optimalkan Bisnis Anda dengan SCM Omahkulos</h2>
                    <p class="mb-4">Sistem terintegrasi untuk semua kebutuhan supply chain, dari kasir hingga laporan analitik.</p>
                    <a href="{{ route('register') }}" class="inline-block bg-white text-blue-700 font-bold px-6 py-2 rounded-lg shadow hover:bg-blue-50 transition">Daftar Sekarang</a>
                </div>
                <div class="flex-1 flex justify-center">
                    <img src="https://cdn.pixabay.com/photo/2022/04/04/12/29/garden-7111101_1280.jpg" alt="Promo SCM" class="h-40 pixabay-img">
                </div>
            </div>
        </div>
    </section>

    <!-- Join Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="section-title">Bergabung Bersama Kami</h2>
                <p class="section-subtitle mt-2">Jadilah bagian dari ekosistem supply chain modern</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="perk-card p-6 text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/11450/11450005.png" alt="Merchant" class="h-20 mx-auto mb-4 pixabay-img">
                    <h3 class="font-bold text-lg mb-2">Merchant</h3>
                    <p class="text-gray-600">Kelola toko dan penjualan dengan sistem kasir terintegrasi.</p>
                </div>
                <div class="perk-card p-6 text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/17226/17226426.png" alt="Supplier" class="h-20 mx-auto mb-4 pixabay-img">
                    <h3 class="font-bold text-lg mb-2">Supplier</h3>
                    <p class="text-gray-600">Gabung sebagai pemasok dan perluas jaringan distribusi Anda.</p>
                </div>
                <div class="perk-card p-6 text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/3201/3201521.png" alt="Pelanggan" class="h-20 mx-auto mb-4 pixabay-img">
                    <h3 class="font-bold text-lg mb-2">Pelanggan</h3>
                    <p class="text-gray-600">Dapatkan pengalaman belanja yang mudah dan transparan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Keuntungan/Perks -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="section-title">Keuntungan Menggunakan SCM Omahkulos</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="perk-card p-6">
                    <h3 class="font-bold text-lg mb-2">Efisiensi Operasional</h3>
                    <p class="text-gray-600">Otomatisasi proses bisnis dan pengurangan kesalahan manual.</p>
                </div>
                <div class="perk-card p-6">
                    <h3 class="font-bold text-lg mb-2">Analisis Data Real-time</h3>
                    <p class="text-gray-600">Laporan dan analitik untuk pengambilan keputusan yang lebih baik.</p>
                </div>
                <div class="perk-card p-6">
                    <h3 class="font-bold text-lg mb-2">Dukungan Multi-Outlet</h3>
                    <p class="text-gray-600">Kelola banyak toko dan gudang dalam satu platform.</p>
                </div>
                <div class="perk-card p-6">
                    <h3 class="font-bold text-lg mb-2">Akses Cloud</h3>
                    <p class="text-gray-600">Akses data kapan saja dan di mana saja secara aman.</p>
                </div>
                <div class="perk-card p-6">
                    <h3 class="font-bold text-lg mb-2">Integrasi Mudah</h3>
                    <p class="text-gray-600">Integrasi dengan berbagai sistem pembayaran dan logistik.</p>
                </div>
                <div class="perk-card p-6">
                    <h3 class="font-bold text-lg mb-2">Dukungan Pelanggan 24/7</h3>
                    <p class="text-gray-600">Tim support siap membantu kapan saja.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Download/CTA Section -->
    <section class="py-16 bg-blue-600">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center gap-8">
            <div class="flex-1 text-white">
                <h2 class="text-3xl font-bold mb-4">Siap Memulai Digitalisasi Supply Chain Anda?</h2>
                <p class="mb-6">Daftar sekarang dan nikmati kemudahan manajemen bisnis bersama SCM Omahkulos.</p>
                <a href="{{ route('register') }}" class="inline-block bg-white text-blue-700 font-bold px-8 py-3 rounded-lg shadow hover:bg-blue-50 transition">Daftar Sekarang</a>
            </div>
            <div class="flex-1 flex justify-center">
                <img src="https://cdn-icons-png.flaticon.com/512/18115/18115733.png" alt="Aplikasi SCM" class="w-full max-w-xs pixabay-img">
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-bg py-12" id="kontak">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-white text-lg font-semibold mb-4">SCM Omahkulos</h3>
                <p class="text-gray-400">Solusi terpadu untuk manajemen supply chain modern.</p>
            </div>
            <div>
                <h3 class="text-white text-lg font-semibold mb-4">Kontak</h3>
                <ul class="space-y-2 text-gray-400">
                    <li><i class="fas fa-envelope mr-2"></i> info@omahkulos.com</li>
                    <li><i class="fas fa-phone mr-2"></i> +62 123 4567 890</li>
                </ul>
            </div>
            <div>
                <h3 class="text-white text-lg font-semibold mb-4">Ikuti Kami</h3>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
        <div class="mt-8 border-t border-gray-800 pt-8 text-center text-gray-400">
            &copy; {{ date('Y') }} SCM Omahkulos. All rights reserved.
        </div>
    </footer>
</body>
</html>
