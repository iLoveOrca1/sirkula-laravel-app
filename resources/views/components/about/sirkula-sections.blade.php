{{-- Font SIRKULA sesuai desain Figma: Plus Jakarta Sans untuk UI dan IBM Plex Mono untuk label/angka --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">

<style>
    /* Tipografi dasar agar konsisten di seluruh section halaman */
    section {
        font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .sirkula-mono {
        font-family: 'IBM Plex Mono', ui-monospace, monospace;
        font-variant-numeric: tabular-nums;
    }
</style>

{{-- ============================================================
     SECTION 01 — DIAGNOSA MASALAH
============================================================ --}}
<section class="py-20 md:py-28 bg-surface"
    style="font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;">
    <div class="max-w-7xl px-6 md:px-8 mx-auto">

        <span class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3 sirkula-mono">
            01 / Diagnosa Masalah
        </span>
        <h2 class="text-3xl md:text-4xl font-extrabold text-[#093A25] mb-4 tracking-tight">Ketimpangan Rantai Pasok
            Limbah</h2>
        <p class="max-w-xl text-sm text-[#414943] leading-relaxed mb-12">
            Kami membedah hambatan operasional nyata yang dihadapi kedua belah pihak di lapangan sebelum merancang
            sistem penemuan ini.
        </p>

        <div class="grid md:grid-cols-2 gap-6 md:gap-8">
            {{-- Card: Penghasil Limbah --}}
            <div
                class="bg-white rounded-[2rem] p-8 md:p-12 shadow-sm border border-[#EBEFEA] flex flex-col transition hover:shadow-md">
                <div
                    class="w-12 h-12 rounded-xl bg-[#EBEFEA] text-[#093A25] flex items-center justify-center mb-8 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#093A25] mb-4 tracking-tight">Tantangan Penghasil Limbah</h3>
                <p class="text-sm text-[#414943] leading-relaxed mb-6">
                    Hotel, resort, restoran, dan fasilitas perakitan sering kali menghadapi tumpukan limbah residu
                    bernilai tinggi namun kebingungan mendistribusikannya secara teratur:
                </p>
                <ul class="space-y-4 text-sm text-[#414943] mb-10 flex-1">
                    <li class="flex gap-3 items-start">
                        <svg class="w-5 h-5 text-[#093A25] shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                        <span>Beban tipping fee dan ongkos angkut pembuangan akhir yang terus melonjak tiap
                            bulan.</span>
                    </li>
                    <li class="flex gap-3 items-start">
                        <svg class="w-5 h-5 text-[#093A25] shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                        <span>Ketiadaan database terpercaya mengenai recycler khusus (minyak jelantah, ampas kopi,
                            karton bal).</span>
                    </li>
                    <li class="flex gap-3 items-start">
                        <svg class="w-5 h-5 text-[#093A25] shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                        <span>Keterbatasan ruang penyimpanan loading dock yang memicu risiko sanitasi jika material
                            lambat dijemput.</span>
                    </li>
                </ul>
                <div class="bg-[#F0F5F0] rounded-xl px-5 py-4 border border-[#EBEFEA] mt-auto">
                    <span
                        class="block text-[10px] font-bold tracking-widest uppercase text-gray-500 mb-1.5 sirkula-mono">Status
                        Lapangan</span>
                    <p class="text-sm font-semibold text-[#093A25] leading-relaxed">Material terpilah kerap berakhir
                        dicampur kembali oleh armada angkut konvensional.</p>
                </div>
            </div>

            {{-- Card: Pengolah & Recycler --}}
            <div
                class="bg-white rounded-[2rem] p-8 md:p-12 shadow-sm border border-[#EBEFEA] flex flex-col transition hover:shadow-md">
                <div
                    class="w-12 h-12 rounded-xl bg-[#EBEFEA] text-[#093A25] flex items-center justify-center mb-8 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#093A25] mb-4 tracking-tight">Tantangan Pengolah &amp; Recycler</h3>
                <p class="text-sm text-[#414943] leading-relaxed mb-6">
                    Pabrik daur ulang, komposter organik, sentra maggot BSF, dan bank sampah justru beroperasi di bawah
                    kapasitas mesin akibat defisit pasokan:
                </p>
                <ul class="space-y-4 text-sm text-[#414943] mb-10 flex-1">
                    <li class="flex gap-3 items-start">
                        <svg class="w-5 h-5 text-[#093A25] shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                        <span>Ketiadaan visibilitas jadwal panen atau siklus pembuangan berkala dari fasilitas
                            komersial.</span>
                    </li>
                    <li class="flex gap-3 items-start">
                        <svg class="w-5 h-5 text-[#093A25] shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                        <span>Margin tergerus oleh rantai tengkulak atau makelar tidak resmi yang menaikkan biaya
                            logistik.</span>
                    </li>
                    <li class="flex gap-3 items-start">
                        <svg class="w-5 h-5 text-[#093A25] shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                        <span>Spesifikasi material yang tidak akurat (kelembapan berlebih, kontaminasi kotoran) saat
                            tiba di gudang.</span>
                    </li>
                </ul>
                <div class="bg-[#C6ECCD] rounded-xl px-5 py-4 border border-[#b2d9ba] mt-auto">
                    <span
                        class="block text-[10px] font-bold tracking-widest uppercase text-[#093A25] mb-1.5 sirkula-mono">Peran
                        Sirkula</span>
                    <p class="text-sm font-semibold text-[#093A25] leading-relaxed">Menyediakan layer transparansi
                        terverifikasi agar pasokan dapat diserap tepat waktu.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     SECTION 02 — LANDASAN KERJA
============================================================ --}}
<section class="py-20 md:py-28 bg-background"
    style="font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;">
    <div class="max-w-7xl mx-auto px-6 md:px-8">

        <span class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3 sirkula-mono">
            02 / Landasan Kerja
        </span>
        <h2 class="text-3xl md:text-4xl font-extrabold text-[#093A25] mb-4 tracking-tight">Prinsip Pokok SIRKULA</h2>
        <p class="max-w-xl text-sm text-[#414943] leading-relaxed mb-12">
            Kami menolak skema pasar tertutup yang mengeksploitasi data komoditas daur ulang. Operasional kami
            berlandaskan 3 kedaulatan utama:
        </p>

        <div class="grid md:grid-cols-3 gap-6">

            <!-- Card 1 -->
            <div
                class="bg-white rounded-[2rem] p-8 md:p-10 shadow-sm border border-[#EBEFEA] flex flex-col transition hover:-translate-y-1 hover:shadow-md">
                <span class="text-2xl font-extrabold text-[#093A25] mb-6 sirkula-mono">01</span>
                <h3 class="text-lg font-bold text-[#093A25] mb-4 leading-tight tracking-tight">Independensi Transaksi
                    Direct-to-Mitra</h3>
                <p class="text-sm text-[#414943] leading-relaxed mb-10 flex-1">
                    Bukan sistem e-commerce berbayar atau penampung monopoli. Seluruh komunikasi, negosiasi harga, dan
                    jadwal armada jemput dilakukan langsung antara mitra via WhatsApp atau kanal resmi masing-masing.
                </p>
                <div class="bg-[#F0F5F0] rounded-xl px-5 py-4 border border-[#EBEFEA] mt-auto">
                    <span
                        class="block text-[10px] font-bold tracking-widest uppercase text-gray-500 mb-1.5 sirkula-mono">Protokol</span>
                    <p class="text-xs font-bold text-[#093A25]">Bebas komisi, tanpa deposit wajib</p>
                </div>
            </div>

            <!-- Card 2 -->
            <div
                class="bg-white rounded-[2rem] p-8 md:p-10 shadow-sm border border-[#EBEFEA] flex flex-col transition hover:-translate-y-1 hover:shadow-md">
                <span class="text-2xl font-extrabold text-[#093A25] mb-6 sirkula-mono">02</span>
                <h3 class="text-lg font-bold text-[#093A25] mb-4 leading-tight tracking-tight">Transparansi Parameter
                    Teknis</h3>
                <p class="text-sm text-[#414943] leading-relaxed mb-10 flex-1">
                    Setiap listing wajib mencantumkan data terukur: massa (kg/ton), frekuensi timbulan
                    (harian/mingguan), jenis wadah (jerrycan, sack, baler), serta dokumentasi aktual dari loading dock
                    fasilitas.
                </p>
                <div class="bg-[#F0F5F0] rounded-xl px-5 py-4 border border-[#EBEFEA] mt-auto">
                    <span
                        class="block text-[10px] font-bold tracking-widest uppercase text-gray-500 mb-1.5 sirkula-mono">Standar
                        Mutu</span>
                    <p class="text-xs font-bold text-[#093A25]">Katalog berbasis inspeksi visual asli</p>
                </div>
            </div>

            <!-- Card 3 -->
            <div
                class="bg-white rounded-[2rem] p-8 md:p-10 shadow-sm border border-[#EBEFEA] flex flex-col transition hover:-translate-y-1 hover:shadow-md">
                <span class="text-2xl font-extrabold text-[#093A25] mb-6 sirkula-mono">03</span>
                <h3 class="text-lg font-bold text-[#093A25] mb-4 leading-tight tracking-tight">Prioritas Sirkulasi
                    Radius Logistik</h3>
                <p class="text-sm text-[#414943] leading-relaxed mb-10 flex-1">
                    Efisiensi emisi karbon adalah inti sirkularitas. Sistem kami mengutamakan pencocokan limbah dalam
                    perimeter terdekat (&lt; 25 km) agar biaya angkut dan emisi gas buang armada muat tetap seminimal
                    mungkin.
                </p>
                <div class="bg-[#F0F5F0] rounded-xl px-5 py-4 border border-[#EBEFEA] mt-auto">
                    <span
                        class="block text-[10px] font-bold tracking-widest uppercase text-gray-500 mb-1.5 sirkula-mono">Dampak
                        Emisi</span>
                    <p class="text-xs font-bold text-[#093A25]">Mengurangi mobilitas lintas pulau</p>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ============================================================
     SECTION 03 — MODEL ALIRAN
