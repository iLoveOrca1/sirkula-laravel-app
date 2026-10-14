<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Sirkula') }} | Sirkular Ekonomi Limbah</title>

    <!-- Konfigurasi Font (IBM Plex Mono & Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap"
        rel="stylesheet">

    <!-- Memanggil Tailwind & CSS/JS Utama -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Leaflet (Peta) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Alpine.js v3 -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- ============================================================ -->
    <!-- CUSTOM CSS: PURE SIRKULA LOGO ANIMATION (BIG & CLEAN)        -->
    <!-- ============================================================ -->
    <style>
        /* Transisi Fade In/Out Layar Loading */
        #sirkula-loader {
            transition: opacity 0.35s ease-out, visibility 0.35s ease-out;
        }
        #sirkula-loader.loader-hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        #sirkula-loader.loader-visible {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        /* Putaran Dinamis Logo Utama (Berirama & Berdenyut) */
        @keyframes sirkulaPureSpin {
            0% {
                transform: rotate(0deg) scale(0.92);
            }
            50% {
                transform: rotate(180deg) scale(1.08);
            }
            100% {
                transform: rotate(360deg) scale(0.92);
            }
        }

        /* Denyut Gelombang Berurutan pada Tiap Elemen Logo (Tetap Presisi & Utuh) */
        @keyframes elementPulseFlow {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
                filter: drop-shadow(0 8px 16px rgba(9, 58, 37, 0.14));
            }
            40% {
                opacity: 0.35;
                transform: scale(0.94);
                filter: drop-shadow(0 2px 4px rgba(9, 58, 37, 0.05));
            }
            75% {
                opacity: 1;
                transform: scale(1.04);
                filter: drop-shadow(0 12px 24px rgba(9, 58, 37, 0.22));
            }
        }

        .sirkula-pure-logo {
            animation: sirkulaPureSpin 2s cubic-bezier(0.65, 0, 0.35, 1) infinite;
        }

        .pure-el-1 {
            animation: elementPulseFlow 2s ease-in-out infinite;
            animation-delay: 0s;
        }
        .pure-el-3 {
            animation: elementPulseFlow 2s ease-in-out infinite;
            animation-delay: 0.25s;
        }
        .pure-el-4 {
            animation: elementPulseFlow 2s ease-in-out infinite;
            animation-delay: 0.5s;
        }
        .pure-el-2 {
            animation: elementPulseFlow 2s ease-in-out infinite;
            animation-delay: 0.75s;
        }
    </style>
</head>

