@extends('layouts.app')

@section('content')

    @php
        // ---------- DATA STATIS & DINAMIS ----------
        $totalListing = isset($limbahMap) ? count($limbahMap) : 128;
        $katalogLimbah = isset($limbahMap) ? collect($limbahMap)->take(6) : collect();

        $kategori = [
            [
                'slug' => 'organik',
                'nama' => 'Limbah Organik',
                'badge' => 'Biodegradable',
                'badgeClass' => 'bg-[#C6ECCD] text-[#093A25]',
                'badgeIcon' => 'M5 19c8 0 14-6 14-14-8 0-14 6-14 14zm0 0c2-4 5-7 9-9',
                'glow' => 'bg-green-200/40',
                'listing' => 42,
                'deskripsi' => 'Fraksi bahan terurai hayati dari operasional dapur hotel, restoran, agroindustri, dan pasar yang bernilai tinggi untuk pakan maggot BSF, komposting, serta biodiesel.',
                'tags' => ['Sisa Makanan Hotel', 'Minyak Jelantah (UCO)', 'Ampas Kopi Roastery', 'Limbah Kulit Buah'],
                'link' => 'Lihat Direktori Organik',
                'estimasi' => '18.4',
            ],
            [
                'slug' => 'anorganik',
                'nama' => 'Limbah Anorganik',
                'badge' => 'Recyclable Dry',
                'badgeClass' => 'bg-[#EBEFEA] text-[#414943]',
                'badgeIcon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
                'glow' => 'bg-gray-200/40',
                'listing' => 86,
                'deskripsi' => 'Material kering terpilah langsung dari sumber komersial. Siap diolah oleh pabrik peleburan, pengolah plastik sirkular, dan fasilitas daur ulang kertas karton.',
                'tags' => ['Kardus Corrugated (OCC)', 'Botol PET Bening Pres', 'Pecahan Botol Kaca', 'Palet Kayu Bekas'],
                'link' => 'Lihat Direktori Anorganik',
                'estimasi' => '109.8',
            ],
        ];

        $statusConf = [
            'tersedia' => ['label' => 'Tersedia', 'wrap' => 'bg-[#C6ECCD] text-[#093A25]', 'dot' => 'bg-[#093A25]'],
            'diproses' => ['label' => 'Sedang Diproses', 'wrap' => 'bg-[#FDECE8] text-[#C4411E]', 'dot' => 'bg-[#C4411E]'],
            'diambil' => ['label' => 'Sudah Diambil', 'wrap' => 'bg-[#EBEFEA] text-gray-500', 'dot' => 'bg-gray-400'],
        ];
    @endphp

    {{-- ========================================== --}}
    {{-- STYLE TAMBAHAN KHUSUS PETA --}}
    {{-- ========================================== --}}
    @verbatim
        <style>
            .sirkula-scroll {
                scrollbar-width: thin;
                scrollbar-color: #C6ECCD transparent;
            }
            .sirkula-scroll::-webkit-scrollbar { width: 6px; }
            .sirkula-scroll::-webkit-scrollbar-thumb { background: #C6ECCD; border-radius: 999px; }

            .leaflet-container { font-family: inherit; background: #e5ebe5; z-index: 10;}
            .leaflet-popup-content-wrapper { border-radius: 16px; box-shadow: 0 12px 32px rgba(9, 58, 37, .18); padding: 0; }
            .leaflet-popup-content { margin: 14px 16px; }
            .leaflet-bar { border: none !important; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 14px rgba(9, 58, 37, .15) !important; }
            .leaflet-bar a { color: #093A25; border-bottom-color: #e5ebe5; }
            .leaflet-tooltip { border: none; border-radius: 8px; box-shadow: 0 4px 14px rgba(9, 58, 37, .18); font-size: 11px; font-weight: 600; color: #093A25; }
        </style>
    @endverbatim

    <div class="bg-gradient-to-b from-[#F0F5F0] via-[#F6FBF5] to-[#F6FBF5]">

        {{-- ========================================== --}}
        {{-- 1. HERO SECTION --}}
        {{-- ========================================== --}}
        <section class="relative overflow-hidden pb-20 pt-24">
            <!-- Dekorasi Latar -->
            <div class="pointer-events-none absolute -right-20 top-20 h-[30rem] w-[30rem] rounded-full bg-orange-50/40 blur-3xl"></div>
            <div class="pointer-events-none absolute -left-20 top-0 h-[30rem] w-[30rem] rounded-full bg-green-50/50 blur-3xl"></div>

            <div class="relative z-10 mx-auto max-w-5xl px-4 text-center sm:px-6 lg:px-8">
                <h1 class="mb-6 text-4xl font-extrabold leading-[1.1] tracking-tight text-[#093A25] md:text-[54px]">
                    Temukan Limbah yang Masih Bernilai.
                </h1>
                <p class="mx-auto mb-12 max-w-2xl text-base leading-relaxed text-[#414943] md:text-lg">
                    Jelajahi informasi limbah organik dan anorganik dari berbagai mitra bisnis dan perhotelan, lalu hubungi mereka secara langsung via WhatsApp.
                </p>

                <!-- Search Bar Interaktif -->
                <form action="{{ route('pasar.index') }}" method="GET" class="mx-auto mb-6 flex max-w-4xl flex-col items-stretch gap-2 rounded-2xl bg-white p-2.5 shadow-sm border border-[#EBEFEA] md:flex-row">
                    
                    <div class="flex flex-1 items-center gap-3 rounded-xl bg-[#F6FBF5] px-4 py-3.5 transition-colors focus-within:bg-white focus-within:ring-1 focus-within:ring-[#093A25]">
                        <svg class="h-5 w-5 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari limbah: kardus, ampas..." class="w-full border-none bg-transparent p-0 text-sm text-[#414943] outline-none placeholder:text-gray-400 focus:ring-0">
                    </div>

                    <div class="flex flex-1 items-center gap-3 rounded-xl bg-[#F6FBF5] px-4 py-3.5 md:border-l md:border-white transition-colors focus-within:bg-white focus-within:ring-1 focus-within:ring-[#093A25]">
                        <svg class="h-5 w-5 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z" /></svg>
                        <select name="kategori" class="w-full cursor-pointer appearance-none border-none bg-transparent p-0 text-sm text-[#414943] outline-none focus:ring-0">
                            <option value="">Semua Kategori</option>
                            <option value="organik" @selected(request('kategori') === 'organik')>Organik</option>
                            <option value="anorganik" @selected(request('kategori') === 'anorganik')>Anorganik</option>
                        </select>
                    </div>

                    <div class="flex flex-1 items-center gap-3 rounded-xl bg-[#F6FBF5] px-4 py-3.5 md:border-l md:border-white transition-colors focus-within:bg-white focus-within:ring-1 focus-within:ring-[#093A25]">
                        <svg class="h-5 w-5 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <select name="wilayah" class="w-full cursor-pointer appearance-none border-none bg-transparent p-0 text-sm text-[#414943] outline-none focus:ring-0">
                            <option value="">Semua Wilayah</option>
                        </select>
                    </div>

                    <button type="submit" class="flex items-center justify-center gap-2 whitespace-nowrap rounded-xl bg-[#1A4B36] px-8 py-3.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-[#093A25]">
                        Jelajahi Limbah <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </form>

                <div class="mb-14">
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#414943] transition-colors hover:text-[#093A25] hover:underline">
                        Saya pengelola usaha & ingin menyediakan informasi limbah <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </a>
                </div>

                <!-- Stats Pills -->
                <div class="flex flex-wrap justify-center gap-3">
                    <div class="bg-[#F0F5F0] px-5 py-2.5 rounded-xl border border-[#EBEFEA] text-[11px] font-bold text-[#093A25] flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#093A25]"></span> 128+ Ton <span class="font-medium text-gray-500">Material Tersirkulasi</span>
                    </div>
                    <div class="bg-[#F0F5F0] px-5 py-2.5 rounded-xl border border-[#EBEFEA] text-[11px] font-bold text-[#093A25] flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#093A25]"></span> 94 Mitra <span class="font-medium text-gray-500">Terverifikasi Aktif</span>
                    </div>
                    <div class="bg-[#F0F5F0] px-5 py-2.5 rounded-xl border border-[#EBEFEA] text-[11px] font-bold text-[#C4411E] flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#C4411E]"></span> 100% Langsung <span class="font-medium text-gray-500">Via WhatsApp Mitra</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ========================================== --}}
        {{-- 2. KLASIFIKASI KATEGORI --}}
        {{-- ========================================== --}}
        <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="mb-10 flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <div>
                    <p class="mb-2 font-mono text-[10px] font-bold uppercase tracking-widest text-gray-400">Klasifikasi Aliran Material</p>
                    <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-[#093A25]">Jelajahi Berdasarkan Jenis</h2>
                </div>
                <p class="text-sm text-[#414943] max-w-sm md:text-right">
                    Pilih jenis material untuk menelusuri ketersediaan terkini, frekuensi volume timbulan, dan spesifikasi penanganan.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                @foreach ($kategori as $item)
                    <div class="relative flex flex-col overflow-hidden rounded-[2rem] bg-gradient-to-br from-white to-[#F0F5F0] p-8 md:p-10 shadow-sm border border-[#EBEFEA] transition-transform hover:-translate-y-1">
                        <div class="pointer-events-none absolute -right-10 -top-10 h-44 w-44 rounded-full {{ $item['glow'] }} blur-3xl"></div>
                        
                        <div class="relative mb-8 flex items-start justify-between gap-3">
                            <span class="{{ $item['badgeClass'] }} inline-flex items-center gap-1.5 rounded-full px-3.5 py-1.5 font-mono text-[10px] font-bold uppercase tracking-wider">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['badgeIcon'] }}" /></svg>
                                {{ $item['badge'] }}
                            </span>
                            <span class="bg-[#EBEFEA] text-[#414943] inline-flex items-center rounded-lg px-3 py-1.5 font-mono text-[10px] font-bold tracking-wider">
                                {{ $item['listing'] }} Listing Aktif
                            </span>
                        </div>

                        <h3 class="relative mb-3 text-2xl font-extrabold tracking-tight text-[#093A25]">{{ $item['nama'] }}</h3>
                        <p class="relative mb-8 text-sm leading-relaxed text-gray-600">{{ $item['deskripsi'] }}</p>
                        
                        <!-- Tags -->
                        <div class="relative mb-12 flex flex-wrap gap-2">
                            @foreach ($item['tags'] as $tag)
                                <span class="rounded-lg border border-[#EBEFEA] bg-white px-3 py-1.5 text-[11px] font-medium text-gray-600">{{ $tag }}</span>
                            @endforeach
                        </div>

                        <div class="relative mt-auto flex items-center justify-between border-t border-[#EBEFEA] pt-6">
                            <a href="{{ route('pasar.index', ['kategori' => $item['slug']]) }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#093A25] transition-colors hover:text-[#1A4B36] hover:underline">
                                {{ $item['link'] }} <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </a>
                            <span class="font-mono text-[10px] text-gray-400 font-medium tracking-wider">Est. {{ $item['estimasi'] }} Ton/Bulan</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ========================================== --}}
        {{-- 3. CARA KERJA SIRKULA --}}
        {{-- ========================================== --}}
        <section class="mx-auto max-w-5xl px-4 py-16 sm:px-6 lg:px-8 text-center">
            <p class="mb-2 font-mono text-[10px] font-bold uppercase tracking-widest text-gray-400">Mekanisme Tanpa Perantara</p>
            <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-[#093A25] mb-4">Cara Kerja SIRKULA</h2>
            <p class="text-sm text-[#414943] max-w-xl mx-auto mb-12">Tiga langkah sederhana untuk menghubungkan penghasil limbah dengan pelaku daur ulang secara mandiri, transparan, dan terukur.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 text-left">
                <!-- Step 1 -->
                <div class="bg-white p-8 rounded-3xl border border-[#EBEFEA] shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-xl font-extrabold text-[#093A25] font-mono">01</span>
                        <div class="bg-[#F0F5F0] p-2.5 rounded-full text-[#093A25]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                        </div>
                    </div>
                    <h4 class="font-bold text-[#093A25] text-lg mb-2">Temukan Material</h4>
                    <p class="text-xs text-gray-600 leading-relaxed">Recycler, peternak, dan pengempul mencari material yang dibutuhkan berdasarkan klasifikasi jenis, estimasi tonase, dan lokasi operasional terdekat.</p>
                </div>
                <!-- Step 2 -->
                <div class="bg-white p-8 rounded-3xl border border-[#EBEFEA] shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-xl font-extrabold text-[#C4411E] font-mono">02</span>
                        <div class="bg-[#FDECE8] p-2.5 rounded-full text-[#C4411E]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                        </div>
                    </div>
                    <h4 class="font-bold text-[#093A25] text-lg mb-2">Hubungi Mitra</h4>
                    <p class="text-xs text-gray-600 leading-relaxed">Buka halaman detail informasi limbah dan hubungi penanggung jawab operasional mitra penyedia secara langsung lewat sambungan WhatsApp resmi.</p>
                </div>
                <!-- Step 3 -->
                <div class="bg-white p-8 rounded-3xl border border-[#EBEFEA] shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-xl font-extrabold text-gray-500 font-mono">03</span>
                        <div class="bg-[#EBEFEA] p-2.5 rounded-full text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                        </div>
                    </div>
                    <h4 class="font-bold text-[#093A25] text-lg mb-2">Sirkulasikan</h4>
                    <p class="text-xs text-gray-600 leading-relaxed">Sepakati jadwal armada angkut atau nilai tebus secara langsung tanpa komisi perantara. Status ketersediaan diperbarui setelah material terangkut.</p>
                </div>
            </div>

            <!-- Box Protokol Independensi -->
            <div class="bg-[#F0F5F0] p-6 rounded-2xl flex flex-col sm:flex-row sm:items-center gap-5 text-left border border-[#EBEFEA]">
                <div class="bg-white p-2.5 rounded-xl text-[#093A25] shadow-sm shrink-0 w-max">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                </div>
                <div>
                    <h5 class="font-bold text-[#093A25] text-sm">Protokol Independensi Platform</h5>
                    <p class="text-xs text-[#414943] mt-1.5 leading-relaxed">SIRKULA bertindak murni sebagai infrastruktur penyedia informasi dan fasilitator koneksi sirkular. Transaksi finansial, penimbangan akhir, izin angkut, dan kesepakatan logistik disepakati secara langsung antara mitra penyedia dan pengolah limbah.</p>
                </div>
            </div>
        </section>

        {{-- ========================================== --}}
        {{-- 4. KETERSEDIAAN LIMBAH TERBARU --}}
        {{-- ========================================== --}}
        <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="mb-10 flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <div>
                    <p class="mb-2 font-mono text-[10px] font-bold uppercase tracking-widest text-gray-400">&bull; Katalog Pembaruan Harian</p>
                    <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-[#093A25]">Ketersediaan Limbah Terbaru</h2>
                    <p class="text-sm text-gray-600 mt-2">Temukan material terpilah yang siap diambil langsung dari mitra SIRKULA.</p>
                </div>
                <a href="{{ route('pasar.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#F0F5F0] border border-[#EBEFEA] px-5 py-3 text-sm font-bold text-[#414943] transition hover:bg-[#EBEFEA]">
                    Lihat Semua Limbah ({{ $totalListing }}) &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @if (isset($limbahMap) && count($limbahMap) > 0)
                    @foreach ($katalogLimbah as $row)
                        @php
                            $statusLower = strtolower($row->status ?? '');
                            $statusKey = $statusLower === 'sedang dijemput' ? 'diproses' : ($statusLower === 'selesai' ? 'diambil' : 'tersedia');
                            $st = $statusConf[$statusKey] ?? $statusConf['tersedia'];
                            $selesai = $statusKey === 'diambil';
                            $katName = $row->kategori->nama_kategori ?? 'UMUM';
                        @endphp
                        <article class="flex flex-col overflow-hidden rounded-3xl bg-white shadow-sm border border-[#EBEFEA] transition hover:-translate-y-1 hover:shadow-md">
                            <div class="relative h-48 bg-gray-200 p-3">
                                @if ($row->foto_limbah)
                                    <img src="{{ asset('storage/' . $row->foto_limbah) }}" alt="{{ $row->judul }}" class="absolute inset-0 h-full w-full object-cover {{ $selesai ? 'grayscale-[60%] opacity-80' : '' }}" onerror="this.style.display='none'">
                                @else
                                    <div class="absolute inset-0 flex h-full w-full items-center justify-center bg-gray-100 text-sm font-medium text-gray-400">Tanpa Foto</div>
                                @endif
                                
                                <span class="relative z-10 inline-block rounded border border-white/20 bg-white/90 px-2 py-1 font-mono text-[9px] font-bold uppercase tracking-widest text-[#414943] backdrop-blur-sm shadow-sm">
                                    {{ $katName }}
                                </span>
                                
                                <span class="absolute right-3 top-3 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 font-mono text-[10px] font-bold shadow-sm {{ $st['wrap'] }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $st['dot'] }}"></span> {{ $st['label'] }}
                                </span>
                            </div>
                            
                            <div class="flex flex-1 flex-col p-6">
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <p class="font-mono text-[10px] text-gray-500 font-medium flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg> {{ $row->mitra->name ?? 'Mitra Sirkula' }}</p>
                                    <span class="bg-[#F0F5F0] text-[#093A25] font-mono font-bold text-[11px] px-2 py-1 rounded tracking-wide">{{ $row->estimasi_volume }} {{ strtoupper($row->satuan) }}</span>
                                </div>
                                <h3 class="mb-2 text-lg font-bold text-[#093A25] leading-tight">{{ $row->judul }}</h3>
                                <p class="mb-5 line-clamp-2 text-xs text-[#414943] leading-relaxed">{{ $row->deskripsi }}</p>
                                
                                <div class="space-y-1.5 mb-6 mt-auto">
                                    <div class="flex items-center gap-2 text-[11px] text-gray-500">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg> 
                                        <span class="truncate">Denpasar Selatan, Bali</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-[11px] text-gray-500">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> 
                                        <span class="truncate">Timbulan: Mingguan</span>
                                    </div>
                                </div>

                                <div>
                                    @if($selesai)
                                        <button disabled class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#EBEFEA] py-3 text-sm font-bold text-gray-400 cursor-not-allowed">
                                            Batch Selesai <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        </button>
                                        <p class="text-[9px] text-center text-gray-400 mt-2">Batch baru dijadwalkan masuk bulan depan</p>
                                    @else
                                        <a href="{{ route('pasar.show', $row->id) }}" class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#F0F5F0] py-3 text-sm font-bold text-[#414943] transition hover:bg-[#EBEFEA]">
                                            Hubungi Mitra &nearr;
                                        </a>
                                        <p class="text-[9px] text-center text-gray-400 mt-2">Transaksi dan pengambilan dilakukan langsung dengan mitra</p>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                @else
                    <div class="col-span-full rounded-3xl border border-dashed border-[#EBEFEA] bg-white py-16 text-center text-sm font-medium text-gray-500">Belum ada data limbah tersedia saat ini.</div>
                @endif
            </div>
        </section>

        {{-- ========================================== --}}
        {{-- 5. PETA INTERAKTIF REAL-TIME (DIPERTAHANKAN) --}}
        {{-- ========================================== --}}
        <section class="mx-auto max-w-7xl px-4 pb-24 pt-8 sm:px-6 lg:px-8">
            <div class="rounded-3xl bg-[#F0F5F0] p-6 shadow-sm border border-[#EBEFEA] md:p-8">
                <div class="mb-6 flex flex-col justify-between gap-2 md:flex-row md:items-end">
                    <div>
                        <p class="mb-2 font-mono text-[10px] font-bold uppercase tracking-widest text-gray-400">Zona Agregasi Wilayah</p>
                        <h2 class="text-3xl font-extrabold tracking-tight text-[#093A25]">Temukan Limbah di Sekitar Anda</h2>
                        <p class="text-sm text-[#414943] mt-2 max-w-lg">Efisiensikan rute logistik pengangkutan dengan menelusuri sumber limbah berdasarkan klaster kota dan simpul pengumpulan terdekat.</p>
                    </div>
                    <div class="flex items-center gap-2 text-sm font-bold text-[#093A25] font-mono">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                        8 Hub Aktif Terpetakan
                    </div>
                </div>

                <!-- Tag Wilayah Peta -->
                <div class="flex flex-wrap gap-2.5 mb-6">
                    <span class="bg-white border border-[#EBEFEA] px-3.5 py-1.5 rounded-full text-sm font-bold text-[#093A25] shadow-sm flex items-center gap-2">Denpasar <span class="bg-[#F0F5F0] text-gray-500 text-[10px] font-mono px-1.5 py-0.5 rounded">38 listing</span></span>
                    <span class="bg-white border border-[#EBEFEA] px-3.5 py-1.5 rounded-full text-sm font-bold text-[#093A25] shadow-sm flex items-center gap-2">Badung & Kuta <span class="bg-[#F0F5F0] text-gray-500 text-[10px] font-mono px-1.5 py-0.5 rounded">44 listing</span></span>
                    <span class="bg-white border border-[#EBEFEA] px-3.5 py-1.5 rounded-full text-sm font-bold text-[#093A25] shadow-sm flex items-center gap-2">Gianyar & Ubud <span class="bg-[#F0F5F0] text-gray-500 text-[10px] font-mono px-1.5 py-0.5 rounded">19 listing</span></span>
                    <span class="bg-white border border-[#EBEFEA] px-3.5 py-1.5 rounded-full text-sm font-bold text-[#093A25] shadow-sm flex items-center gap-2">Surabaya <span class="bg-[#F0F5F0] text-gray-500 text-[10px] font-mono px-1.5 py-0.5 rounded">12 listing</span></span>
                    <span class="bg-white border border-[#EBEFEA] px-3.5 py-1.5 rounded-full text-sm font-bold text-[#093A25] shadow-sm flex items-center gap-2">Jakarta Timur <span class="bg-[#F0F5F0] text-gray-500 text-[10px] font-mono px-1.5 py-0.5 rounded">8 listing</span></span>
                    <span class="bg-white border border-[#EBEFEA] px-3.5 py-1.5 rounded-full text-sm font-bold text-[#093A25] shadow-sm flex items-center gap-2">Bandung <span class="bg-[#F0F5F0] text-gray-500 text-[10px] font-mono px-1.5 py-0.5 rounded">7 listing</span></span>
                </div>

                <div class="flex flex-col gap-6 lg:flex-row">
                    <!-- Kolom Kiri: Pencarian & Daftar Lokasi (Tersembunyi via CSS agar mirip desain Figma yang memanjangkan peta, tapi tetap fungsional) -->
                    <div class="hidden lg:flex w-full flex-col gap-4 lg:w-1/3">
                        <div class="relative">
                            <label for="mapSearch" class="sr-only">Cari mitra atau jenis limbah</label>
                            <input type="text" id="mapSearch" placeholder="Cari mitra atau jenis limbah..." autocomplete="off"
                                class="w-full rounded-xl border border-[#EBEFEA] bg-white py-3.5 pl-11 pr-12 text-sm text-[#414943] shadow-sm outline-none transition placeholder:text-gray-400 focus:border-[#093A25] focus:ring-1 focus:ring-[#093A25]">
                            <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <span id="mapCount" class="pointer-events-none absolute right-4 top-[15px] font-mono text-[11px] font-bold text-gray-400"></span>
                        </div>
                        <div id="placesList" class="sirkula-scroll flex max-h-[350px] flex-col gap-3 overflow-y-auto pr-2"></div>
                    </div>

                    <!-- Kolom Kanan: Peta Leaflet -->
                    <div class="w-full lg:w-2/3">
                        <div class="relative isolate h-[350px] w-full overflow-hidden rounded-2xl bg-gray-200 shadow-sm ring-1 ring-[#EBEFEA] lg:h-[415px]">
                            <div id="peta-sirkula" class="absolute inset-0 z-0"></div>
                            
                            <!-- Overlay Bawah Peta -->
                            <div class="pointer-events-none absolute bottom-0 left-0 w-full bg-gradient-to-t from-[#093A25]/90 to-transparent pt-16 pb-4 px-6 z-[600] flex justify-between items-end">
                                <div>
                                    <p class="text-[10px] font-mono font-bold uppercase tracking-widest text-green-300">Simpul Sirkulasi Aktif</p>
                                    <p class="text-white font-bold text-sm mt-0.5">Koridor Bali Selatan (Denpasar – Kuta – Nusa Dua)</p>
                                </div>
                                <div class="bg-white/20 backdrop-blur border border-white/20 text-white text-[10px] px-3 py-1.5 rounded-lg font-medium flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg> Radius logistik rerata 14.2 km
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ========================================== --}}
        {{-- 6. CTA BOTTOM (KEMITRAAN FASILITAS) --}}
        {{-- ========================================== --}}
        <section class="max-w-7xl mx-auto px-4 pb-20 sm:px-6 lg:px-8">
            <div class="bg-[#1A4B36] rounded-[2.5rem] p-10 md:p-16 relative overflow-hidden shadow-sm">
                <!-- Dekorasi -->
                <div class="absolute -right-20 -bottom-20 w-[400px] h-[400px] border-[50px] border-[#093A25]/40 rounded-full"></div>
                <div class="absolute right-40 top-10 w-[100px] h-[100px] bg-white/5 rounded-full blur-2xl"></div>

                <div class="relative z-10 max-w-2xl">
                    <span class="bg-[#093A25] text-white/90 text-[10px] font-mono font-bold px-3.5 py-1.5 rounded-full uppercase tracking-wider mb-6 inline-flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-white"></span> Kemitraan Fasilitas & Usaha
                    </span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4 leading-tight">Punya limbah yang masih bisa dimanfaatkan?</h2>
                    <p class="text-[#C6ECCD] text-sm md:text-base leading-relaxed mb-8">
                        Bagikan informasi limbah terpilah bisnis Anda di SIRKULA agar lebih cepat terserap oleh pengolah kompos, peternak maggot, dan industri manufaktur sirkular lokal. Kurangi biaya TPA, ciptakan dampak nyata.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('laporan.create') }}" class="bg-white text-[#093A25] px-6 py-3.5 rounded-xl font-bold text-sm text-center shadow-sm hover:bg-gray-50 transition-colors inline-flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> Tambah Informasi Limbah
                        </a>
                        <a href="{{ route('about') ?? '#' }}" class="bg-[#093A25] text-white px-6 py-3.5 rounded-xl font-bold text-sm text-center hover:bg-[#072c1c] transition-colors inline-flex items-center justify-center gap-2">
                            Pelajari Lebih Lanjut <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- ========================================== --}}
        {{-- 7. FOOTER --}}
        {{-- ========================================== --}}
        <footer class="bg-[#F6FBF5] pt-8 border-t border-[#EBEFEA] pb-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="mb-4">
                        <img src="{{ asset('storage/images/logo-sirkula.png') }}" alt="Logo Sirkula" class="h-8 w-auto">
                    </div>
                    <p class="text-sm font-bold text-[#093A25] mt-1">Temukan. Hubungkan. Sirkulasikan.</p>
                    <p class="text-xs text-[#414943] mt-3 leading-relaxed">
                        Infrastruktur pertukaran dan koneksi informasi limbah sirkular untuk mitra industri, perhotelan, komersial, dan pendaur ulang.
                    </p>
                    <p class="text-xs text-gray-400 mt-6">&copy; {{ date('Y') }} SIRKULA. Hak cipta dilindungi undang-undang.</p>
                </div>
                <div>
                    <h4 class="font-bold text-[#093A25] text-sm mb-4">Eksplorasi</h4>
                    <ul class="space-y-2 text-xs text-[#414943]">
                        <li><a href="{{ route('pasar.index') }}" class="hover:text-[#093A25] hover:underline">Jelajahi Limbah</a></li>
                        <li><a href="{{ route('about') ?? '#' }}" class="hover:text-[#093A25] hover:underline">Tentang SIRKULA</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-[#093A25] hover:underline">Untuk Mitra</a></li>
                        <li><a href="#" class="hover:text-[#093A25] hover:underline">Bantuan &amp; Panduan</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-[#093A25] text-sm mb-4">Pemberitahuan Sistem</h4>
                    <div class="bg-[#F0F5F0] p-4 rounded-xl border border-[#EBEFEA]">
                        <p class="text-[10px] font-mono font-bold text-gray-500 uppercase tracking-wider mb-1">Protokol Transaksi</p>
                        <p class="text-xs text-[#414943] italic leading-relaxed">
                            "Transaksi dan kesepakatan pengambilan dilakukan langsung antara mitra dan recycler."
                        </p>
                    </div>
                </div>
            </div>
        </footer>

    </div>

    {{-- ========================================== --}}
    {{-- SCRIPT PETA TERHUBUNG KE DATABASE (DIPERTAHANKAN) --}}
    {{-- ========================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // TERIMA DATA ASLI DARI DATABASE LARAVEL
            const rawLimbah = @json($limbahMap ?? []);

            const realPlaces = rawLimbah
                .filter(function(item) {
                    return item.latitude !== null && item.longitude !== null && item.latitude !== '' && item.longitude !== '';
                })
                .map(function(item) {
                    return {
                        id: item.id,
                        nama: item.mitra ? item.mitra.name : 'Mitra Sirkula',
                        kategori: item.kategori ? item.kategori.nama_kategori : 'Umum',
                        judul: item.judul,
                        volume: item.estimasi_volume + ' ' + item.satuan,
                        lokasi: item.deskripsi ? item.deskripsi.substring(0, 35) + '...' : 'Lokasi Mitra',
                        lat: parseFloat(item.latitude),
                        lng: parseFloat(item.longitude)
                    };
                });

            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const esc = (s) => String(s).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

            const peta = L.map('peta-sirkula', {
                center: [-8.6705, 115.2126],
                zoom: 11,
                scrollWheelZoom: false,
                zoomControl: false,
            });
            L.control.zoom({ position: 'bottomright' }).addTo(peta);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(peta);

            peta.on('click', () => peta.scrollWheelZoom.enable());
            peta.getContainer().addEventListener('mouseleave', () => peta.scrollWheelZoom.disable());

            let markersMap = {};
            let activeId = null;
            const listContainer = document.getElementById('placesList');
            const searchInput = document.getElementById('mapSearch');
            const countEl = document.getElementById('mapCount');

            function setActive(id) {
                activeId = id;
                Object.entries(markersMap).forEach(([key, marker]) => {
                    const isActive = String(key) === String(id);
                    marker.setStyle({ radius: isActive ? 11 : 7, weight: isActive ? 3 : 2 });
                    if (isActive) marker.bringToFront();
                });

                listContainer.querySelectorAll('[data-id]').forEach((card) => {
                    const isActive = String(card.dataset.id) === String(id);
                    card.classList.toggle('ring-1', isActive);
                    card.classList.toggle('ring-[#093A25]', isActive);
                    card.classList.toggle('bg-[#F0F5F0]', isActive);
                });
            }

            function renderList(places) {
                listContainer.innerHTML = '';
                if (countEl) countEl.textContent = places.length + '/' + realPlaces.length;

                if (places.length === 0) {
                    listContainer.innerHTML = `
                        <div class="flex flex-col items-center gap-2 rounded-2xl bg-white px-4 py-8 text-center border border-[#EBEFEA]">
                            <p class="text-xs font-medium text-gray-500">Belum ada data limbah berkoordinat valid dari database.</p>
                        </div>`;
                    return;
                }

                places.forEach(function(place) {
                    const card = document.createElement('button');
                    card.type = 'button';
                    card.dataset.id = place.id;
                    card.className = "group flex w-full cursor-pointer flex-col gap-1.5 rounded-2xl border border-[#EBEFEA] bg-white p-4 text-left transition hover:-translate-y-0.5 hover:shadow-sm";
                    card.innerHTML = `
                        <div class="flex items-center justify-between gap-2 mb-1">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#EBEFEA] px-2.5 py-1 font-mono text-[9px] font-bold uppercase tracking-wider text-[#414943]">${esc(place.kategori)}</span>
                            <span class="font-mono text-[11px] font-bold text-[#093A25] bg-[#C6ECCD] px-2 py-0.5 rounded">${esc(place.volume)}</span>
                        </div>
                        <h4 class="text-sm font-bold leading-snug text-[#093A25] transition group-hover:text-[#1A4B36]">${esc(place.judul)}</h4>
                        <p class="text-[11px] font-mono text-gray-500 mt-1">🏭 ${esc(place.nama)}</p>
                    `;

                    card.addEventListener('click', function() {
                        peta.flyTo([place.lat, place.lng], 14, { duration: reduceMotion ? 0 : 1.2 });
                        if (markersMap[place.id]) markersMap[place.id].openPopup();
                        setActive(place.id);
                    });

                    listContainer.appendChild(card);
                });
            }

            function renderMarkers(places) {
                Object.values(markersMap).forEach(marker => peta.removeLayer(marker));
                markersMap = {};

                places.forEach(function(place) {
                    const marker = L.circleMarker([place.lat, place.lng], {
                        radius: 7, color: '#ffffff', weight: 2, fillColor: '#093A25', fillOpacity: 1
                    }).addTo(peta);

                    marker.bindPopup(`
                        <div class="min-w-[200px] text-left">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#EBEFEA] px-2 py-1 font-mono text-[9px] font-bold uppercase text-[#414943] tracking-wider mb-2">${esc(place.kategori)}</span>
                            <h4 class="text-sm font-bold leading-tight text-[#093A25]">${esc(place.judul)}</h4>
                            <p class="mt-1 font-mono text-[10px] text-gray-500">🏭 ${esc(place.nama)}</p>
                            <div class="mt-3 flex items-center justify-between gap-3 border-t border-[#EBEFEA] pt-3">
                                <span class="font-mono text-xs font-bold text-[#093A25] bg-[#C6ECCD] px-2 py-1 rounded">${esc(place.volume)}</span>
                                <a href="/pasar/${place.id}" class="rounded-lg bg-[#093A25] px-3 py-1.5 font-bold text-[10px] text-white hover:bg-[#1A4B36] transition-colors">Detail &rarr;</a>
                            </div>
                        </div>
                    `);

                    marker.on('click', () => {
                        setActive(place.id);
                        const card = listContainer.querySelector(`[data-id="${place.id}"]`);
                        if (card) card.scrollIntoView({ block: 'nearest', behavior: reduceMotion ? 'auto' : 'smooth' });
                    });

                    markersMap[place.id] = marker;
                });
            }

            function fitToPlaces(places) {
                if (!places.length) return;
                const bounds = L.latLngBounds(places.map(p => [p.lat, p.lng]));
                peta.fitBounds(bounds, { padding: [48, 48], maxZoom: 13, animate: !reduceMotion });
            }

            function applyFilter(raw) {
                const keyword = raw.trim().toLowerCase();
                const filtered = realPlaces.filter(p => 
                    p.nama.toLowerCase().includes(keyword) || 
                    p.judul.toLowerCase().includes(keyword) || 
                    p.kategori.toLowerCase().includes(keyword)
                );
                activeId = null;
                renderList(filtered);
                renderMarkers(filtered);
                if (keyword) fitToPlaces(filtered);
            }

            renderList(realPlaces);
            renderMarkers(realPlaces);
            if (realPlaces.length > 0) fitToPlaces(realPlaces);

            if (searchInput) {
                searchInput.addEventListener('input', (e) => applyFilter(e.target.value));
            }

            setTimeout(() => peta.invalidateSize(), 250);
        });
    </script>
@endsection