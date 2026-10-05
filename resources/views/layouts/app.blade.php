<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sirkula | Sirkular Ekonomi Limbah</title>
    <!-- Memanggil Tailwind & Alpine -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-sirkula-light text-sirkula-text antialiased">

    <!-- Navbar Menggunakan Alpine.js untuk State -->
    <nav x-data="{ open: false }" class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                <!-- Logo & Menu Desktop -->
                <div class="flex items-center">
                    <a href="/" class="flex-shrink-0 flex items-center gap-2">
                        <!-- Placeholder Logo -->
                        <div class="w-8 h-8 bg-sirkula-green rounded-full flex items-center justify-center text-white font-bold">S</div>
                        <span class="text-xl font-bold text-sirkula-green tracking-tight">Sirkula</span>
                    </a>
                    <div class="hidden sm:ml-10 sm:flex sm:space-x-8">
                        <a href="/" class="border-b-2 border-sirkula-green text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium">Beranda</a>
                        <a href="#" class="border-b-2 border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 text-sm font-medium transition duration-150">Jelajah Limbah</a>
                        <a href="#" class="border-b-2 border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 text-sm font-medium transition duration-150">Tentang Kami</a>
                    </div>
                </div>

                <!-- Tombol Login/Register & User Profil Desktop -->
                <div class="hidden sm:flex sm:items-center sm:space-x-4">
                    @guest
                        <a href="/login" class="text-sm font-medium text-gray-500 hover:text-gray-900">Masuk</a>
                        <a href="/register" class="bg-sirkula-green hover:bg-sirkula-green-hover text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150">Daftar</a>
                    @endguest

                    @auth
                        <div class="flex items-center gap-4">
                            <a href="/dashboard" class="text-sm font-medium text-gray-700 hover:text-sirkula-green transition-colors">
                                Halo, {{ Auth::user()->name }}
                            </a>
                            <form action="/logout" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-700 transition-colors">Keluar</button>
                            </form>
                        </div>
                    @endauth
                </div>

                <!-- Tombol Menu Mobile -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="open = ! open" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150" aria-label="Main menu" aria-expanded="false">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <!-- Ikon Hamburger -->
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <!-- Ikon X (Tutup) -->
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Menu Dropdown Mobile (Dikendalikan Alpine.js) -->
        <div :class="{'block': open, 'hidden': ! open}" class="sm:hidden hidden border-b border-gray-200 shadow-lg" x-transition>
            <div class="pt-2 pb-3 space-y-1">
                <a href="/" class="bg-green-50 border-l-4 border-sirkula-green text-sirkula-green block pl-3 pr-4 py-2 text-base font-medium">Beranda</a>
                <a href="#" class="border-l-4 border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 text-base font-medium">Jelajah Limbah</a>
                <a href="#" class="border-l-4 border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 text-base font-medium">Tentang Kami</a>
            </div>
            
            <!-- Logika Auth untuk Layar Mobile -->
            <div class="pt-4 pb-4 border-t border-gray-200 bg-gray-50">
                @guest
                    <div class="flex flex-col px-4 space-y-3">
                        <a href="/login" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md text-base font-medium text-gray-700 bg-white hover:bg-gray-100">Masuk</a>
                        <a href="/register" class="block w-full text-center px-4 py-2 border border-transparent rounded-md text-base font-medium text-white bg-sirkula-green hover:bg-sirkula-green-hover">Daftar</a>
                    </div>
                @endguest

                @auth
                    <div class="px-4">
                        <div class="text-base font-medium text-gray-800 mb-1">Halo, {{ Auth::user()->name }}</div>
                        <div class="text-sm font-medium text-gray-500 mb-4">{{ ucfirst(Auth::user()->role) }}</div>
                        
                        <div class="flex flex-col space-y-2">
                            <a href="/dashboard" class="block w-full text-center px-4 py-2 border border-sirkula-green rounded-md text-base font-medium text-sirkula-green bg-green-50 hover:bg-green-100">Dashboard Saya</a>
                            
                            <form action="/logout" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full text-center px-4 py-2 border border-transparent rounded-md text-base font-medium text-white bg-red-500 hover:bg-red-600">Keluar</button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Konten Utama Halaman -->
    <main>
        @yield('content')
    </main>

</body>
</html>