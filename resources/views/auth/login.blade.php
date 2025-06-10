<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SCM Omahkulos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Figtree', sans-serif; background: #f8fafc; }
        .login-bg { background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); }
        .login-card { background: #fff; border-radius: 1.5rem; box-shadow: 0 4px 24px 0 rgba(30,64,175,0.10); }
        .pixabay-img { border-radius: 1rem; }
        .btn-blue { background: #1e40af; color: #fff; }
        .btn-blue:hover { background: #3b82f6; color: #fff; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center login-bg">
    <div class="w-full max-w-4xl mx-auto flex flex-col md:flex-row items-center gap-8 p-6">
        <div class="flex-1 hidden md:flex justify-center">
            <img src="https://cdn-icons-png.flaticon.com/512/7856/7856126.png" alt="Login Illustration" class="w-full max-w-xs pixabay-img">
        </div>
        <div class="flex-1">
            <div class="login-card p-8">
                <div class="mb-6 text-center">
                    <a href="/" class="flex items-center justify-center mb-2">
                        <i class="fas fa-box-open text-blue-600 text-2xl mr-2"></i>
                        <span class="text-2xl font-bold text-gray-900">SCM Omahkulos</span>
                    </a>
                    <h2 class="text-xl font-bold text-gray-900">Login ke Akun Anda</h2>
                </div>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 mb-2">Email</label>
                        <input id="email" type="email" name="email" required autofocus class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 mb-2">Password</label>
                        <input id="password" type="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <input type="checkbox" name="remember" id="remember" class="mr-1">
                            <label for="remember" class="text-sm text-gray-600">Ingat saya</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">Lupa password?</a>
                    </div>
                    <button type="submit" class="w-full btn-blue py-2 rounded-lg font-bold text-lg transition">Login</button>
                </form>
                <div class="mt-6 text-center text-gray-600">
                    Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Daftar sekarang</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
