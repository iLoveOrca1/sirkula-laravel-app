@extends('layouts.app')

@section('content')
<div class="bg-[#F6FBF5] min-h-screen pb-24">

    {{-- ========================================== --}}
    {{-- 1. HEADER & SEARCH BAR --}}
    {{-- ========================================== --}}
    <section class="pt-20 pb-16 px-4 sm:px-6 lg:px-8 text-center">
        <div class="max-w-3xl mx-auto">
            <span class="block font-mono text-[10px] font-bold uppercase tracking-widest text-[#1A4B36] mb-3">
                Support Portal Sirkula
            </span>
            <h1 class="text-3xl md:text-[40px] font-extrabold text-[#093A25] leading-tight mb-4">
                Pusat Bantuan Mitra
            </h1>
            <p class="text-sm md:text-base text-[#414943] leading-relaxed max-w-xl mx-auto mb-10">
                Temukan jawaban cepat atas pertanyaan Anda seputar pengoperasian platform, verifikasi akun, dan alur transaksi sirkular tanpa komisi.
            </p>

            <!-- Search Bar -->
            <div class="relative max-w-2xl mx-auto">
                <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <input type="text" placeholder="Cari pertanyaan atau kata kunci bantuan..." 
                       class="w-full bg-white border border-[#EBEFEA] rounded-2xl py-3.5 pl-12 pr-4 text-sm text-[#414943] shadow-sm focus:ring-2 focus:ring-[#093A25] focus:border-[#093A25] outline-none transition-all">
            </div>
        </div>
    </section>

    {{-- ========================================== --}}
    {{-- 2. KUMPULAN FAQ (ACCORDION) --}}
    {{-- ========================================== --}}
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Grup 1: Umum & Cara Kerja -->
        <div class="bg-white rounded-2xl border border-[#EBEFEA] shadow-sm p-6 md:p-8" x-data="{ active: 1 }">
            <h2 class="text-lg font-bold text-[#093A25] mb-6">Umum &amp; Cara Kerja</h2>
            <div class="space-y-2">
                
                <!-- Q1 -->
                <div class="border-b border-gray-100 last:border-0 pb-2 last:pb-0">
                    <button @click="active === 1 ? active = 0 : active = 1" class="w-full flex items-center justify-between py-4 text-left focus:outline-none">
                        <span class="text-sm font-bold text-[#093A25]">Apa itu SIRKULA?</span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="active === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="active === 1" x-collapse x-cloak>
                        <p class="text-sm text-[#414943] pb-4 leading-relaxed">SIRKULA adalah platform B2B pertukaran limbah digital di Indonesia yang memfasilitasi koneksi langsung antara penghasil limbah industri/perhotelan dan pendaur ulang profesional.</p>
                    </div>
                </div>

                <!-- Q2 -->
                <div class="border-b border-gray-100 last:border-0 pb-2 last:pb-0">
                    <button @click="active === 2 ? active = 0 : active = 2" class="w-full flex items-center justify-between py-4 text-left focus:outline-none">
                        <span class="text-sm font-bold text-[#093A25]">Bagaimana cara kerja platform ini?</span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="active === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="active === 2" x-collapse x-cloak>
                        <p class="text-sm text-[#414943] pb-4 leading-relaxed">Penyedia mendaftarkan material limbah mereka di katalog. Recycler menyaring berdasarkan ketersediaan dan lokasi, lalu menghubungi penanggung jawab langsung lewat tautan WhatsApp.</p>
                    </div>
                </div>

                <!-- Q3 -->
                <div class="border-b border-gray-100 last:border-0 pb-2 last:pb-0">
                    <button @click="active === 3 ? active = 0 : active = 3" class="w-full flex items-center justify-between py-4 text-left focus:outline-none">
                        <span class="text-sm font-bold text-[#093A25]">Apakah pendaftaran SIRKULA berbayar?</span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="active === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="active === 3" x-collapse x-cloak>
                        <p class="text-sm text-[#414943] pb-4 leading-relaxed">Tidak, pendaftaran dan publikasi listing limbah dasar di SIRKULA sepenuhnya gratis untuk mendukung akselerasi ekonomi sirkular Indonesia.</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Grup 2: Akun & Registrasi Instansi -->
        <div class="bg-white rounded-2xl border border-[#EBEFEA] shadow-sm p-6 md:p-8" x-data="{ active: 0 }">
            <h2 class="text-lg font-bold text-[#093A25] mb-6">Akun &amp; Registrasi Instansi</h2>
            <div class="space-y-2">
                
                <!-- Q1 -->
                <div class="border-b border-gray-100 last:border-0 pb-2 last:pb-0">
                    <button @click="active === 1 ? active = 0 : active = 1" class="w-full flex items-center justify-between py-4 text-left focus:outline-none">
                        <span class="text-sm font-bold text-[#093A25]">Bagaimana cara memverifikasi akun instansi?</span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="active === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="active === 1" x-collapse x-cloak>
                        <p class="text-sm text-[#414943] pb-4 leading-relaxed">Unggah dokumen legalitas badan usaha (NIB/SIUP) melalui Dashboard Pengaturan Akun. Tim verifikator SIRKULA akan meninjau dalam 1x24 jam kerja.</p>
                    </div>
                </div>

                <!-- Q2 -->
                <div class="border-b border-gray-100 last:border-0 pb-2 last:pb-0">
                    <button @click="active === 2 ? active = 0 : active = 2" class="w-full flex items-center justify-between py-4 text-left focus:outline-none">
                        <span class="text-sm font-bold text-[#093A25]">Apakah satu instansi bisa memiliki beberapa PIC?</span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="active === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="active === 2" x-collapse x-cloak>
                        <p class="text-sm text-[#414943] pb-4 leading-relaxed">Ya, Anda dapat menambahkan beberapa narahubung operasional (PIC) dengan pembagian tugas area logistik masing-masing.</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Grup 3: Spesifikasi Limbah & Material -->
        <div class="bg-white rounded-2xl border border-[#EBEFEA] shadow-sm p-6 md:p-8" x-data="{ active: 0 }">
            <h2 class="text-lg font-bold text-[#093A25] mb-6">Spesifikasi Limbah &amp; Material</h2>
            <div class="space-y-2">
                
                <!-- Q1 -->
                <div class="border-b border-gray-100 last:border-0 pb-2 last:pb-0">
                    <button @click="active === 1 ? active = 0 : active = 1" class="w-full flex items-center justify-between py-4 text-left focus:outline-none">
                        <span class="text-sm font-bold text-[#093A25]">Jenis limbah apa saja yang boleh didaftarkan?</span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="active === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="active === 1" x-collapse x-cloak>
                        <p class="text-sm text-[#414943] pb-4 leading-relaxed">Kami menerima Limbah Organik (sisa makanan, agroindustri) dan Limbah Anorganik terpilah seperti plastik PET, kaca, kardus corrugated, minyak jelantah, dan tekstil.</p>
                    </div>
                </div>

                <!-- Q2 -->
                <div class="border-b border-gray-100 last:border-0 pb-2 last:pb-0">
                    <button @click="active === 2 ? active = 0 : active = 2" class="w-full flex items-center justify-between py-4 text-left focus:outline-none">
                        <span class="text-sm font-bold text-[#093A25]">Berapa batas minimum volume listing?</span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="active === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="active === 2" x-collapse x-cloak>
                        <p class="text-sm text-[#414943] pb-4 leading-relaxed">Demi efisiensi armada logistik daur ulang, disarankan batas volume minimum listing adalah 50 Kilogram per pengangkutan.</p>
                    </div>
                </div>

            </div>
        </div>

    </section>

    {{-- ========================================== --}}
    {{-- 3. BOTTOM CTA: MASIH BUTUH BANTUAN? --}}
    {{-- ========================================== --}}
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 mb-10">
        <div class="bg-white rounded-[2rem] border border-[#EBEFEA] shadow-sm p-8 md:p-12 flex flex-col md:flex-row md:items-center justify-between gap-8">
            
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-[#093A25] mb-3">Masih Butuh Bantuan?</h2>
                <p class="text-sm text-[#414943] leading-relaxed mb-5 max-w-sm">
                    Tim support sirkula siap mendampingi proses go-live instansi Anda dan kendala teknis penimbangan logistik di lapangan.
                </p>
                <div class="flex items-center gap-2 text-xs font-bold text-[#093A25]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Senin - Jumat, 09:00 - 17:00 WITA
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 shrink-0">
                <!-- WhatsApp Box -->
                <a href="https://wa.me/6281234567890" target="_blank" class="bg-[#F0F5F0] hover:bg-[#EBEFEA] transition-colors rounded-2xl p-5 flex flex-col items-start gap-4 border border-[#EBEFEA] min-w-[160px]">
                    <div class="w-8 h-8 rounded-full border border-green-500 text-green-500 flex items-center justify-center bg-white">
                        <!-- Icon WA/Chat Simple -->
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-[#093A25]">Support WhatsApp</h4>
                        <p class="text-[10px] font-mono text-gray-500 mt-1">+62 812-3456-7890</p>
                    </div>
                </a>

                <!-- Email Box -->
                <a href="mailto:bantuan@sirkula.id" class="bg-[#F0F5F0] hover:bg-[#EBEFEA] transition-colors rounded-2xl p-5 flex flex-col items-start gap-4 border border-[#EBEFEA] min-w-[160px]">
                    <div class="w-8 h-8 rounded-full border border-[#093A25] text-[#093A25] flex items-center justify-center bg-white">
                        <!-- Icon Email -->
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-[#093A25]">Email Support</h4>
                        <p class="text-[10px] font-mono text-gray-500 mt-1">bantuan@sirkula.id</p>
                    </div>
                </a>
            </div>

        </div>
    </section>

</div>
@endsection