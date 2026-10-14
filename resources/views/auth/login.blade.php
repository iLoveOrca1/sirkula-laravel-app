<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIRKULA | Portal Akun Mitra</title>

    <!-- Google Fonts: Plus Jakarta Sans (UI/Heading) & IBM Plex Mono (Badge/Teknis) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Tailwind & JS Utama -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js v3 -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --f-ui: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            --f-mono: 'IBM Plex Mono', ui-monospace, Menlo, monospace;
        }

        body {
            font-family: var(--f-ui);
        }

        .font-mono-tech {
            font-family: var(--f-mono);
        }

        [x-cloak] {
            display: none !important;
        }

        /* Checkbox mengikuti warna brand (kotak hijau tua seperti di Figma) */
        input[type="checkbox"] {
            accent-color: #23533D;
        }

        /* Hilangkan ikon "reveal password" bawaan Edge agar tidak dobel dengan tombol LIHAT/mata */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }

        /* ============================================================ */
        /* CUSTOM CSS: PURE SIRKULA LOGO ANIMATION (KONSISTEN GLOBAL)   */
        /* ============================================================ */
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

        @keyframes sirkulaPureSpin {
            0% { transform: rotate(0deg) scale(0.92); }
            50% { transform: rotate(180deg) scale(1.08); }
            100% { transform: rotate(360deg) scale(0.92); }
        }

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

        .pure-el-1 { animation: elementPulseFlow 2s ease-in-out infinite; animation-delay: 0s; }
        .pure-el-3 { animation: elementPulseFlow 2s ease-in-out infinite; animation-delay: 0.25s; }
        .pure-el-4 { animation: elementPulseFlow 2s ease-in-out infinite; animation-delay: 0.5s; }
        .pure-el-2 { animation: elementPulseFlow 2s ease-in-out infinite; animation-delay: 0.75s; }

        @media (prefers-reduced-motion: reduce) {
            .sirkula-pure-logo,
            .pure-el-1, .pure-el-2, .pure-el-3, .pure-el-4 {
                animation: none;
            }
        }
    </style>
</head>

@php
    // Otomatis buka tab 'register' jika diakses dari route register atau ada error pada input register
    $initialTab = (request()->routeIs('register') || request()->is('register') || old('auth_mode') === 'register' || $errors->has('name') || $errors->has('nama_instansi') || $errors->has('whatsapp') || $errors->has('jenis_usaha')) ? 'register' : 'login';

    // Gaya input: Login = putih berbingkai, Register = terisi abu-hijau (sesuai Figma)
    $fieldLogin = 'w-full h-[42px] bg-white border border-[#D9E0D9] rounded-lg text-[13px] text-[#18221C] placeholder-[#9AA59D] outline-none transition focus:border-[#23533D] focus:ring-2 focus:ring-[#23533D]/15';
    $fieldReg = 'w-full h-[40px] bg-[#EEF3EE] border border-transparent rounded-lg text-[12.5px] text-[#18221C] placeholder-[#7F8B83] outline-none transition focus:bg-white focus:border-[#23533D] focus:ring-2 focus:ring-[#23533D]/15';
@endphp

