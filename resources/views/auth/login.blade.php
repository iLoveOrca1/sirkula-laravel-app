<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sirkula | Masuk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-sirkula-light flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 w-full max-w-md">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-sirkula-green">Sirkula</h2>
            <p class="text-gray-500 mt-2">Selamat datang kembali! Silakan masuk ke akun Anda.</p>
        </div>

        <!-- Menampilkan Pesan Error -->
        @if ($errors->any())
            <div class="bg-red-50 text-red-500 p-3 rounded-md mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/login" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-sirkula-green focus:border-sirkula-green outline-none transition-colors">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-sirkula-green focus:border-sirkula-green outline-none transition-colors">
            </div>

            <div class="flex items-center justify-between">
                <a href="#" class="text-sm text-sirkula-green hover:underline">Lupa kata sandi?</a>
            </div>

            <button type="submit" 
                class="w-full bg-sirkula-green text-white font-medium py-2.5 rounded-md hover:bg-sirkula-green-hover transition-colors">
                Masuk
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-6">
            Belum punya akun? <a href="#" class="text-sirkula-green font-medium hover:underline">Daftar sekarang</a>
        </p>
    </div>

</body>
</html>