============================================================ --}}
<section class="py-20 md:py-28 bg-surface"
    style="font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;">
    <div class="max-w-7xl mx-auto px-6 md:px-8">

        <div class="text-center mb-16">
            <span class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3 sirkula-mono">
                03 / Model Aliran
            </span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-[#093A25] mb-4 tracking-tight">Arsitektur Ekosistem
                SIRKULA</h2>
            <p class="max-w-2xl mx-auto text-sm text-[#414943] leading-relaxed">
                Kami memposisikan platform sebagai lapisan penemu informasi murni, menjamin kepemilikan material tetap
                berada di tangan mitra penghasil dan pengolah.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-6 mb-8 text-left">
            <!-- Step 1 -->
            <div class="bg-white rounded-3xl p-8 md:p-10 shadow-sm border border-[#EBEFEA]">
                <div
                    class="w-10 h-10 rounded-full bg-[#1A4B36] text-white flex items-center justify-center text-sm font-bold mb-8 sirkula-mono">
                    1</div>
                <h3 class="text-xl font-bold text-[#093A25] mb-4 tracking-tight">Listing &amp; Verifikasi</h3>
                <p class="text-sm text-[#414943] leading-relaxed mb-8">
                    Mitra mengunggah spesifikasi material terpilah, perkiraan tonase rutin, waktu muat, dan foto kondisi
                    lapangan melalui formulir mitra terbuka.
                </p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 sirkula-mono">
                    Output: <span class="text-[#093A25]">Profil Publik Terverifikasi</span>
                </p>
            </div>

            <!-- Step 2 -->
            <div class="bg-white rounded-3xl p-8 md:p-10 shadow-sm border border-[#EBEFEA]">
                <div
                    class="w-10 h-10 rounded-full bg-[#1A4B36] text-white flex items-center justify-center text-sm font-bold mb-8 sirkula-mono">
                    2</div>
                <h3 class="text-xl font-bold text-[#093A25] mb-4 tracking-tight">Koneksi Langsung</h3>
                <p class="text-sm text-[#414943] leading-relaxed mb-8">
                    Pengolah meninjau katalog, mengecek kesesuaian lokasi via peta, dan mengklik tautan komunikasi
                    langsung menuju narahubung logistik mitra via WhatsApp.
                </p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 sirkula-mono">
                    Output: <span class="text-[#093A25]">Negosiasi Mandiri Bebas Biaya</span>
                </p>
            </div>

            <!-- Step 3 -->
            <div class="bg-white rounded-3xl p-8 md:p-10 shadow-sm border border-[#EBEFEA]">
                <div
                    class="w-10 h-10 rounded-full bg-[#1A4B36] text-white flex items-center justify-center text-sm font-bold mb-8 sirkula-mono">
                    3</div>
                <h3 class="text-xl font-bold text-[#093A25] mb-4 tracking-tight">Penjemputan &amp; Sirkulasi</h3>
                <p class="text-sm text-[#414943] leading-relaxed mb-8">
                    Armada pengolah menjemput material di loading dock sesuai janji temu. Limbah langsung
                    bertransformasi menjadi pakan larva, pupuk organik, atau bahan baku industri.
                </p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 sirkula-mono">
                    Output: <span class="text-[#093A25]">Sirkularitas Nyata di Lapangan</span>
                </p>
            </div>
        </div>

        {{-- Banner Non-Escrow --}}
        <div
            class="bg-[#F0F5F0] rounded-2xl px-6 md:px-8 py-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4 border border-[#EBEFEA]">
            <div class="flex items-center gap-4">
                <div
                    class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shrink-0 shadow-sm border border-[#EBEFEA]">
                    <svg class="w-5 h-5 text-[#093A25]" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-[#093A25] tracking-tight">Bukan Perantara Pembayaran (Non-Escrow)
                    </h4>
                    <p class="text-xs text-[#414943] mt-0.5">Kesepakatan harga jual/beli material, penagihan, maupun
                        dokumen jalan dikelola mandiri oleh kedua belah pihak.</p>
                </div>
            </div>
            <span
                class="shrink-0 inline-flex items-center gap-1.5 bg-white border border-[#EBEFEA] rounded-full px-4 py-1.5 text-[10px] font-bold uppercase tracking-widest text-gray-600 shadow-sm sirkula-mono">
                Model Terbuka &amp; Aman
            </span>
        </div>

    </div>
</section>

{{-- ============================================================
     SECTION 04 — KEMITRAAN & PRAKTIK LAPANGAN
============================================================ --}}
<section class="py-20 md:py-28 bg-background"
    style="font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;">
    <div class="max-w-7xl mx-auto px-6 md:px-8">
        <div class="grid md:grid-cols-2 gap-12 md:gap-20 items-center">

            {{-- Image Panel --}}
            <div class="relative">
                <div
                    class="bg-[#EBEFEA] rounded-[2rem] aspect-[4/5] md:aspect-square w-full overflow-hidden shadow-sm">
                    <!-- Placeholder Abu-abu terang persis desain Figma -->
                    <div class="w-full h-full bg-[#EBEFEA]"></div>
                </div>

                {{-- Floating Card --}}
                <div
                    class="absolute -bottom-6 left-6 md:-right-6 md:left-auto bg-white rounded-2xl shadow-lg px-6 py-5 w-72 border border-[#EBEFEA]">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-[#093A25] shrink-0" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                        <h4 class="text-sm font-bold text-[#093A25] tracking-tight">Inisiatif Perhotelan Berkelanjutan
                        </h4>
                    </div>
                    <p class="text-xs text-[#414943] leading-relaxed">Kolaborasi aktif bersama manajer fasilitas resort
                        dan F&amp;B di Bali dan Jawa Timur.</p>
                </div>
            </div>

            {{-- Text & Quote Panel --}}
            <div class="pt-10 md:pt-0">
                <span class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-6 sirkula-mono">
                    04 / Kemitraan &amp; Praktik Lapangan
                </span>

                <h2 class="text-[28px] md:text-4xl font-extrabold text-[#093A25] leading-tight mb-8 tracking-tight">
                    "Sirkularitas bukan tentang laporan ESG di atas kertas. Ini tentang material yang benar-benar
                    terangkut dari loading dock setiap jam 7 pagi."
                </h2>

                <p class="text-sm text-[#414943] leading-relaxed mb-6">
                    Pengelolaan limbah komersial skala besar menuntut kehandalan operasional harian. Ketika hotel
                    bintang lima memisahkan sisa dapur atau drum minyak bekas, mereka memerlukan kepastian bahwa
                    pengolah lokal siap menjemput material tersebut sebelum menciptakan kendala sanitasi di fasilitas
                    tamu.
                </p>
                <p class="text-sm text-[#414943] leading-relaxed mb-10">
                    SIRKULA hadir untuk menghargai komitmen operasional tersebut. Dengan memberikan transparansi kanal
                    langsung kepada pengolah terdekat, limbah bernilai dialirkan kembali ke rantai ekonomi produktif
                    tanpa komplikasi birokrasi.
                </p>

                <div class="flex flex-col sm:flex-row sm:items-center gap-6 border-t border-[#EBEFEA] pt-6">
                    <div class="border-l-2 border-[#093A25] pl-4">
                        <p class="text-sm font-bold text-[#093A25]">Dewi Ratnasari</p>
                        <p class="text-xs text-gray-500 mt-0.5">Koordinator Advokasi Sirkular &amp; Fasilitas Mitra</p>
                    </div>
                    <div class="hidden sm:block w-px h-8 bg-[#EBEFEA]"></div>
                    <div
                        class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 sirkula-mono">
                        <svg class="w-4 h-4 text-[#093A25]" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Protokol Verifikasi 2025
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ============================================================
     SECTION 05 — KOMITMEN PLATFORM
============================================================ --}}
<section class="py-20 md:py-28 bg-surface"
    style="font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;">
    <div class="max-w-7xl mx-auto px-6 md:px-8">

        <div class="mb-14">
            <span class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3 sirkula-mono">
                05 / Komitmen Platform
            </span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-[#093A25] mb-4 tracking-tight">Standar Operasional
                SIRKULA</h2>
            <p class="max-w-xl text-sm text-[#414943] leading-relaxed">
                Kami menetapkan patokan ketat demi menjaga kenyamanan data dan kepastian operasional seluruh pemangku
                kepentingan.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">

            <div
                class="bg-white rounded-[2rem] p-8 md:p-10 shadow-sm border border-[#EBEFEA] transition hover:shadow-md">
                <div
                    class="w-12 h-12 rounded-xl bg-[#F0F5F0] text-[#093A25] flex items-center justify-center mb-8 border border-[#EBEFEA]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-[#093A25] mb-3 tracking-tight">Akses Data Terbuka</h3>
                <p class="text-sm text-[#414943] leading-relaxed">
                    Data jenis limbah dan titik koordinat kasar disediakan bebas bagi akademisi, inovator bahan baku
                    daur ulang, dan pengolah berskala UMKM demi kemajuan ekonomi sirkular nasional.
                </p>
            </div>

            <div
                class="bg-white rounded-[2rem] p-8 md:p-10 shadow-sm border border-[#EBEFEA] transition hover:shadow-md">
                <div
                    class="w-12 h-12 rounded-xl bg-[#F0F5F0] text-[#093A25] flex items-center justify-center mb-8 border border-[#EBEFEA]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-[#093A25] mb-3 tracking-tight">Keamanan Kontak Mitra</h3>
                <p class="text-sm text-[#414943] leading-relaxed">
                    Nomor perwakilan hanya dibuka untuk maksud pengambilan logistik valid. Kami secara aktif menyaring
                    upaya spam komersial dan penawaran pihak ketiga tak berizin.
                </p>
            </div>

            <div
                class="bg-white rounded-[2rem] p-8 md:p-10 shadow-sm border border-[#EBEFEA] transition hover:shadow-md">
                <div
                    class="w-12 h-12 rounded-xl bg-[#F0F5F0] text-[#093A25] flex items-center justify-center mb-8 border border-[#EBEFEA]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-[#093A25] mb-3 tracking-tight">Pembaruan Berkala 48 Jam</h3>
                <p class="text-sm text-[#414943] leading-relaxed">
                    Katalog kami diarsip secara otomatis jika material telah berstatus "Sudah Diambil", memastikan
                    pengolah tidak membuang armada untuk stok yang tidak lagi tersedia.
                </p>
            </div>

        </div>
    </div>
</section>

{{-- ============================================================
     SECTION 06 — PERTANYAAN UMUM + CTA
============================================================ --}}
<section class="py-20 md:py-28 bg-background"
    style="font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;">
    <div class="max-w-4xl mx-auto px-6 md:px-8" x-data="{ activeAccordion: 1 }">

        <div class="text-center mb-16">
            <span class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3 sirkula-mono">
                06 / Pertanyaan Umum
            </span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-[#093A25] mb-4 tracking-tight">Menjawab Keraguan Anda
            </h2>
            <p class="text-sm text-[#414943]">Transparansi adalah inti hubungan kami dengan mitra penghasil dan pendaur
                ulang.</p>
        </div>

        {{-- Accordion FAQ --}}
        <div class="space-y-4 mb-24">

            <!-- FAQ 1 -->
            <div
                class="bg-white rounded-2xl border border-[#EBEFEA] shadow-sm overflow-hidden transition-all duration-300">
                <button @click="activeAccordion === 1 ? activeAccordion = 0 : activeAccordion = 1"
                    class="w-full flex items-center justify-between gap-4 px-6 md:px-8 py-5 text-left focus:outline-none focus-visible:bg-[#F0F5F0]">
                    <span class="text-sm md:text-base font-bold text-[#093A25] tracking-tight">Mengapa transaksi dan
                        kesepakatan diarahkan langsung via WhatsApp?</span>
                    <div class="shrink-0 w-8 h-8 flex items-center justify-center rounded-full transition-colors duration-300"
                        :class="activeAccordion === 1 ? 'bg-[#F0F5F0]' : 'bg-transparent'">
                        <svg class="w-5 h-5 text-[#093A25] transition-transform duration-300"
                            :class="activeAccordion === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </button>
                <div x-show="activeAccordion === 1" x-collapse x-cloak>
                    <div class="px-6 md:px-8 pb-6 pt-2">
                        <p class="text-sm text-[#414943] leading-relaxed">SIRKULA bukan perantara pembayaran.
                            Komunikasi langsung menjaga independensi negosiasi harga dan menjadwalkan armada tanpa
                            potongan biaya platform.</p>
                    </div>
                </div>
            </div>

            <!-- FAQ 2 -->
            <div
                class="bg-white rounded-2xl border border-[#EBEFEA] shadow-sm overflow-hidden transition-all duration-300">
                <button @click="activeAccordion === 2 ? activeAccordion = 0 : activeAccordion = 2"
                    class="w-full flex items-center justify-between gap-4 px-6 md:px-8 py-5 text-left focus:outline-none focus-visible:bg-[#F0F5F0]">
                    <span class="text-sm md:text-base font-bold text-[#093A25] tracking-tight">Apakah SIRKULA memungut
                        biaya atau komisi potongan transaksi?</span>
                    <div class="shrink-0 w-8 h-8 flex items-center justify-center rounded-full transition-colors duration-300"
                        :class="activeAccordion === 2 ? 'bg-[#F0F5F0]' : 'bg-transparent'">
                        <svg class="w-5 h-5 text-[#093A25] transition-transform duration-300"
                            :class="activeAccordion === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </button>
                <div x-show="activeAccordion === 2" x-collapse x-cloak>
                    <div class="px-6 md:px-8 pb-6 pt-2">
                        <p class="text-sm text-[#414943] leading-relaxed">Tidak. Seluruh kesepakatan harga jual/beli
                            material, penagihan, maupun dokumen jalan dikelola mandiri oleh kedua belah pihak tanpa
                            komisi atau deposit wajib.</p>
                    </div>
                </div>
            </div>

            <!-- FAQ 3 -->
            <div
                class="bg-white rounded-2xl border border-[#EBEFEA] shadow-sm overflow-hidden transition-all duration-300">
                <button @click="activeAccordion === 3 ? activeAccordion = 0 : activeAccordion = 3"
                    class="w-full flex items-center justify-between gap-4 px-6 md:px-8 py-5 text-left focus:outline-none focus-visible:bg-[#F0F5F0]">
                    <span class="text-sm md:text-base font-bold text-[#093A25] tracking-tight">Jenis limbah apa saja
                        yang memenuhi syarat untuk dipublikasikan?</span>
                    <div class="shrink-0 w-8 h-8 flex items-center justify-center rounded-full transition-colors duration-300"
                        :class="activeAccordion === 3 ? 'bg-[#F0F5F0]' : 'bg-transparent'">
                        <svg class="w-5 h-5 text-[#093A25] transition-transform duration-300"
                            :class="activeAccordion === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </button>
                <div x-show="activeAccordion === 3" x-collapse x-cloak>
                    <div class="px-6 md:px-8 pb-6 pt-2">
                        <p class="text-sm text-[#414943] leading-relaxed">Material residu bernilai daur ulang seperti
                            minyak jelantah, ampas kopi, karton bal, sisa dapur terpilah, dan bahan baku industri lain
                            yang lolos verifikasi parameter teknis.</p>
                    </div>
                </div>
            </div>

            <!-- FAQ 4 -->
            <div
                class="bg-white rounded-2xl border border-[#EBEFEA] shadow-sm overflow-hidden transition-all duration-300">
                <button @click="activeAccordion === 4 ? activeAccordion = 0 : activeAccordion = 4"
                    class="w-full flex items-center justify-between gap-4 px-6 md:px-8 py-5 text-left focus:outline-none focus-visible:bg-[#F0F5F0]">
                    <span class="text-sm md:text-base font-bold text-[#093A25] tracking-tight">Bagaimana langkah
                        mendaftarkan fasilitas bisnis kami sebagai mitra penghasil?</span>
                    <div class="shrink-0 w-8 h-8 flex items-center justify-center rounded-full transition-colors duration-300"
                        :class="activeAccordion === 4 ? 'bg-[#F0F5F0]' : 'bg-transparent'">
                        <svg class="w-5 h-5 text-[#093A25] transition-transform duration-300"
                            :class="activeAccordion === 4 ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </button>
                <div x-show="activeAccordion === 4" x-collapse x-cloak>
                    <div class="px-6 md:px-8 pb-6 pt-2">
                        <p class="text-sm text-[#414943] leading-relaxed">Isi formulir mitra terbuka dengan spesifikasi
                            material, perkiraan tonase rutin, waktu muat, dan foto kondisi lapangan. Profil publik Anda
                            akan terverifikasi sebelum tampil di katalog.</p>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- Bottom CTA Banner --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div
            class="relative overflow-hidden rounded-[2.5rem] bg-[#1A4B36] p-10 md:p-16 shadow-md border border-[#1A4B36]">
            {{-- Dekorasi Lingkaran (Mirip Figma) --}}
            <div
                class="absolute -right-20 -bottom-20 w-[400px] h-[400px] border-[50px] border-[#093A25]/40 rounded-full">
            </div>
            <div class="absolute right-40 top-10 w-[100px] h-[100px] bg-white/5 rounded-full blur-2xl"></div>

            <div class="relative z-10 max-w-2xl">
                <span
                    class="inline-block bg-white/10 text-white/90 text-[10px] font-bold tracking-[0.1em] uppercase rounded-full px-3.5 py-1.5 mb-6 backdrop-blur-sm sirkula-mono">
                    Aksi Kolaborasi Nyata
                </span>

                <h3 class="text-3xl md:text-[40px] font-extrabold text-white mb-5 leading-[1.1] tracking-tight">
                    Mulai Wujudkan Sirkularitas di Fasilitas Anda Hari Ini
                </h3>

                <p class="text-sm md:text-base text-[#C6ECCD] leading-relaxed max-w-lg mb-10">
                    Tinggalkan kebiasaan membuang material bernilai ke pembuangan akhir. Hubungkan tim operasional Anda
                    dengan pengolah terpercaya di sekitar Anda sekarang juga.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    <a href="{{ route('pasar.index') }}"
                        class="inline-flex items-center justify-center gap-2 bg-white text-[#093A25] text-sm font-bold rounded-xl px-7 py-3.5 hover:bg-gray-50 transition-colors shadow-sm tracking-tight">
                        Jelajahi Katalog Limbah
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center justify-center gap-2 border border-white/30 text-white text-sm font-bold rounded-xl px-7 py-3.5 hover:bg-white/10 transition-colors tracking-tight">
                        Lihat Dashboard
                    </a>
                </div>

                <p class="text-[10px] text-white/50 italic tracking-wide sirkula-mono">
                    "Transaksi dan pengambilan material selalu dilakukan langsung antara mitra dan pengolah tanpa
                    perantara."
                </p>
            </div>
        </div>
    </div>
</section>