<body class="bg-[#F4FBF4] text-[#18221C] min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-10 antialiased selection:bg-[#23533D] selection:text-white">

    <!-- ============================================================ -->
    <!-- GLOBAL LOADING PAGE (Murni Logo Sirkula Ukuran Besar)        -->
    <!-- ============================================================ -->
    <div id="sirkula-loader" class="loader-visible fixed inset-0 z-[99999] flex items-center justify-center bg-[#F4FBF4]/90 backdrop-blur-md select-none">
        <div class="sirkula-pure-logo relative w-40 h-40 sm:w-48 sm:h-48">
            <img src="{{ asset('storage/images/elemen-1.png') }}" onerror="this.onerror=null; this.src='{{ asset('images/elemen-1.png') }}';" alt="" class="pure-el-1 absolute inset-0 w-full h-full object-contain pointer-events-none">
            <img src="{{ asset('storage/images/elemen-3.png') }}" onerror="this.onerror=null; this.src='{{ asset('images/elemen-3.png') }}';" alt="" class="pure-el-3 absolute inset-0 w-full h-full object-contain pointer-events-none">
            <img src="{{ asset('storage/images/elemen-4.png') }}" onerror="this.onerror=null; this.src='{{ asset('images/elemen-4.png') }}';" alt="" class="pure-el-4 absolute inset-0 w-full h-full object-contain pointer-events-none">
            <img src="{{ asset('storage/images/elemen-2.png') }}" onerror="this.onerror=null; this.src='{{ asset('images/elemen-2.png') }}';" alt="" class="pure-el-2 absolute inset-0 w-full h-full object-contain pointer-events-none">
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- MAIN SPLIT AUTH CARD                                         -->
    <!-- ============================================================ -->
    <div
        x-data="{
            tab: '{{ $initialTab }}',
            showLoginPass: false,
            showRegPass: false,
            showRegConfirmPass: false
        }"
        class="w-full max-w-[1000px] bg-white rounded-2xl shadow-[0_24px_60px_-18px_rgba(9,58,37,0.18)] overflow-hidden grid grid-cols-1 lg:grid-cols-[46fr_54fr] lg:min-h-[660px]"
    >

        <!-- ============================================================ -->
        <!-- PANEL KIRI: BRANDING & VALUE PROPOSITION                     -->
        <!-- ============================================================ -->
        <div class="relative bg-[#EEF4EE] px-8 py-8 lg:px-9 lg:py-9 flex flex-col justify-between overflow-hidden">

            <!-- Efek hijau lembut di sudut kanan atas & kiri bawah (sesuai Figma) -->
            <div class="pointer-events-none absolute -top-10 -right-12 w-64 h-64 rounded-full bg-[#CFE9D5]/70 blur-2xl"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-24 w-64 h-64 rounded-full bg-[#CFE9D5]/50 blur-3xl"></div>

            <!-- Bagian atas panel kiri -->
            <div class="relative z-10">
                <!-- Logo SIRKULA + titik oranye -->
                <a href="/" class="inline-flex items-center gap-2.5 mb-5 group">
                    <img
                        src="{{ asset('storage/images/logo-sirkula.png') }}"
                        onerror="this.onerror=null; this.src='{{ asset('images/logo-sirkula.png') }}';"
                        alt="Logo Sirkula"
                        class="h-10 w-auto transition-transform group-hover:scale-105"
                    >
                    <span class="text-[21px] font-semibold tracking-[0.01em] text-[#093A25] flex items-start gap-1">
                        SIRKULA
                        <span class="w-2 h-2 rounded-full bg-[#E0623A] inline-block mt-1"></span>
                    </span>
                </a>

                <!-- Badge slogan monospace -->
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#E3EBE2] text-[#2A4638] text-[9.5px] font-mono-tech font-medium tracking-[0.08em] uppercase mb-5">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#2F6B4F]"></span>
                    Temukan. Hubungkan. Sirkulasikan.
                </div>

                <!-- KONTEN KIRI: MODE LOGIN -->
                <div x-show="tab === 'login'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <h1 class="text-[28px] lg:text-[31px] font-semibold text-[#0F2A1D] leading-[1.18] tracking-[-0.02em] mb-4">
                        Beri Nilai Baru Pada Setiap Material Sisa Anda.
                    </h1>
                    <p class="text-[13.5px] text-[#4F5C54] leading-[1.7] mb-7 max-w-[330px]">
                        Platform pertukaran terstruktur untuk mendistribusikan material sisa bisnis hospitality, restoran, dan industri langsung ke ekosistem daur ulang.
                    </p>

                    <!-- 3 poin keunggulan -->
                    <div class="space-y-3.5">
                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-full bg-[#C9EBD2] text-[#0F3824] flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <p class="text-[12px] font-semibold text-[#18221C] leading-snug pt-0.5">
                                Publikasikan informasi limbah bisnis Anda secara terstandardisasi dalam hitungan menit.
                            </p>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-full bg-[#C9EBD2] text-[#0F3824] flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            </div>
                            <p class="text-[12px] font-semibold text-[#18221C] leading-snug pt-0.5">
                                Terhubung langsung dengan jaringan recycler, peternak, dan pengumpul terverifikasi via WhatsApp.
                            </p>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-full bg-[#C9EBD2] text-[#0F3824] flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <p class="text-[12px] font-semibold text-[#18221C] leading-snug pt-0.5">
                                Kelola status operasional serta pantau metrik sirkularitas riil fasilitas Anda.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- KONTEN KIRI: MODE REGISTER -->
                <div x-show="tab === 'register'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <h1 class="text-[28px] lg:text-[31px] font-semibold text-[#0F2A1D] leading-[1.18] tracking-[-0.02em] mb-4">
                        Bergabung Sebagai Mitra Penghasil Limbah.
                    </h1>
                    <p class="text-[13.5px] text-[#4F5C54] leading-[1.7] mb-7 max-w-[340px]">
                        Bantu ekosistem lokal menyerap material bernilai dari fasilitas Anda. Kurangi beban TPA dan terhubung langsung dengan jaringan recycler tepercaya.
                    </p>
                </div>
            </div>

            <!-- Bagian bawah panel kiri: kartu benefit (register) -->
            <div x-show="tab === 'register'" x-cloak class="relative z-10 mt-6 space-y-3.5">
                <div class="bg-white p-4 rounded-xl shadow-[0_6px_18px_-8px_rgba(9,58,37,0.18)] flex items-start gap-3.5">
                    <div class="w-9 h-9 rounded-lg bg-[#23533D] text-white flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-[13px] font-semibold text-[#111C16]">Gratis &amp; Tanpa Biaya Komisi</h3>
                        <p class="text-[11px] text-[#5B6860] leading-relaxed mt-0.5">
                            SIRKULA memfasilitasi koneksi langsung tanpa perantara biaya transaksi atau potongan royalti penjualan.
                        </p>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl shadow-[0_6px_18px_-8px_rgba(9,58,37,0.18)] flex items-start gap-3.5">
                    <div class="w-9 h-9 rounded-lg bg-[#23533D] text-white flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-[13px] font-semibold text-[#111C16]">Kontrol Penuh Jadwal &amp; Pengambilan</h3>
                        <p class="text-[11px] text-[#5B6860] leading-relaxed mt-0.5">
                            Anda menentukan sendiri kuantitas, jam operasional dock pemuatan, dan kesepakatan logistik via WhatsApp langsung.
                        </p>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl shadow-[0_6px_18px_-8px_rgba(9,58,37,0.18)] flex items-start gap-3.5">
                    <div class="w-9 h-9 rounded-lg bg-[#23533D] text-white flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <div>
                        <h3 class="text-[13px] font-semibold text-[#111C16]">Pencatatan Dampak Riil</h3>
                        <p class="text-[11px] text-[#5B6860] leading-relaxed mt-0.5">
                            Dapatkan metrik estimasi limbah teralihkan dan reduksi emisi karbon untuk pelaporan keberlanjutan (ESG/CSR).
                        </p>
                    </div>
                </div>
            </div>

            <!-- Bagian bawah panel kiri: testimoni (login) -->
            <div x-show="tab === 'login'" class="relative z-10 mt-8">
                <div class="bg-white rounded-xl p-5 shadow-[0_6px_18px_-8px_rgba(9,58,37,0.18)]">
                    <span class="text-[#E0623A] font-serif text-2xl font-black leading-none block mb-1">&rdquo;</span>
                    <p class="text-[12px] italic text-[#36423B] leading-relaxed mb-4">
                        &ldquo;SIRKULA membantu Hotel Sagara menyalurkan lebih dari 4 ton limbah organik dan kardus langsung ke pengolah lokal tanpa penumpukan.&rdquo;
                    </p>
                    <div class="flex items-end justify-between gap-3">
                        <div>
                            <p class="text-[12.5px] font-semibold text-[#111C16]">Rina Paramitha</p>
                            <p class="text-[10.5px] text-[#68756D] leading-snug">Sustainability Lead &mdash; Sagara Resort</p>
                        </div>
                        <div class="bg-[#E9EEE8] text-[#1D402E] font-mono-tech text-[9.5px] font-medium px-2.5 py-1.5 rounded-md leading-tight uppercase shrink-0">
                            4.2 Ton Alih<br>Limbah
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- PANEL KANAN: FORM LOGIN & REGISTER                           -->
        <!-- ============================================================ -->
        <div class="bg-white px-6 py-8 sm:px-10 lg:py-10 flex flex-col justify-center">

            <!-- TAB SWITCHER ("Masuk Mitra" vs "Daftar Baru") -->
            <div class="w-full max-w-[372px] mx-auto bg-[#E9EEE8] p-1 rounded-xl grid grid-cols-2 mb-7">
                <button
                    type="button"
                    @click="tab = 'login'"
                    :class="tab === 'login' ? 'bg-white text-[#0F3824] font-semibold shadow-[0_1px_4px_rgba(9,58,37,0.12)]' : 'text-[#526057] font-medium hover:text-[#0F3824]'"
                    class="py-2.5 text-[13px] rounded-lg transition-all duration-200 focus:outline-none"
                >
                    Masuk Mitra
                </button>
                <button
                    type="button"
                    @click="tab = 'register'"
                    :class="tab === 'register' ? 'bg-white text-[#0F3824] font-semibold shadow-[0_1px_4px_rgba(9,58,37,0.12)]' : 'text-[#526057] font-medium hover:text-[#0F3824]'"
                    class="py-2.5 text-[13px] rounded-lg transition-all duration-200 focus:outline-none"
                >
                    Daftar Baru
                </button>
            </div>

            <!-- ALERT ERROR VALIDASI GLOBAL -->
            @if ($errors->any())
                <div class="w-full mx-auto mb-5 bg-[#FDEEEA] border border-[#F6CFC4] text-[#9A2B12] px-4 py-3 rounded-lg text-xs font-medium flex items-start gap-2.5"
                    :class="tab === 'login' ? 'max-w-[372px]' : 'max-w-[502px]'">
                    <svg class="w-4 h-4 text-[#C4411E] shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- ======================================================== -->
            <!-- 1. FORM MASUK MITRA (LOGIN)                              -->
            <!-- ======================================================== -->
            <div x-show="tab === 'login'" class="w-full max-w-[372px] mx-auto" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="mb-6">
                    <h2 class="text-[22px] font-bold text-[#0F2A1D] tracking-[-0.02em]">Masuk ke Akun Mitra</h2>
                    <p class="text-[12px] text-[#637067] mt-1">Kelola informasi ketersediaan limbah bisnis Anda.</p>
                </div>

                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="auth_mode" value="login">

                    <!-- Email bisnis -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="login_email" class="text-[13px] font-semibold text-[#18221C]">
                                Email Bisnis / Organisasi <span class="text-[#C4241C]">*</span>
                            </label>
                            <span class="text-[10px] font-mono-tech text-[#5F6B63]">Domain Resmi Diutamakan</span>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#5F6B63]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <input
                                type="email"
                                name="email"
                                id="login_email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="email"
                                placeholder="sustainability@sagarahotel.com"
                                class="{{ $fieldLogin }} pl-10 pr-4"
                            >
                        </div>
                    </div>

                    <!-- Kata sandi -->
                    <div>
                        <label for="login_password" class="block text-[13px] font-semibold text-[#18221C] mb-1.5">
                            Kata Sandi <span class="text-[#C4241C]">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#5F6B63]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            <input
                                :type="showLoginPass ? 'text' : 'password'"
                                name="password"
                                id="login_password"
                                required
                                autocomplete="current-password"
                                placeholder="Minimal 8 karakter terproteksi"
                                class="{{ $fieldLogin }} pl-10 pr-16"
                            >
                            <button
                                type="button"
                                @click="showLoginPass = !showLoginPass"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[10.5px] font-mono-tech font-semibold tracking-wide text-[#23533D] hover:text-[#093A25]"
                                x-text="showLoginPass ? 'TUTUP' : 'LIHAT'"
                            ></button>
                        </div>
                    </div>

                    <!-- Ingat perangkat & lupa sandi -->
                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-[#C9D2C9]">
                            <span class="text-[12px] text-[#4A5750]">Ingat perangkat ini</span>
                        </label>
                        <a href="#" class="text-[12px] font-semibold text-[#0F3824] hover:underline">Lupa kata sandi?</a>
                    </div>

                    <!-- Tombol login -->
                    <button
                        type="submit"
                        class="w-full bg-[#23533D] hover:bg-[#093A25] text-white font-semibold text-[14px] h-11 rounded-lg shadow-sm transition flex items-center justify-center gap-2"
                    >
                        <span>Login</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>

                    <!-- Divider ATAU -->
                    {{-- <div class="relative flex items-center">
                        <div class="flex-grow border-t border-[#E3E9E3]"></div>
                        <span class="flex-shrink mx-3 text-[9.5px] font-mono-tech text-[#8C9890] uppercase tracking-widest">Atau</span>
                        <div class="flex-grow border-t border-[#E3E9E3]"></div>
                    </div> --}}

                    <!-- Masuk dengan Google Workspace -->
                    {{-- <a
                        href="{{ url('/auth/google') }}"
                        data-no-loader
                        class="w-full bg-[#EBEFEA] hover:bg-[#E0E7DF] text-[#18221C] font-semibold text-[13px] h-11 rounded-lg transition flex items-center justify-center gap-2.5"
                    >
                        <svg class="w-4 h-4" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z"/>
                            <path fill="#34A853" d="M12 24c3.3 0 6.08-1.09 8.11-2.96l-3.88-3.05c-1.08.72-2.45 1.16-4.23 1.16-3.26 0-6.02-2.2-7.01-5.16H1.01v3.14C3.03 21.14 7.21 24 12 24z"/>
                            <path fill="#FBBC05" d="M4.99 13.99c-.25-.72-.39-1.5-.39-2.31s.14-1.59.39-2.31V6.23H1.01C.37 7.51 0 8.95 0 11.68s.37 4.17 1.01 5.45l3.98-3.14z"/>
                            <path fill="#EA4335" d="M12 4.75c1.8 0 3.41.62 4.68 1.84l3.51-3.51C18.07 1.19 15.3 0 12 0 7.21 0 3.03 2.86 1.01 6.87l3.98 3.14c.99-2.96 3.75-5.26 7.01-5.26z"/>
                        </svg>
                        <span>Masuk dengan Google Workspace</span>
                    </a> --}}
                </form>

                <!-- Pindah ke tab register -->
                <div class="text-center mt-5">
                    <button
                        type="button"
                        @click="tab = 'register'"
                        class="text-[12px] font-medium text-[#2E523E] hover:text-[#0F3824] inline-flex items-center gap-1"
                    >
                        <span>Belum memiliki akun mitra? Daftarkan Bisnis / Hotel Anda</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>

                <!-- Kotak info untuk recycler & pengumpul -->
                <div class="mt-5 bg-[#EBEFEA] rounded-lg p-3.5 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-[#2E523E] shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-[11px] text-[#3B4740] leading-relaxed">
                        <strong class="font-bold text-[#18221C]">Bagi Recycler dan Pengumpul:</strong> Anda tidak memerlukan akun untuk mencari limbah. Anda dapat langsung menjelajahi katalog dan menghubungi mitra secara bebas.
                    </p>
                </div>

                <p class="text-center text-[11px] text-[#839087] mt-4">
                    Transaksi dan pengambilan dilakukan langsung dengan mitra.
                </p>
            </div>

            <!-- ======================================================== -->
            <!-- 2. FORM DAFTAR BARU (REGISTER)                           -->
            <!-- ======================================================== -->
            <div x-show="tab === 'register'" x-cloak class="w-full max-w-[502px] mx-auto" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="mb-4">
                    <h2 class="text-[22px] font-bold text-[#0F2A1D] tracking-[-0.02em]">Daftar ke Akun Mitra</h2>
                    <p class="text-[12px] text-[#637067] mt-1">Kelola informasi ketersediaan limbah bisnis Anda.</p>
                </div>

                <form action="{{ route('register') }}" method="POST" class="space-y-3.5">
                    @csrf
                    <input type="hidden" name="auth_mode" value="register">
                    <input type="hidden" name="role" value="mitra">

                    <!-- Baris 1: Nama bisnis & jenis usaha -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label for="reg_name" class="block text-[12px] font-semibold text-[#18221C] mb-1.5">
                                Nama Bisnis / Fasilitas <span class="text-[#C4241C]">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#6B776F]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </span>
                                <input
                                    type="text"
                                    name="name"
                                    id="reg_name"
                                    value="{{ old('name') }}"
                                    required
                                    placeholder="cth: Hotel Sagara Resort &amp; Spa"
                                    class="{{ $fieldReg }} pl-10 pr-3.5"
                                >
                            </div>
                        </div>

                        <div>
                            <label for="reg_jenis" class="block text-[12px] font-semibold text-[#18221C] mb-1.5">
                                Jenis Usaha / Kategori <span class="text-[#C4241C]">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#6B776F] pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                </span>
                                <select
                                    name="jenis_usaha"
                                    id="reg_jenis"
                                    required
                                    class="{{ $fieldReg }} pl-10 pr-8 appearance-none"
                                >
                                    <option value="" disabled @selected(! old('jenis_usaha'))>Pilih kategori fasilitas...</option>
                                    @foreach (['Hotel, Resort & Hospitality', 'Restoran, Kafe & Katering', 'Pabrik & Industri Pengolahan', 'Pusat Komersial & Retail'] as $opsiUsaha)
                                        <option value="{{ $opsiUsaha }}" @selected(old('jenis_usaha') === $opsiUsaha)>{{ $opsiUsaha }}</option>
                                    @endforeach
                                </select>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-[#6B776F] pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Baris 2: Email resmi / bisnis -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="reg_email" class="text-[12px] font-semibold text-[#18221C]">
                                Email Resmi / Bisnis <span class="text-[#C4241C]">*</span>
                            </label>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[#C9EBD2] text-[#0F3824] font-mono-tech text-[9.5px] font-semibold">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Domain Resmi Diutamakan
                            </span>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#6B776F]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <input
                                type="email"
                                name="email"
                                id="reg_email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                placeholder="sustainability@perusahaan.com"
                                class="{{ $fieldReg }} pl-10 pr-4"
                            >
                        </div>
                    </div>

                    <!-- Baris 3: WhatsApp PIC & kota/lokasi -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label for="reg_whatsapp" class="block text-[12px] font-semibold text-[#18221C] mb-1.5">
                                WhatsApp PIC Operasional <span class="text-[#C4241C]">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#6B776F]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                </span>
                                <input
                                    type="text"
                                    name="whatsapp"
                                    id="reg_whatsapp"
                                    value="{{ old('whatsapp') }}"
                                    required
                                    inputmode="tel"
                                    placeholder="+62 812-3456-7890"
                                    class="{{ $fieldReg }} pl-10 pr-3.5"
                                >
                            </div>
                            <p class="text-[10.5px] text-[#68756D] mt-1.5 flex items-center gap-1.5">
                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-4m0-4h.01"/></svg>
                                Untuk koordinasi recycler &amp; verifikasi darurat
                            </p>
                        </div>

                        <div>
                            <label for="reg_lokasi" class="block text-[12px] font-semibold text-[#18221C] mb-1.5">
                                Kota / Lokasi Fasilitas <span class="text-[#C4241C]">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#6B776F]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </span>
                                <input
                                    type="text"
                                    name="alamat_lengkap"
                                    id="reg_lokasi"
                                    value="{{ old('alamat_lengkap') }}"
                                    required
                                    placeholder="cth: Denpasar Selatan, Bali"
                                    class="{{ $fieldReg }} pl-10 pr-3.5"
                                >
                            </div>
                            <p class="text-[10.5px] text-[#68756D] mt-1.5 flex items-center gap-1.5">
                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" d="M12 3v3m0 12v3M3 12h3m12 0h3"/></svg>
                                Kecamatan dan Kabupaten/Kota
                            </p>
                        </div>
                    </div>

                    <!-- Baris 4: Kata sandi & konfirmasi -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label for="reg_password" class="block text-[12px] font-semibold text-[#18221C] mb-1.5">
                                Kata Sandi <span class="text-[#C4241C]">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#6B776F]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                </span>
                                <input
                                    :type="showRegPass ? 'text' : 'password'"
                                    name="password"
                                    id="reg_password"
                                    required
                                    minlength="8"
                                    autocomplete="new-password"
                                    placeholder="Minimal 8 karakter"
                                    class="{{ $fieldReg }} pl-10 pr-10"
                                >
                                <button type="button" @click="showRegPass = !showRegPass" class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#6B776F] hover:text-[#23533D]" :aria-label="showRegPass ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                                    <svg x-show="!showRegPass" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="showRegPass" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="reg_password_confirmation" class="block text-[12px] font-semibold text-[#18221C] mb-1.5">
                                Konfirmasi Kata Sandi <span class="text-[#C4241C]">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#6B776F]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </span>
                                <input
                                    :type="showRegConfirmPass ? 'text' : 'password'"
                                    name="password_confirmation"
                                    id="reg_password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Ulangi kata sandi"
                                    class="{{ $fieldReg }} pl-10 pr-10"
                                >
                                <button type="button" @click="showRegConfirmPass = !showRegConfirmPass" class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#6B776F] hover:text-[#23533D]" :aria-label="showRegConfirmPass ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                                    <svg x-show="!showRegConfirmPass" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="showRegConfirmPass" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Checkbox persetujuan -->
                    <div class="bg-[#EEF3EE] px-3.5 py-3 rounded-lg">
                        <label class="flex items-start gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" required class="mt-0.5 w-4 h-4 rounded border-[#C9D2C9] shrink-0">
                            <span class="text-[11px] text-[#3B4740] leading-relaxed">
                                Saya menyatakan bahwa material yang dipublikasikan adalah limbah riil dan bersedia dihubungi oleh recycler terdaftar demi kelancaran koordinasi sirkular.
                            </span>
                        </label>
                    </div>

                    <!-- Tombol daftar sekarang -->
                    <button
                        type="submit"
                        class="w-full bg-[#0C3B26] hover:bg-[#082A1B] text-white font-semibold text-[14px] h-11 rounded-lg shadow-sm transition flex items-center justify-center gap-2"
                    >
                        <span>Daftar Sekarang</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>

                <!-- Pindah ke tab login -->
                <div class="text-center mt-4">
                    <span class="text-[12px] text-[#637067]">Sudah memiliki akun mitra?</span>
                    <button
                        type="button"
                        @click="tab = 'login'"
                        class="text-[12px] font-bold text-[#0F3824] hover:underline ml-1 inline-flex items-center gap-0.5"
                    >
                        <span>Masuk ke Akun Anda</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7m0 0H8m9 0v9"/></svg>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- ============================================================ -->
    <!-- GLOBAL LOADING CONTROLLER                                    -->
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

            window.SirkulaLoader = { show: showLoader, hide: hideLoader };

            window.addEventListener('DOMContentLoaded', function () {
                setTimeout(hideLoader, 350);
            });

            document.addEventListener('click', function (e) {
                const link = e.target.closest('a');
                if (!link) return;

                const href = link.getAttribute('href');
                const target = link.getAttribute('target');

                if (
                    !href ||
                    href.startsWith('#') ||
                    href.startsWith('javascript:') ||
                    target === '_blank' ||
                    link.hasAttribute('data-no-loader') ||
                    e.ctrlKey || e.metaKey || e.shiftKey || e.button !== 0
                ) {
                    return;
                }

                e.preventDefault();
                showLoader();

                setTimeout(function () {
                    window.location.href = link.href;
                }, 380);
            });

            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (!form || form.hasAttribute('data-no-loader')) return;
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