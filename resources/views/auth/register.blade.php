<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sirkula | Daftar Akun</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-sirkula-light flex items-center justify-center min-h-screen py-10">

    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 w-full max-w-md">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-sirkula-green">Bergabunglah</h2>
            <p class="text-gray-500 mt-2">Buat akun untuk mulai berkontribusi.</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 text-red-500 p-3 rounded-md mb-4 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/register" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap / Instansi</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-sirkula-green focus:border-sirkula-green outline-none">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-sirkula-green focus:border-sirkula-green outline-none">
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Daftar Sebagai</label>
                <select name="role" id="role" required class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:ring-sirkula-green focus:border-sirkula-green outline-none">
                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Pilih Peran Anda --</option>
                    <option value="pembeli" {{ old('role') == 'pembeli' ? 'selected' : '' }}>Pencari/Pembeli Limbah</option>
                    <option value="mitra" {{ old('role') == 'mitra' ? 'selected' : '' }}>Mitra (Penghasil Limbah)</option>
                </select>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                <input type="password" id="password" name="password" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-sirkula-green focus:border-sirkula-green outline-none">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Kata Sandi</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-sirkula-green focus:border-sirkula-green outline-none">
            </div>

            <button type="submit" class="w-full bg-sirkula-green text-white font-medium py-2.5 rounded-md hover:bg-sirkula-green-hover transition-colors mt-2">
                Daftar Sekarang
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-6">
            Sudah punya akun? <a href="/login" class="text-sirkula-green font-medium hover:underline">Masuk di sini</a>
        </p>
    </div>

</body>
</html>