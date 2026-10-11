@extends('layouts.app')

@section('content')
    <div class="bg-[#F6FBF5] min-h-screen pb-20">

        {{-- ========================================== --}}
        {{-- 1. HERO SECTION --}}
        {{-- ========================================== --}}
        <section class="pt-24 pb-20 px-4 sm:px-6 lg:px-8 text-center border-b border-[#EBEFEA]">
            <div class="max-w-4xl mx-auto">
                <span class="block font-mono text-[10px] font-bold uppercase tracking-widest text-[#1A4B36] mb-4">
                    Sirkula Movement &amp; Infrastructure
                </span>
                <h1 class="text-4xl md:text-5xl font-extrabold text-[#093A25] leading-tight mb-6">
                    Akselerasi Ekonomi Sirkular Tanpa Perantara
                </h1>
                <p class="text-base md:text-lg text-[#414943] leading-relaxed max-w-3xl mx-auto">
                    SIRKULA adalah infrastruktur pertukaran informasi limbah terbuka untuk menghubungkan pelaku industri,
                    perhotelan, komersial, dengan recycler dan pengolah lokal demi mewujudkan emisi nol bersih.
                </p>
            </div>
        </section>

        {{-- ========================================== --}}
        {{-- 2. BAGAIMANA SIRKULA BEKERJA (4 CARDS) --}}
        {{-- ========================================== --}}
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <span
                        class="block font-mono text-[10px] font-bold uppercase tracking-widest text-[#1A4B36] mb-3">Mekanisme
                        Transparansi</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-[#093A25]">Bagaimana SIRKULA Bekerja?</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Step 1 -->
                    <div class="bg-white rounded-[1.5rem] p-8 border border-[#EBEFEA] shadow-sm flex flex-col">
                        <div class="flex justify-between items-start mb-6">
                            <span class="text-2xl font-bold text-[#1A4B36] font-mono">01</span>
                            <div class="w-8 h-8 rounded-lg bg-[#F0F5F0] text-[#1A4B36] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-[#093A25] mb-3">Daftar &amp; Verifikasi</h3>
                        <p class="text-sm text-[#414943] leading-relaxed">Verifikasi profil legalitas instansi atau recycler
                            demi menjamin validitas dan kepatuhan transaksi logistik.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="bg-white rounded-[1.5rem] p-8 border border-[#EBEFEA] shadow-sm flex flex-col">
                        <div class="flex justify-between items-start mb-6">
                            <span class="text-2xl font-bold text-[#1A4B36] font-mono">02</span>
                            <div class="w-8 h-8 rounded-lg bg-[#F0F5F0] text-[#1A4B36] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-[#093A25] mb-3">Publikasikan Limbah</h3>
                        <p class="text-sm text-[#414943] leading-relaxed">Tulis rincian spesifikasi material limbah atau
                            pasang kebutuhan pencarian material yang Anda butuhkan.</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="bg-white rounded-[1.5rem] p-8 border border-[#EBEFEA] shadow-sm flex flex-col">
                        <div class="flex justify-between items-start mb-6">
                            <span class="text-2xl font-bold text-[#1A4B36] font-mono">03</span>
                            <div class="w-8 h-8 rounded-lg bg-[#F0F5F0] text-[#1A4B36] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-[#093A25] mb-3">Terhubung Langsung</h3>
                        <p class="text-sm text-[#414943] leading-relaxed">Hubungi narahubung utama secara instan via
                            WhatsApp. Tanpa potongan komisi operasional atau platform.</p>
                    </div>

                    <!-- Step 4 -->
                    <div class="bg-white rounded-[1.5rem] p-8 border border-[#EBEFEA] shadow-sm flex flex-col">
                        <div class="flex justify-between items-start mb-6">
                            <span class="text-2xl font-bold text-[#1A4B36] font-mono">04</span>
                            <div class="w-8 h-8 rounded-lg bg-[#F0F5F0] text-[#1A4B36] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-[#093A25] mb-3">Transaksi &amp; Dampak</h3>
                        <p class="text-sm text-[#414943] leading-relaxed">Sepakati kesepakatan logistik secara mandiri.
                            Perbarui status listing setelah material berhasil terangkut.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ========================================== --}}
        {{-- 3. MENGAPA PENTING & MANFAAT --}}
        {{-- ========================================== --}}
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-7xl mx-auto">

                <!-- Banner Hijau Gelap -->
                <div
                    class="bg-[#0A3221] rounded-[2rem] p-8 md:p-12 mb-20 flex flex-col lg:flex-row items-center gap-10 shadow-lg">
                    <div class="lg:w-1/2">
                        <h2 class="text-3xl font-extrabold text-white mb-4">Mengapa Ekonomi Sirkular Penting?</h2>
                        <p class="text-sm text-[#A7C9B5] leading-relaxed">
                            Metode pembuangan linier (kumpul-angkut-buang) membebani TPA daerah dan melepaskan emisi gas
                            rumah kaca yang tinggi. SIRKULA mendorong pengembalian material ke jalur daur ulang lokal untuk
                            menekan jejak karbon.
                        </p>
                    </div>
                    <div class="lg:w-1/2 flex flex-col sm:flex-row gap-5 w-full">
                        <!-- Stat 1 -->
                        <div class="bg-[#0E422C] rounded-2xl p-6 flex-1 border border-white/5">
                            <h3 class="text-3xl font-bold text-white font-mono mb-2">128+ Ton</h3>
                            <p class="text-xs text-[#A7C9B5]">Material Terdaur Ulang</p>
                        </div>
                        <!-- Stat 2 -->
                        <div class="bg-[#0E422C] rounded-2xl p-6 flex-1 border border-white/5">
                            <h3 class="text-3xl font-bold text-white font-mono mb-2">2,410 KG</h3>
                            <p class="text-xs text-[#A7C9B5]">CO₂e Berhasil Dihindari</p>
                        </div>
                    </div>
                </div>

                <!-- Manfaat untuk Bisnis -->
                <div class="text-center mb-12">
                    <span class="block font-mono text-[10px] font-bold uppercase tracking-widest text-[#1A4B36] mb-3">Nilai
                        Tambah Industri</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-[#093A25]">Manfaat untuk Bisnis Anda</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Manfaat 1 -->
                    <div class="bg-white rounded-[1.5rem] p-8 border border-[#EBEFEA] shadow-sm">
                        <div class="w-10 h-10 mb-5 text-[#1A4B36]">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"
                                    class="hidden" />
                            </svg>
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"
                                    class="hidden" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"
                                    class="hidden" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                    class="hidden" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"
                                    class="hidden" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                                    class="hidden" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"
                                    class="hidden" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                    class="hidden" />
                                <!-- Leaf Icon -->
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z" />
                                    <path d="M2 22l8-8" />
                                </svg>
                        </div>
                        <h3 class="text-lg font-bold text-[#093A25] mb-3">Efisiensi Biaya TPA</h3>
                        <p class="text-sm text-[#414943] leading-relaxed">Kurangi beban pengeluaran retribusi pembuangan
                            sampah ke TPA dengan menyalurkan limbah terpilah ke recycler lokal.</p>
                    </div>

                    <!-- Manfaat 2 -->
                    <div class="bg-white rounded-[1.5rem] p-8 border border-[#EBEFEA] shadow-sm">
                        <div class="w-10 h-10 mb-5 text-[#1A4B36]">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z" />
                                <path d="M2 22l8-8" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-[#093A25] mb-3">Laporan Kinerja LST</h3>
                        <p class="text-sm text-[#414943] leading-relaxed">Data tonase sirkulasi material di platform dapat
                            dimanfaatkan sebagai basis pelaporan ESG (Lingkungan, Sosial, dan Tata Kelola).</p>
                    </div>

                    <!-- Manfaat 3 -->
                    <div class="bg-white rounded-[1.5rem] p-8 border border-[#EBEFEA] shadow-sm">
                        <div class="w-10 h-10 mb-5 text-[#1A4B36]">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z" />
                                <path d="M2 22l8-8" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-[#093A25] mb-3">Koneksi Terverifikasi</h3>
                        <p class="text-sm text-[#414943] leading-relaxed">Minimalkan risiko penipuan logistik dengan
                            jaringan mitra yang telah divalidasi oleh sistem kurasi SIRKULA.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ========================================== --}}
        {{-- 4. BOTTOM CTA --}}
        {{-- ========================================== --}}
        {{-- ========================================== --}}
        {{-- 4. BOTTOM CTA --}}
        {{-- ========================================== --}}
        <section class="px-4 sm:px-6 lg:px-8 mt-10 relative z-10 -mb-28">
            <div class="max-w-7xl mx-auto bg-[#204E3A] rounded-[2rem] p-12 text-center shadow-lg border border-[#1A4B36]">
                <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">Mulai Langkah Sirkular Anda Hari Ini</h2>
                <p class="text-sm text-[#A7C9B5] mb-8 max-w-xl mx-auto">
                    Publikasikan limbah operasional Anda atau temukan material pasokan terpilah dalam hitungan menit secara
                    mandiri.
                </p>

                {{-- Logika Dinamis Tombol CTA --}}
                @auth
                    {{-- Jika User Sudah Login --}}
                    <a href="{{ route('laporan.create') }}"
                        class="inline-block bg-white text-[#093A25] font-bold text-sm px-8 py-4 rounded-xl hover:bg-gray-100 transition-colors shadow-sm">
                        Mulai Ajukan Limbah mu!
                    </a>
                @else
                    {{-- Jika User Belum Login (Guest) --}}
                    <a href="{{ route('register') }}"
                        class="inline-block bg-white text-[#093A25] font-bold text-sm px-8 py-4 rounded-xl hover:bg-gray-100 transition-colors shadow-sm">
                        Daftar Sekarang Sebagai Mitra
                    </a>
                @endauth

            </div>
        </section>

    </div>
@endsection