<body class="bg-[#F6FBF5] text-[#414943] font-sans antialiased">

    <!-- ============================================================ -->
    <!-- GLOBAL LOADING PAGE (Murni Logo Sirkula Ukuran Besar)        -->
    <!-- ============================================================ -->
    <div id="sirkula-loader" class="loader-visible fixed inset-0 z-[99999] flex items-center justify-center bg-[#F6FBF5]/90 backdrop-blur-md select-none">
        
        <!-- Kontainer Logo Ukuran Besar -->
        <div class="sirkula-pure-logo relative w-40 h-40 sm:w-48 sm:h-48">
            <!-- Elemen 1: Hijau Kiri Bawah -->
            <img 
                src="{{ asset('storage/images/elemen-1.png') }}" 
                onerror="this.onerror=null; this.src='{{ asset('images/elemen-1.png') }}';"
                alt="" 
                class="pure-el-1 absolute inset-0 w-full h-full object-contain pointer-events-none"
            >
            <!-- Elemen 3: Oranye Kiri Bawah -->
            <img 
                src="{{ asset('storage/images/elemen-3.png') }}" 
                onerror="this.onerror=null; this.src='{{ asset('images/elemen-3.png') }}';"
                alt="" 
                class="pure-el-3 absolute inset-0 w-full h-full object-contain pointer-events-none"
            >
            <!-- Elemen 4: Hijau Kanan Atas -->
            <img 
                src="{{ asset('storage/images/elemen-4.png') }}" 
                onerror="this.onerror=null; this.src='{{ asset('images/elemen-4.png') }}';"
                alt="" 
                class="pure-el-4 absolute inset-0 w-full h-full object-contain pointer-events-none"
            >
            <!-- Elemen 2: Oranye Kanan Atas -->
            <img 
                src="{{ asset('storage/images/elemen-2.png') }}" 
                onerror="this.onerror=null; this.src='{{ asset('images/elemen-2.png') }}';"
                alt="" 
                class="pure-el-2 absolute inset-0 w-full h-full object-contain pointer-events-none"
            >
        </div>

    </div>

    @php
        // Logika Deteksi Halaman Aktif untuk Navigasi
        $isHome = request()->routeIs('home') || request()->is('/') || request()->routeIs('pelajari');
        $isPasar = request()->routeIs('pasar.*') || request()->routeIs('permintaan');
        $isAbout = request()->routeIs('about');
        $isDashboard =
            request()->is('dashboard*') ||
            request()->routeIs('laporan.*') ||
            request()->routeIs('profile.*') ||
            request()->routeIs('permintaan.dashboard');
    @endphp

    <!-- Navbar Menggunakan Alpine.js untuk State -->
    <nav x-data="{ open: false }" class="bg-[#F6FBF5] sticky top-0 z-50 border-b border-[#EBEFEA] shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">

                <!-- 1. Logo Desktop & Mobile -->
                <div class="flex items-center shrink-0">
                    <a href="/" class="flex items-center gap-2.5">
                        <img src="{{ asset('storage/images/logo-sirkula.png') }}" 
                             onerror="this.onerror=null; this.src='{{ asset('images/logo-sirkula.png') }}';"
                             alt="Logo Sirkula" class="h-8 w-auto">
                        <span class="text-xl font-semibold tracking-wide text-primary-500">SIRKULA</span>
                    </a>
                </div>

                <!-- 2. Menu Tengah Desktop (Dinamis) -->
                <div class="hidden sm:flex sm:items-center sm:justify-center flex-1 space-x-8">
                    <a href="{{ route('home') }}"
                        class="text-sm transition-colors {{ $isHome ? 'font-bold text-[#093A25]' : 'font-medium text-gray-500 hover:text-[#093A25]' }}">
                        Beranda
                    </a>
                    <a href="{{ route('pasar.index') }}"
                        class="text-sm transition-colors {{ $isPasar ? 'font-bold text-[#093A25]' : 'font-medium text-gray-500 hover:text-[#093A25]' }}">
                        Jelajahi Limbah
                    </a>
                    <a href="{{ route('about') ?? '#' }}"
                        class="text-sm transition-colors {{ $isAbout ? 'font-bold text-[#093A25]' : 'font-medium text-gray-500 hover:text-[#093A25]' }}">
                        Tentang
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="text-sm transition-colors {{ $isDashboard ? 'font-bold text-[#093A25]' : 'font-medium text-gray-500 hover:text-[#093A25]' }}">
                            Dashboard
                        </a>
                    @endauth
                </div>

                <!-- 3. Tombol Aksi Kanan Desktop -->
                <div class="hidden sm:flex sm:items-center sm:space-x-5 shrink-0">
                    @guest
                        <a href="{{ route('login') }}"
                            class="text-sm font-bold text-[#414943] hover:text-[#093A25] transition-colors">Masuk</a>
                        <a href="{{ route('register') }}"
                            class="bg-[#1A4B36] hover:bg-[#093A25] text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-sm">
                            Daftar menjadi Mitra
                        </a>
                    @endguest

                    @auth
                        <a href="{{ route('laporan.create') }}"
                            class="bg-[#1A4B36] hover:bg-[#093A25] text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-sm">
                            Tambah Limbah
                        </a>

                        <!-- Dropdown Profil Menggunakan Alpine.js -->
                        <div x-data="{ dropdownOpen: false }" class="relative ml-2">
                            <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false"
                                class="flex items-center focus:outline-none transition-transform hover:scale-105">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=093A25&background=C6ECCD&bold=true"
                                    alt="Profile"
                                    class="w-10 h-10 rounded-full border-2 border-white shadow-sm object-cover">
                            </button>

                            <!-- Isi Dropdown -->
                            <div x-show="dropdownOpen" x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-lg border border-[#EBEFEA] py-2 z-50"
                                style="display: none;">

                                <div class="px-5 py-3 border-b border-gray-50">
                                    <p class="text-sm font-bold text-[#093A25] truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs font-mono text-gray-500 mt-0.5 capitalize">{{ Auth::user()->role }}</p>
                                </div>

                                <div class="py-1">
                                    <a href="{{ route('dashboard') }}"
                                        class="block px-5 py-2.5 text-sm text-gray-700 hover:bg-[#F6FBF5] hover:text-[#093A25] font-medium">Dashboard
                                        Saya</a>
                                    <a href="{{ route('profile.edit') }}"
                                        class="block px-5 py-2.5 text-sm text-gray-700 hover:bg-[#F6FBF5] hover:text-[#093A25] font-medium">Pengaturan
                                        Profil</a>
                                </div>

                                <div class="border-t border-gray-50 py-1">
                                    <form action="{{ route('logout') }}" method="POST" class="block w-full text-left">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left px-5 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors">
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>

                <!-- 4. Tombol Menu Mobile (Hamburger) -->
                <div class="-mr-2 flex items-center sm:hidden shrink-0">
                    <button @click="open = ! open" type="button"
                        class="inline-flex items-center justify-center p-2.5 rounded-xl text-gray-500 hover:text-[#093A25] hover:bg-[#EBEFEA] focus:outline-none transition duration-150">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- MENU DROPDOWN MOBILE -->
        <div :class="{ 'block': open, 'hidden': !open }"
            class="sm:hidden hidden border-t border-[#EBEFEA] bg-white shadow-lg absolute w-full z-40" x-transition>
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}"
                    class="block pl-3 pr-4 py-3 text-base transition-colors {{ $isHome ? 'border-l-4 border-[#093A25] bg-[#F6FBF5] text-[#093A25] font-bold' : 'border-l-4 border-transparent hover:bg-[#F6FBF5] hover:border-[#093A25] text-gray-600 hover:text-[#093A25] font-medium' }}">
                    Beranda
                </a>
                <a href="{{ route('pasar.index') }}"
                    class="block pl-3 pr-4 py-3 text-base transition-colors {{ $isPasar ? 'border-l-4 border-[#093A25] bg-[#F6FBF5] text-[#093A25] font-bold' : 'border-l-4 border-transparent hover:bg-[#F6FBF5] hover:border-[#093A25] text-gray-600 hover:text-[#093A25] font-medium' }}">
                    Jelajahi Limbah
                </a>
                <a href="{{ route('about') ?? '#' }}"
                    class="block pl-3 pr-4 py-3 text-base transition-colors {{ $isAbout ? 'border-l-4 border-[#093A25] bg-[#F6FBF5] text-[#093A25] font-bold' : 'border-l-4 border-transparent hover:bg-[#F6FBF5] hover:border-[#093A25] text-gray-600 hover:text-[#093A25] font-medium' }}">
                    Tentang
                </a>
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="block pl-3 pr-4 py-3 text-base transition-colors {{ $isDashboard ? 'border-l-4 border-[#093A25] bg-[#F6FBF5] text-[#093A25] font-bold' : 'border-l-4 border-transparent hover:bg-[#F6FBF5] hover:border-[#093A25] text-gray-600 hover:text-[#093A25] font-medium' }}">
                        Dashboard
                    </a>
                @endauth
            </div>

            <div class="pt-4 pb-5 border-t border-[#EBEFEA] bg-[#F0F5F0]">
                @guest
                    <div class="flex flex-col px-5 space-y-3">
                        <a href="{{ route('login') }}"
                            class="block w-full text-center px-4 py-3 border border-gray-300 rounded-xl text-sm font-bold text-[#414943] bg-white hover:bg-gray-50 shadow-sm">Masuk</a>
                        <a href="{{ route('register') }}"
                            class="block w-full text-center px-4 py-3 border border-transparent rounded-xl text-sm font-bold text-white bg-[#1A4B36] hover:bg-[#093A25] shadow-sm">Daftar
                            menjadi Mitra</a>
                    </div>
                @endguest

                @auth
                    <div class="px-5 flex items-center gap-3 mb-5">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=093A25&background=C6ECCD&bold=true"
                            alt="Profile" class="w-12 h-12 rounded-full border border-white shadow-sm object-cover">
                        <div>
                            <div class="text-base font-bold text-[#093A25]">{{ Auth::user()->name }}</div>
                            <div class="text-[11px] font-mono font-medium text-gray-500 capitalize">
                                {{ Auth::user()->role }}</div>
                        </div>
                    </div>

                    <div class="flex flex-col px-5 space-y-3">
                        <a href="{{ route('laporan.create') }}"
                            class="block w-full text-center px-4 py-3 border border-transparent rounded-xl text-sm font-bold text-white bg-[#093A25] shadow-sm hover:bg-[#1A4B36]">
                            Tambah Limbah Baru
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit"
                                class="w-full text-center px-4 py-3 border border-red-200 rounded-xl text-sm font-bold text-red-600 bg-white hover:bg-red-50 shadow-sm transition-colors">
                                Keluar
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Konten Utama Halaman -->
    <main>
        @yield('content')
    </main>
    <x-layouts.footer />

    <!-- ============================================================ -->
    <!-- GLOBAL LOADING CONTROLLER (Semua Halaman & Semua Proses)     -->
    <!-- ============================================================ -->
    <script>
        (function () {
            const loader = document.getElementById('sirkula-loader');

            function showLoader() {
                if (!loader) return;
                loader.classList.remove('loader-hidden');
                loader.classList.add('loader-visible');
            }

            function hideLoader() {
                if (!loader) return;
                loader.classList.remove('loader-visible');
                loader.classList.add('loader-hidden');
            }

            // Ekspos secara global agar bisa dipanggil manual dari script halaman lain bila perlu
            window.SirkulaLoader = { show: showLoader, hide: hideLoader };

            // 1. Sembunyikan loader setelah halaman selesai dimuat
            window.addEventListener('DOMContentLoaded', function () {
                setTimeout(hideLoader, 350);
            });

            // 2. Event Delegation untuk SEMUA klik link <a> (termasuk elemen dinamis)
            document.addEventListener('click', function (e) {
                const link = e.target.closest('a');
                if (!link) return;

                const href = link.getAttribute('href');
                const target = link.getAttribute('target');

                // Abaikan link kosong, anchor (#), javascript:, tab baru, download, atau klik modifier
                if (
                    !href ||
                    href.startsWith('#') ||
                    href.startsWith('javascript:') ||
                    href.startsWith('mailto:') ||
                    href.startsWith('tel:') ||
                    target === '_blank' ||
                    link.hasAttribute('download') ||
                    link.hasAttribute('data-no-loader') ||
                    e.ctrlKey || e.metaKey || e.shiftKey || e.button !== 0
                ) {
                    return;
                }

                // Abaikan jika link mengarah ke URL yang sama persis (kecuali jika ada query filter baru)
                if (link.href === window.location.href && !link.href.includes('?')) {
                    return;
                }

                e.preventDefault();
                showLoader();

                setTimeout(function () {
                    window.location.href = link.href;
                }, 380);
            });

            // 3. Event Delegation untuk SEMUA Form Submit (Login, Register, CRUD, Filter, Logout)
            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (!form || form.hasAttribute('data-no-loader')) return;
                showLoader();
            });

            // 4. Tangani event browser unload & tombol Back/Forward (bfcache)
            window.addEventListener('beforeunload', function () {
                showLoader();
            });

            window.addEventListener('pageshow', function (event) {
                if (event.persisted) {
                    hideLoader();
                }
            });
        })();
    </script>
</body>
</html>