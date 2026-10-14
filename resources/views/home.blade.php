@extends('layouts.app')

@section('content')

    @php
        // ---------- DATA REAL DARI DATABASE ----------
        // Status yang tampil publik sama dengan halaman Jelajahi Limbah (PasarController).
        $statusPublik = ['tersedia', 'sedang diproses', 'sudah diambil'];
        $statusAktif = ['tersedia', 'sedang diproses'];

        $keKg = function ($volume, $satuan) {
            return match (strtolower((string) $satuan)) {
                'ton' => (float) $volume * 1000,
                'kg' => (float) $volume,
                default => 0, // liter & pcs tidak bisa dikonversi ke massa
            };
        };

        $statusDari = fn($row) => strtolower((string) $row->status);

        $publik = \App\Models\LaporanLimbah::with(['mitra', 'kategori'])
            ->whereRaw('LOWER(status) IN (?, ?, ?)', $statusPublik)
            ->latest()
            ->get();

        $aktif = $publik->filter(fn($r) => in_array($statusDari($r), $statusAktif, true));
        $diambil = $publik->filter(fn($r) => $statusDari($r) === 'sudah diambil');

        $totalListing = $publik->count();
        $katalogLimbah = $publik->take(6);

        // Statistik hero
        $totalTon = $diambil->sum(fn($r) => $keKg($r->estimasi_volume, $r->satuan)) / 1000;
        $totalTonText = $totalTon >= 100
            ? number_format($totalTon, 0, ',', '.')
            : rtrim(rtrim(number_format($totalTon, 1, ',', '.'), '0'), ',');
        $jumlahMitra = $publik->pluck('mitra_id')->unique()->count();

        // Data peta: hanya kolom yang dibutuhkan (tanpa email/WhatsApp mitra) dan hanya yang berkoordinat
        $petaData = $aktif
            ->filter(fn($r) => $r->latitude !== null && $r->longitude !== null && $r->latitude !== '' && $r->longitude !== '')
            ->map(fn($r) => [
                'id' => $r->id,
                'judul' => $r->judul,
                'estimasi_volume' => $r->estimasi_volume,
                'satuan' => $r->satuan,
                'foto_limbah' => $r->foto_limbah,
                'latitude' => $r->latitude,
                'longitude' => $r->longitude,
                'kategori' => ['nama_kategori' => $r->kategori->nama_kategori ?? 'Umum'],
                'mitra' => [
                    'name' => $r->mitra->name ?? 'Mitra Sirkula',
                    'nama_instansi' => $r->mitra->nama_instansi ?? null,
                ],
            ])
            ->values();

        $jumlahTitik = $petaData->count();
        $hubTerpetakan = $petaData->pluck('mitra.name')->unique()->count();

        // Wilayah (kelurahan mitra) dengan listing aktif terbanyak
        $perWilayah = $aktif
            ->groupBy(fn($r) => trim((string) ($r->mitra->kelurahan ?? '')))
            ->forget('')
            ->map(fn($g) => $g->count())
            ->sortDesc()
            ->take(8);

        $wilayahPeta = $perWilayah->map(fn($jml, $nama) => [$nama, $jml])->values()->all();
        $captionWilayah = $perWilayah->keys()->take(3)->implode(' – ');

        // Pilihan wilayah pada form pencarian (sama dengan filter di PasarController)
        $wilayahOpsi = \App\Models\User::where('role', 'mitra')
            ->whereNotNull('kelurahan')
            ->where('kelurahan', '!=', '')
            ->distinct()
            ->orderBy('kelurahan')
            ->pluck('kelurahan');

        $kategori = [
            [
                'slug' => 'organik',
                'nama' => 'Limbah Organik',
                'badge' => 'Biodegradable',
                'badgeClass' => 'bg-[#C6ECCD] text-[#093A25]',
                'badgeIcon' => 'M5 19c8 0 14-6 14-14-8 0-14 6-14 14zm0 0c2-4 5-7 9-9',
                'glow' => 'bg-[#C6ECCD]/60',
                'listing' => 0,
                'deskripsi' =>
                    'Fraksi bahan terurai hayati dari operasional dapur hotel, restoran, agroindustri, dan pasar yang bernilai tinggi untuk pakan maggot BSF, komposting, serta biodiesel.',
                'tags' => [],
                'link' => 'Lihat Direktori Organik',
                'estimasi' => '0',
            ],
            [
                'slug' => 'anorganik',
                'nama' => 'Limbah Anorganik',
                'badge' => 'Recyclable Dry',
                'badgeClass' => 'bg-[#E9EEE8] text-[#414943]',
                'badgeIcon' =>
                    'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
                'glow' => 'bg-[#C6ECCD]/60',
                'listing' => 0,
                'deskripsi' =>
                    'Material kering terpilah langsung dari sumber komersial. Siap diolah oleh pabrik peleburan, pengolah plastik sirkular, dan fasilitas daur ulang kertas karton.',
                'tags' => [],
                'link' => 'Lihat Direktori Anorganik',
                'estimasi' => '0',
            ],
        ];

        $statusConf = [
            'tersedia' => ['label' => 'Tersedia', 'wrap' => 'bg-[#C6ECCD] text-[#093A25]', 'dot' => 'bg-[#093A25]'],
            'diproses' => [
                'label' => 'Sedang Diproses',
                'wrap' => 'bg-[#FFE1D9] text-[#C4411E]',
                'dot' => 'bg-[#C4411E]',
            ],
            'diambil' => ['label' => 'Sudah Diambil', 'wrap' => 'bg-[#E9EEE8] text-[#5F6B63]', 'dot' => 'bg-[#8A968E]'],
        ];

        // Angka dan contoh material tiap kategori dihitung dari data laporan (menimpa nilai bawaan di atas)
        $kategori = collect($kategori)->map(function ($k) use ($publik, $aktif, $statusDari, $keKg) {
            $semua = $publik->filter(fn($r) => ($r->kategori->nama_kategori ?? null) === $k['nama']);

            $k['listing'] = $aktif->filter(fn($r) => ($r->kategori->nama_kategori ?? null) === $k['nama'])->count();

            $bulanIni = $semua->filter(
                fn($r) => $r->tanggal_pencatatan && \Carbon\Carbon::parse($r->tanggal_pencatatan)->gte(now()->subDays(30)),
            );
            $ton = $bulanIni->sum(fn($r) => $keKg($r->estimasi_volume, $r->satuan)) / 1000;
            $k['estimasi'] = number_format($ton, 1, ',', '.');

            $k['tags'] = $semua
                ->pluck('judul')
                ->map(fn($j) => \Illuminate\Support\Str::limit($j, 28))
                ->unique()
                ->take(4)
                ->values()
                ->all();

            return $k;
        })->all();

        $labelSatuan = ['kg' => 'KG', 'ton' => 'Ton', 'liter' => 'Liter', 'pcs' => 'Unit'];
    @endphp

    {{-- Font sesuai Figma: Plus Jakarta Sans (judul), Inter (isi), JetBrains Mono (label) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap"
        rel="stylesheet">

    {{-- ========================================== --}}
    {{-- STYLE: FONT, WARNA, DAN PETA --}}
    {{-- ========================================== --}}
    @verbatim
        <style>
            .sirkula-home {
                --f-display: 'Plus Jakarta Sans', 'Inter', ui-sans-serif, system-ui, sans-serif;
                --f-body: 'Inter', ui-sans-serif, system-ui, sans-serif;
                --f-mono: 'JetBrains Mono', ui-monospace, 'SFMono-Regular', Menlo, monospace;

                --c-ink: #093A25;
                --c-forest: #23533D;
                --c-forest-deep: #19402F;
                --c-body: #414943;
                --c-soft: #F0F5F0;
                --c-chip: #E9EEE8;
                --c-line: #E6ECE6;
                --c-mint: #C6ECCD;
                --c-rust: #C4411E;

                font-family: var(--f-body);
                color: var(--c-body);
                background: #ffffff;
            }

            /* Hero memenuhi satu layar (tinggi layar dikurangi tinggi navbar ± 92px) */
            .sirkula-home .sk-hero {
                display: flex;
                align-items: center;
                min-height: calc(100vh - 92px);
                min-height: calc(100svh - 92px);
            }

            /* Latar hero ala Figma: hijau lembut di tengah-atas, peach di kanan */
            .sirkula-home .sk-hero-glow-green {
                background:
                    radial-gradient(ellipse 52% 62% at 50% 30%,
                        rgba(198, 236, 205, .62) 0%,
                        rgba(214, 241, 219, .50) 35%,
                        rgba(232, 247, 235, .28) 60%,
                        rgba(255, 255, 255, 0) 82%);
            }

            .sirkula-home .sk-hero-glow-peach {
                background:
                    radial-gradient(ellipse 30% 55% at 100% 62%,
                        rgba(253, 222, 210, .65) 0%,
                        rgba(253, 230, 221, .38) 45%,
                        rgba(255, 255, 255, 0) 85%);
            }

            .sirkula-home h1,
            .sirkula-home h2,
            .sirkula-home h3,
            .sirkula-home h4,
            .sirkula-home h5 {
                font-family: var(--f-display);
                letter-spacing: -0.02em;
            }

            .sirkula-home .font-mono {
                font-family: var(--f-mono);
            }

            .sirkula-home .shadow-card {
                box-shadow: 0 1px 2px rgba(9, 58, 37, .04), 0 8px 24px rgba(9, 58, 37, .06);
            }

            /* ---------- Scrollbar daftar lokasi ---------- */
            .sirkula-scroll {
                scrollbar-width: thin;
                scrollbar-color: #C6ECCD transparent;
            }

            .sirkula-scroll::-webkit-scrollbar {
                width: 6px;
            }

            .sirkula-scroll::-webkit-scrollbar-thumb {
                background: #C6ECCD;
                border-radius: 999px;
            }

            /* ---------- Peta ---------- */
            .sirkula-home .leaflet-container {
                font-family: var(--f-body);
                background: #EEF3EE;
                z-index: 1;
            }

            .sirkula-home .leaflet-tile-pane {
                filter: grayscale(.3) saturate(.8) contrast(.97) brightness(1.03);
            }

            /* Zoom control */
            .sirkula-home .leaflet-bar {
                border: none !important;
                border-radius: 14px;
                overflow: hidden;
                box-shadow: 0 6px 18px rgba(9, 58, 37, .16) !important;
            }

            .sirkula-home .leaflet-bar a {
                width: 36px;
                height: 36px;
                line-height: 36px;
                background: #ffffff;
                color: var(--c-ink);
                font-weight: 500;
                border-bottom: 1px solid var(--c-line);
                transition: background .15s;
            }

            .sirkula-home .leaflet-bar a:last-child {
                border-bottom: none;
            }

            .sirkula-home .leaflet-bar a:hover {
                background: var(--c-soft);
                color: var(--c-ink);
            }

            /* Atribusi */
            .sirkula-home .leaflet-control-attribution {
                background: rgba(255, 255, 255, .85);
                border-radius: 8px;
                font-size: 9px;
                color: #5F6B63;
                padding: 2px 8px;
                margin: 10px 0 0 10px;
            }

            .sirkula-home .leaflet-control-attribution a {
                color: var(--c-forest);
            }

            /* Penanda */
            .sk-pin-wrap {
                background: transparent;
                border: none;
            }

            .sk-pin {
                position: relative;
                width: 30px;
                height: 30px;
                border-radius: 50% 50% 50% 0;
                transform: rotate(-45deg);
                background: var(--c-forest);
                border: 3px solid #ffffff;
                box-shadow: 0 6px 14px rgba(9, 58, 37, .35);
                transition: transform .2s ease, background .2s ease;
            }

            .sk-pin::after {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 8px;
                height: 8px;
                margin: -4px 0 0 -4px;
                border-radius: 50%;
                background: #ffffff;
            }

            .sk-pin-wrap:hover .sk-pin {
                transform: rotate(-45deg) scale(1.1);
            }

            .sk-pin.is-active {
                background: var(--c-rust);
                transform: rotate(-45deg) scale(1.25);
            }

            /* Tooltip */
            .sirkula-home .sk-tooltip {
                background: var(--c-ink);
                color: #ffffff;
                border: none;
                border-radius: 8px;
                padding: 5px 10px;
                font-size: 11px;
                font-weight: 600;
                box-shadow: 0 6px 16px rgba(9, 58, 37, .25);
            }

            .sirkula-home .sk-tooltip::before {
                border-top-color: var(--c-ink);
            }

            /* Popup */
            .sirkula-home .sk-popup .leaflet-popup-content-wrapper {
                border-radius: 18px;
                padding: 0;
                overflow: hidden;
                background: #ffffff;
                box-shadow: 0 18px 44px rgba(9, 58, 37, .25), 0 2px 6px rgba(9, 58, 37, .08);
            }

            .sirkula-home .sk-popup .leaflet-popup-content {
                margin: 0;
                width: 260px !important;
                font-family: var(--f-body);
                line-height: 1.4;
            }

            .sirkula-home .sk-popup .leaflet-popup-content p {
                margin: 0;
            }

            .sirkula-home .sk-popup .leaflet-popup-tip {
                background: #ffffff;
                box-shadow: 0 8px 16px rgba(9, 58, 37, .12);
            }

            /* Foto / header popup */
            .sk-pop-media {
                position: relative;
                height: 112px;
                background: linear-gradient(135deg, #DDEFDF 0%, #C6ECCD 100%);
            }

            .sk-pop-media img {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }

            .sk-pop-media::after {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(to top, rgba(9, 58, 37, .35), transparent 55%);
                pointer-events: none;
            }

            .sk-pop-badge {
                position: absolute;
                left: 12px;
                top: 12px;
                z-index: 2;
                background: rgba(255, 255, 255, .95);
                color: var(--c-body);
                font-family: var(--f-mono);
                font-size: 9px;
                font-weight: 700;
                letter-spacing: .12em;
                text-transform: uppercase;
                padding: 4px 9px;
                border-radius: 6px;
                box-shadow: 0 2px 8px rgba(9, 58, 37, .15);
            }

            .sk-pop-body {
                padding: 14px 16px 16px;
            }

            .sk-pop-title {
                font-family: var(--f-display);
                font-size: 15px;
                font-weight: 600;
                letter-spacing: -0.01em;
                line-height: 1.25;
                color: var(--c-ink);
                margin: 0;
            }

            .sk-pop-mitra {
                display: flex;
                align-items: center;
                gap: 6px;
                margin-top: 6px;
                font-family: var(--f-mono);
                font-size: 10px;
                color: #5F6B63;
            }

            .sk-pop-mitra svg {
                width: 12px;
                height: 12px;
                flex-shrink: 0;
            }

            .sk-pop-foot {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                margin-top: 14px;
                padding-top: 14px;
                border-top: 1px solid var(--c-line);
            }

            .sk-pop-vol {
                background: var(--c-mint);
                color: var(--c-ink);
                font-family: var(--f-mono);
                font-size: 12px;
                font-weight: 700;
                padding: 5px 10px;
                border-radius: 8px;
                white-space: nowrap;
            }

            /* Tombol tutup: melayang di atas foto */
            .sirkula-home .sk-popup a.leaflet-popup-close-button {
                top: 10px;
                right: 10px;
                width: 26px;
                height: 26px;
                padding: 0;
                font: 400 18px/26px var(--f-body);
                text-align: center;
                border-radius: 999px;
                color: var(--c-ink);
                background: rgba(255, 255, 255, .95);
                box-shadow: 0 2px 8px rgba(9, 58, 37, .2);
                z-index: 5;
                transition: background .15s;
            }

            .sirkula-home .sk-popup a.leaflet-popup-close-button:hover {
                background: #ffffff;
                color: var(--c-rust);
            }

            .sirkula-home .sk-popup a.sk-detail-btn {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                color: #ffffff !important;
                background: var(--c-ink);
                border-radius: 10px;
                padding: 8px 14px;
                font-size: 11px;
                font-weight: 700;
                text-decoration: none;
                white-space: nowrap;
                transition: background .15s, transform .15s;
            }

            .sirkula-home .sk-popup a.sk-detail-btn:hover {
                background: var(--c-forest);
                transform: translateX(1px);
            }

            /* Kartu daftar lokasi */
            .sk-place {
                border: 1px solid var(--c-line);
                box-shadow: 0 1px 2px rgba(9, 58, 37, .04);
                transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
            }

            .sk-place:hover {
                transform: translateY(-1px);
                box-shadow: 0 8px 20px rgba(9, 58, 37, .08);
            }

            .sk-place.is-active {
                border-color: var(--c-forest);
                box-shadow: 0 0 0 1px var(--c-forest), 0 8px 20px rgba(9, 58, 37, .1);
                background: #F6FBF5;
            }

            @media (prefers-reduced-motion: reduce) {

                .sk-pin,
                .sk-place {
                    transition: none;
                }
            }
        </style>
    @endverbatim

    <div class="sirkula-home">

        {{-- ========================================== --}}
        {{-- 1. HERO SECTION --}}
        {{-- ========================================== --}}
        <section class="sk-hero relative overflow-hidden py-12">
            <!-- Dekorasi Latar (hijau pucat kiri-atas, peach kanan) -->
            {{-- Cahaya hijau besar di tengah (di belakang judul), memudar ke putih ke segala arah --}}
            <div class="sk-hero-glow-green pointer-events-none absolute inset-0"></div>
            {{-- Cahaya peach di sisi kanan --}}
            <div class="sk-hero-glow-peach pointer-events-none absolute inset-0"></div>

            <div class="relative z-10 mx-auto w-full max-w-5xl px-4 text-center sm:px-6 lg:px-8">
                <h1 class="mb-5 text-4xl font-bold leading-[1.1] tracking-tight text-[#093A25] md:text-[54px]">
                    Temukan Limbah yang Masih Bernilai.
                </h1>
                <p class="mx-auto mb-12 max-w-2xl text-base leading-relaxed text-[#414943] md:text-lg">
                    Jelajahi informasi limbah organik dan anorganik dari berbagai mitra bisnis dan perhotelan, lalu hubungi
                    mereka secara langsung via WhatsApp.
                </p>

                <!-- Search Bar Interaktif -->
                <form action="{{ route('pasar.index') }}" method="GET"
                    class="mx-auto mb-5 flex max-w-4xl flex-col items-stretch gap-2 rounded-2xl bg-white p-2.5 shadow-card md:flex-row">

                    <div
                        class="flex flex-1 items-center gap-3 rounded-xl bg-[#EEF3EE] px-4 py-3.5 transition-colors focus-within:bg-white focus-within:ring-1 focus-within:ring-[#23533D]">
                        <svg class="h-[18px] w-[18px] shrink-0 text-[#5F6B63]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari limbah: kardus, ampas..."
                            class="w-full border-none bg-transparent p-0 text-sm text-[#414943] outline-none placeholder:text-[#8A968E] focus:ring-0">
                    </div>

                    <div
                        class="flex flex-1 items-center gap-3 rounded-xl bg-[#EEF3EE] px-4 py-3.5 transition-colors focus-within:bg-white focus-within:ring-1 focus-within:ring-[#23533D]">
                        <svg class="h-[18px] w-[18px] shrink-0 text-[#5F6B63]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z" />
                        </svg>
                        <select name="kategori"
                            class="w-full cursor-pointer appearance-none border-none bg-transparent p-0 text-sm text-[#414943] outline-none focus:ring-0">
                            <option value="">Semua Kategori</option>
                            <option value="organik" @selected(request('kategori') === 'organik')>Organik</option>
                            <option value="anorganik" @selected(request('kategori') === 'anorganik')>Anorganik</option>
                        </select>
                    </div>

                    <div
                        class="flex flex-1 items-center gap-3 rounded-xl bg-[#EEF3EE] px-4 py-3.5 transition-colors focus-within:bg-white focus-within:ring-1 focus-within:ring-[#23533D]">
                        <svg class="h-[18px] w-[18px] shrink-0 text-[#5F6B63]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <select name="wilayah"
                            class="w-full cursor-pointer appearance-none border-none bg-transparent p-0 text-sm text-[#414943] outline-none focus:ring-0">
                            <option value="">Semua Wilayah</option>
                            @foreach ($wilayahOpsi as $opsi)
                                <option value="{{ $opsi }}" @selected(request('wilayah') === $opsi)>{{ $opsi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit"
                        class="flex items-center justify-center gap-2 whitespace-nowrap rounded-xl bg-[#23533D] px-8 py-3.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-[#093A25]">
                        Jelajahi Limbah <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>

                <div class="mb-12">
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center gap-1.5 text-xs font-medium text-[#23533D] transition-colors hover:text-[#093A25] hover:underline">
                        Saya pengelola usaha & ingin menyediakan informasi limbah <svg class="h-3.5 w-3.5" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>

                <!-- Stats Pills -->
                <div class="flex flex-wrap justify-center gap-3">
                    <div
                        class="flex items-center gap-2.5 rounded-xl bg-[#E9EEE8] px-5 py-3 text-xs text-[#414943]">
                        <span class="h-2 w-2 rounded-full bg-[#2F6B4F]"></span>
                        <span class="font-mono font-bold text-[#093A25]">{{ $totalTonText }} Ton</span> Material Tersirkulasi
                    </div>
                    <div
                        class="flex items-center gap-2.5 rounded-xl bg-[#E9EEE8] px-5 py-3 text-xs text-[#414943]">
                        <span class="h-2 w-2 rounded-full bg-[#2F6B4F]"></span>
                        <span class="font-mono font-bold text-[#093A25]">{{ $jumlahMitra }} Mitra</span> Aktif Berbagi Limbah
                    </div>
                    <div
                        class="flex items-center gap-2.5 rounded-xl bg-[#E9EEE8] px-5 py-3 text-xs text-[#414943]">
                        <span class="h-2 w-2 rounded-full bg-[#9A3A1B]"></span>
                        <span class="font-mono font-bold text-[#093A25]">100% Langsung</span> Via WhatsApp Mitra
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
                    <p class="mb-2 font-mono text-[10px] font-medium uppercase tracking-[0.18em] text-[#5F6B63]">
                        Klasifikasi Aliran Material</p>
                    <h2 class="text-3xl font-semibold tracking-tight text-[#093A25] md:text-4xl">Jelajahi Berdasarkan
                        Jenis</h2>
                </div>
                <p class="max-w-sm text-sm leading-relaxed text-[#414943] md:text-right">
                    Pilih jenis material untuk menelusuri ketersediaan terkini, frekuensi volume timbulan, dan spesifikasi
                    penanganan.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                @foreach ($kategori as $item)
                    <div
                        class="relative flex flex-col overflow-hidden rounded-3xl bg-white p-8 shadow-card transition-transform hover:-translate-y-1 md:p-9">
                        <div
                            class="pointer-events-none absolute -right-12 -top-12 h-48 w-48 rounded-full {{ $item['glow'] }} blur-3xl">
                        </div>

                        <div class="relative mb-7 flex items-start justify-between gap-3">
                            <span
                                class="{{ $item['badgeClass'] }} inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 font-mono text-[10px] font-bold uppercase tracking-wider">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $item['badgeIcon'] }}" />
                                </svg>
                                {{ $item['badge'] }}
                            </span>
                            <span
                                class="inline-flex items-center rounded-lg bg-[#E9EEE8] px-3 py-1.5 font-mono text-[11px] font-medium text-[#414943]">
                                <span class="font-bold text-[#093A25]">{{ $item['listing'] }}</span>&nbsp;Listing Aktif
                            </span>
                        </div>

                        <h3 class="relative mb-3 text-2xl font-semibold tracking-tight text-[#093A25]">{{ $item['nama'] }}
                        </h3>
                        <p class="relative mb-7 text-sm leading-relaxed text-[#414943]">{{ $item['deskripsi'] }}</p>

                        <!-- Tags -->
                        <div class="relative mb-10 flex flex-wrap gap-2">
                            @forelse ($item['tags'] as $tag)
                                <span
                                    class="rounded-lg bg-[#EEF3EE] px-3 py-1.5 text-[11px] font-medium text-[#414943]">{{ $tag }}</span>
                            @empty
                                <span class="text-[11px] italic text-[#8A968E]">Belum ada limbah terdaftar di kategori ini.</span>
                            @endforelse
                        </div>

                        <div class="relative mt-auto flex items-center justify-between pt-2">
                            <a href="{{ route('pasar.index', ['kategori' => $item['slug']]) }}"
                                class="inline-flex items-center gap-2 text-sm font-semibold text-[#093A25] transition-colors hover:text-[#23533D]">
                                {{ $item['link'] }} <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                            <span class="font-mono text-[10px] font-medium tracking-wider text-[#5F6B63]">Est.
                                {{ $item['estimasi'] }} Ton/Bulan</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ========================================== --}}
        {{-- 3. CARA KERJA SIRKULA --}}
        {{-- ========================================== --}}
        <section class="mx-auto max-w-5xl px-4 py-16 text-center sm:px-6 lg:px-8">
            <p class="mb-2 font-mono text-[10px] font-medium uppercase tracking-[0.18em] text-[#5F6B63]">Mekanisme Tanpa
                Perantara</p>
            <h2 class="mb-4 text-3xl font-semibold tracking-tight text-[#093A25] md:text-4xl">Cara Kerja SIRKULA</h2>
            <p class="mx-auto mb-12 max-w-xl text-sm leading-relaxed text-[#414943]">Tiga langkah sederhana untuk
                menghubungkan penghasil limbah dengan pelaku daur ulang secara mandiri, transparan, dan terukur.</p>

            <div class="mb-8 grid grid-cols-1 gap-6 text-left md:grid-cols-3">
                <!-- Step 1 -->
                <div class="rounded-2xl bg-white p-7 shadow-card">
                    <div class="mb-6 flex items-center justify-between">
                        <span class="font-mono text-lg font-bold text-[#23533D]">01</span>
                        <div class="rounded-full bg-[#E9EEE8] p-2.5 text-[#093A25]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                            </svg>
                        </div>
                    </div>
                    <h4 class="mb-2 text-lg font-semibold text-[#093A25]">Temukan Material</h4>
                    <p class="text-[13px] leading-relaxed text-[#414943]">Recycler, peternak, dan pengepul mencari
                        material yang dibutuhkan berdasarkan klasifikasi jenis, estimasi tonase, dan lokasi operasional
                        terdekat.</p>
                </div>
                <!-- Step 2 -->
                <div class="rounded-2xl bg-white p-7 shadow-card">
                    <div class="mb-6 flex items-center justify-between">
                        <span class="font-mono text-lg font-bold text-[#9A3A1B]">02</span>
                        <div class="rounded-full bg-[#E9EEE8] p-2.5 text-[#9A3A1B]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                    </div>
                    <h4 class="mb-2 text-lg font-semibold text-[#093A25]">Hubungi Mitra</h4>
                    <p class="text-[13px] leading-relaxed text-[#414943]">Buka halaman detail informasi limbah dan
                        hubungi penanggung jawab operasional mitra penyedia secara langsung lewat sambungan WhatsApp
                        resmi.</p>
                </div>
                <!-- Step 3 -->
                <div class="rounded-2xl bg-white p-7 shadow-card">
                    <div class="mb-6 flex items-center justify-between">
                        <span class="font-mono text-lg font-bold text-[#5F6B63]">03</span>
                        <div class="rounded-full bg-[#E9EEE8] p-2.5 text-[#23533D]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                    </div>
                    <h4 class="mb-2 text-lg font-semibold text-[#093A25]">Sirkulasikan</h4>
                    <p class="text-[13px] leading-relaxed text-[#414943]">Sepakati jadwal armada angkut atau nilai tebus
                        secara langsung tanpa komisi perantara. Status ketersediaan diperbarui setelah material
                        terangkut.</p>
                </div>
            </div>

            <!-- Box Protokol Independensi -->
            <div class="flex flex-col gap-5 rounded-2xl bg-[#EEF3EE] p-6 text-left sm:flex-row sm:items-center">
                <div class="w-max shrink-0 rounded-full bg-[#E1E8E1] p-2.5 text-[#093A25]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-[#093A25]">Protokol Independensi Platform</h5>
                    <p class="mt-1.5 text-xs leading-relaxed text-[#414943]">SIRKULA bertindak murni sebagai
                        infrastruktur penyedia informasi dan fasilitator koneksi sirkular. Transaksi finansial,
                        penimbangan akhir, izin angkut, dan kesepakatan logistik disepakati secara langsung antara mitra
                        penyedia dan pengolah limbah.</p>
                </div>
            </div>
        </section>

        {{-- ========================================== --}}
        {{-- 4. KETERSEDIAAN LIMBAH TERBARU --}}
        {{-- ========================================== --}}
        <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="mb-10 flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <div>
                    <p
                        class="mb-2 flex items-center gap-2 font-mono text-[10px] font-medium uppercase tracking-[0.18em] text-[#23533D]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#23533D]"></span> Katalog Pembaruan Harian</p>
                    <h2 class="text-3xl font-semibold tracking-tight text-[#093A25] md:text-4xl">Ketersediaan Limbah
                        Terbaru</h2>
                    <p class="mt-2 text-sm text-[#414943]">Temukan material terpilah yang siap diambil langsung dari mitra
                        SIRKULA.</p>
                </div>
                <a href="{{ route('pasar.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-[#E9EEE8] px-5 py-3 text-sm font-semibold text-[#093A25] transition hover:bg-[#DFE7DF]">
                    Lihat Semua Limbah ({{ $totalListing }}) &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @if ($katalogLimbah->count() > 0)
                    @foreach ($katalogLimbah as $row)
                        @php
                            $statusLower = strtolower($row->status ?? '');
                            $statusKey = in_array($statusLower, ['sedang diproses', 'sedang dijemput'], true)
                                ? 'diproses'
                                : (in_array($statusLower, ['sudah diambil', 'selesai'], true)
                                    ? 'diambil'
                                    : 'tersedia');
                            $st = $statusConf[$statusKey];
                            $selesai = $statusKey === 'diambil';
                            $katName = $row->kategori->nama_kategori ?? 'UMUM';

                            $namaMitra = $row->mitra->nama_instansi ?? null;
                            $namaMitra = $namaMitra ?: ($row->mitra->name ?? 'Mitra Sirkula');

                            $lokasiMitra = $row->mitra->kelurahan ?? null;
                            if (! $lokasiMitra && ! empty($row->mitra->alamat_lengkap)) {
                                $lokasiMitra = \Illuminate\Support\Str::limit($row->mitra->alamat_lengkap, 40);
                            }
                            $lokasiMitra = $lokasiMitra ?: 'Lokasi mitra';

                            $volumeText = rtrim(rtrim(number_format((float) $row->estimasi_volume, 2, ',', '.'), '0'), ',');
                            $satuanText = $labelSatuan[strtolower((string) $row->satuan)] ?? strtoupper((string) $row->satuan);

                            $tanggalText = $row->tanggal_pencatatan
                                ? \Carbon\Carbon::parse($row->tanggal_pencatatan)->format('d M Y')
                                : null;
                        @endphp
                        <article
                            class="flex flex-col overflow-hidden rounded-2xl bg-white shadow-card transition hover:-translate-y-1 hover:shadow-lg">
                            <div class="relative h-40 bg-[#E9EEE8]">
                                @if ($row->foto_limbah)
                                    <img src="{{ asset('storage/' . $row->foto_limbah) }}" alt="{{ $row->judul }}"
                                        class="absolute inset-0 h-full w-full object-cover {{ $selesai ? 'grayscale-[60%] opacity-80' : '' }}"
                                        onerror="this.style.display='none'">
                                @else
                                    <div
                                        class="absolute inset-0 flex h-full w-full items-center justify-center text-sm font-medium text-[#8A968E]">
                                        Tanpa Foto</div>
                                @endif

                                <span
                                    class="absolute left-3 top-3 z-10 inline-block rounded-md bg-white/95 px-2 py-1 font-mono text-[9px] font-bold uppercase tracking-widest text-[#414943] shadow-sm">
                                    {{ $katName }}
                                </span>

                                <span
                                    class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 font-mono text-[10px] font-bold shadow-sm {{ $st['wrap'] }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $st['dot'] }}"></span>
                                    {{ $st['label'] }}
                                </span>
                            </div>

                            <div class="flex flex-1 flex-col p-5">
                                <div class="mb-3 flex items-start justify-between gap-2">
                                    <p
                                        class="flex min-w-0 items-center gap-1.5 font-mono text-[10px] font-medium text-[#5F6B63]">
                                        <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="truncate">{{ $namaMitra }}</span>
                                    </p>
                                    <span
                                        class="shrink-0 rounded-md bg-[#E9EEE8] px-2 py-1 font-mono text-[11px] font-bold tracking-wide text-[#093A25]">{{ $volumeText }}
                                        {{ $satuanText }}</span>
                                </div>
                                <h3 class="mb-2 text-lg font-semibold leading-tight text-[#093A25]">{{ $row->judul }}</h3>
                                <p class="mb-5 line-clamp-2 text-xs leading-relaxed text-[#414943]">{{ $row->deskripsi }}
                                </p>

                                <div class="mb-5 mt-auto space-y-1.5">
                                    <div class="flex items-center gap-2 text-[11px] text-[#5F6B63]">
                                        <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="truncate">{{ $lokasiMitra }}</span>
                                    </div>
                                    @if ($tanggalText)
                                        <div class="flex items-center gap-2 text-[11px] text-[#5F6B63]">
                                            <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="truncate">Dicatat: {{ $tanggalText }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    @if ($selesai)
                                        <button disabled
                                            class="flex w-full cursor-not-allowed items-center justify-center gap-2 rounded-xl bg-[#EEF1EE] py-3 text-sm font-semibold text-[#A3ADA6]">
                                            Batch Selesai <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        </button>
                                        <p class="mt-2 text-center text-[10px] text-[#8A968E]">Batch baru dijadwalkan masuk
                                            bulan depan</p>
                                    @else
                                        <a href="{{ route('pasar.show', $row->id) }}"
                                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#E9EEE8] py-3 text-sm font-semibold text-[#093A25] transition hover:bg-[#DCE5DC]">
                                            Hubungi Mitra &nearr;
                                        </a>
                                        <p class="mt-2 text-center text-[10px] text-[#8A968E]">Transaksi dan pengambilan
                                            dilakukan langsung dengan mitra</p>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                @else
                    <div
                        class="col-span-full rounded-2xl bg-[#EEF3EE] py-16 text-center text-sm font-medium text-[#5F6B63]">
                        Belum ada data limbah tersedia saat ini.</div>
                @endif
            </div>
        </section>

        {{-- ========================================== --}}
        {{-- 5. PETA INTERAKTIF --}}
        {{-- ========================================== --}}
        <section class="mx-auto max-w-7xl px-4 pb-24 pt-8 sm:px-6 lg:px-8">
            <div class="rounded-3xl bg-[#EFF4EF] p-6 md:p-8">
                <div class="mb-6 flex flex-col justify-between gap-3 md:flex-row md:items-end">
                    <div>
                        <p class="mb-2 font-mono text-[10px] font-medium uppercase tracking-[0.18em] text-[#5F6B63]">Zona
                            Agregasi Wilayah</p>
                        <h2 class="text-3xl font-semibold tracking-tight text-[#093A25]">Temukan Limbah di Sekitar Anda
                        </h2>
                        <p class="mt-2 max-w-xl text-sm leading-relaxed text-[#414943]">Efisiensikan rute logistik
                            pengangkutan dengan menelusuri sumber limbah berdasarkan klaster kota dan simpul pengumpulan
                            terdekat.</p>
                    </div>
                    <div class="flex items-center gap-2 font-mono text-xs font-bold text-[#093A25]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        {{ $hubTerpetakan }} Hub Aktif Terpetakan
                    </div>
                </div>

                <!-- Tag Wilayah Peta (dihitung dari kelurahan mitra) -->
                @if (count($wilayahPeta) > 0)
                    <div class="mb-6 flex flex-wrap gap-2.5">
                        @foreach ($wilayahPeta as $wil)
                            <span
                                class="flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-medium text-[#093A25] shadow-sm">{{ $wil[0] }}
                                <span
                                    class="rounded-md bg-[#E9EEE8] px-1.5 py-0.5 font-mono text-[10px] font-medium text-[#5F6B63]">{{ $wil[1] }}
                                    listing</span></span>
                        @endforeach
                    </div>
                @endif

                <div class="flex flex-col gap-6 lg:flex-row">
                    <!-- Kolom Kiri: Pencarian & Daftar Lokasi -->
                    <div class="flex w-full flex-col gap-4 lg:w-1/3">
                        <div class="relative">
                            <label for="mapSearch" class="sr-only">Cari mitra atau jenis limbah</label>
                            <input type="text" id="mapSearch" placeholder="Cari mitra atau jenis limbah..."
                                autocomplete="off"
                                class="w-full rounded-xl border-0 bg-white py-3.5 pl-11 pr-14 text-sm text-[#414943] shadow-sm outline-none transition placeholder:text-[#8A968E] focus:ring-2 focus:ring-[#23533D]">
                            <svg class="absolute left-4 top-3.5 h-5 w-5 text-[#8A968E]" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span id="mapCount"
                                class="pointer-events-none absolute right-4 top-[15px] font-mono text-[11px] font-bold text-[#8A968E]"></span>
                        </div>
                        <div id="placesList"
                            class="sirkula-scroll flex max-h-[260px] flex-col gap-3 overflow-y-auto pr-2 lg:max-h-[340px]">
                        </div>
                    </div>

                    <!-- Kolom Kanan: Peta Leaflet -->
                    <div class="w-full lg:w-2/3">
                        <div
                            class="relative isolate h-[360px] w-full overflow-hidden rounded-2xl bg-[#EEF3EE] shadow-card lg:h-[415px]">
                            <div id="peta-sirkula" class="absolute inset-0 z-0"></div>

                            <!-- Overlay Bawah Peta -->
                            <div
                                class="pointer-events-none absolute bottom-0 left-0 z-[500] flex w-full items-end justify-between gap-3 bg-gradient-to-t from-[#093A25]/85 via-[#093A25]/35 to-transparent px-5 pb-4 pt-14">
                                <div class="min-w-0">
                                    <p
                                        class="font-mono text-[10px] font-bold uppercase tracking-[0.18em] text-[#9BE3B0]">
                                        Simpul Sirkulasi Aktif</p>
                                    <p class="mt-0.5 truncate text-sm font-semibold text-white">
                                        {{ $captionWilayah !== '' ? $captionWilayah : 'Belum ada simpul aktif' }}</p>
                                </div>
                                <div
                                    class="hidden shrink-0 items-center gap-1.5 rounded-lg bg-white/20 px-3 py-1.5 text-[10px] font-medium text-white backdrop-blur sm:flex">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                    </svg> {{ $jumlahTitik }} titik limbah terpetakan
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
        <section class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-3xl bg-[#23533D] p-10 shadow-card md:p-14">
                <!-- Dekorasi -->
                <div
                    class="absolute -bottom-24 -right-20 h-[420px] w-[420px] rounded-full border-[56px] border-[#1B4733]">
                </div>
                <div class="absolute right-44 top-8 h-[110px] w-[110px] rounded-full bg-white/10 blur-2xl"></div>

                <div class="relative z-10 max-w-2xl">
                    <span
                        class="mb-6 inline-flex items-center gap-2 rounded-full bg-[#19402F] px-3.5 py-1.5 font-mono text-[10px] font-bold uppercase tracking-wider text-white/90">
                        <span class="h-1.5 w-1.5 rounded-full bg-white"></span> Kemitraan Fasilitas & Usaha
                    </span>
                    <h2 class="mb-4 text-3xl font-semibold leading-tight tracking-tight text-white md:text-4xl">Punya
                        limbah yang masih bisa dimanfaatkan?</h2>
                    <p class="mb-8 text-sm leading-relaxed text-[#9CC1AA] md:text-base">
                        Bagikan informasi limbah terpilah bisnis Anda di SIRKULA agar lebih cepat terserap oleh pengolah
                        kompos, peternak maggot, dan industri manufaktur sirkular lokal. Kurangi biaya TPA, ciptakan dampak
                        nyata.
                    </p>
                    <div class="flex flex-col gap-4 sm:flex-row">
                        <a href="{{ route('laporan.create') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-6 py-3.5 text-center text-sm font-semibold text-[#093A25] shadow-sm transition-colors hover:bg-[#EEF3EE]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m-4-4h8" />
                            </svg>
                            Tambah Informasi Limbah
                        </a>
                        <a href="{{ route('pelajari') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#19402F] px-6 py-3.5 text-center text-sm font-semibold text-white transition-colors hover:bg-[#143526]">
                            Pelajari Lebih Lanjut
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </div>

    {{-- ========================================== --}}
    {{-- SCRIPT PETA TERHUBUNG KE DATABASE --}}
    {{-- ========================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // TERIMA DATA ASLI DARI DATABASE LARAVEL
            const rawLimbah = @json($petaData);

            const labelSatuan = {
                kg: 'KG',
                ton: 'Ton',
                liter: 'Liter',
                pcs: 'Unit'
            };

            function formatVolume(vol, satuan) {
                const angka = parseFloat(vol);
                const teks = isNaN(angka) ? String(vol) : angka.toLocaleString('id-ID', {
                    maximumFractionDigits: 2
                });
                const key = String(satuan || '').toLowerCase();
                return teks + ' ' + (labelSatuan[key] || String(satuan || '').toUpperCase());
            }

            const realPlaces = rawLimbah
                .filter(function(item) {
                    return item.latitude !== null && item.longitude !== null && item.latitude !== '' && item
                        .longitude !== '';
                })
                .map(function(item) {
                    return {
                        id: item.id,
                        nama: item.mitra ? (item.mitra.nama_instansi || item.mitra.name) : 'Mitra Sirkula',
                        kategori: item.kategori ? item.kategori.nama_kategori : 'Umum',
                        judul: item.judul,
                        volume: formatVolume(item.estimasi_volume, item.satuan),
                        foto: item.foto_limbah ? '/storage/' + item.foto_limbah : null,
                        lat: parseFloat(item.latitude),
                        lng: parseFloat(item.longitude)
                    };
                });

            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const esc = (s) => String(s).replace(/[&<>"']/g, (c) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            } [c]));

            const peta = L.map('peta-sirkula', {
                center: [-8.6705, 115.2126],
                zoom: 11,
                scrollWheelZoom: false,
                zoomControl: false,
                attributionControl: false,
            });

            // Zoom dipindah ke kanan-atas agar tidak tertutup overlay bawah peta
            L.control.zoom({
                position: 'topright'
            }).addTo(peta);

            L.control.attribution({
                position: 'topleft',
                prefix: false
            }).addTo(peta);

            // Tile OpenStreetMap (tanpa API key); tampilannya dilembutkan lewat CSS
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

            function pinIcon() {
                return L.divIcon({
                    className: 'sk-pin-wrap',
                    html: '<div class="sk-pin"></div>',
                    // Pin berupa kotak 30px yang diputar 45°; ujung runcingnya berada di (15, 36)
                    iconSize: [30, 30],
                    iconAnchor: [15, 36],
                    popupAnchor: [0, -40],
                    tooltipAnchor: [0, -36]
                });
            }

            function setActive(id) {
                activeId = id;
                Object.entries(markersMap).forEach(([key, marker]) => {
                    const isActive = String(key) === String(id);
                    const el = marker.getElement();
                    if (el) {
                        const pin = el.querySelector('.sk-pin');
                        if (pin) pin.classList.toggle('is-active', isActive);
                    }
                    marker.setZIndexOffset(isActive ? 1000 : 0);
                });

                listContainer.querySelectorAll('[data-id]').forEach((card) => {
                    card.classList.toggle('is-active', String(card.dataset.id) === String(id));
                });
            }

            function renderList(places) {
                listContainer.innerHTML = '';
                if (countEl) countEl.textContent = places.length + '/' + realPlaces.length;

                if (places.length === 0) {
                    listContainer.innerHTML = `
                        <div class="flex flex-col items-center gap-2 rounded-2xl bg-white px-4 py-8 text-center">
                            <p class="text-xs font-medium text-[#5F6B63]">Belum ada data limbah berkoordinat valid dari database.</p>
                        </div>`;
                    return;
                }

                places.forEach(function(place) {
                    const card = document.createElement('button');
                    card.type = 'button';
                    card.dataset.id = place.id;
                    card.className = "sk-place group flex w-full cursor-pointer flex-col gap-1.5 rounded-2xl bg-white p-4 text-left";
                    card.innerHTML = `
                        <div class="mb-1 flex items-center justify-between gap-2">
                            <span class="inline-flex items-center rounded-full bg-[#E9EEE8] px-2.5 py-1 font-mono text-[9px] font-bold uppercase tracking-wider text-[#414943]">${esc(place.kategori)}</span>
                            <span class="rounded-md bg-[#C6ECCD] px-2 py-0.5 font-mono text-[11px] font-bold text-[#093A25]">${esc(place.volume)}</span>
                        </div>
                        <h4 class="text-sm font-semibold leading-snug text-[#093A25]">${esc(place.judul)}</h4>
                        <p class="mt-0.5 flex items-center gap-1.5 font-mono text-[10px] text-[#5F6B63]">
                            <svg class="h-3 w-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="truncate">${esc(place.nama)}</span>
                        </p>
                    `;

                    card.addEventListener('click', function() {
                        peta.flyTo([place.lat, place.lng], 14, {
                            duration: reduceMotion ? 0 : 1.2
                        });
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
                    const marker = L.marker([place.lat, place.lng], {
                        icon: pinIcon(),
                        keyboard: true,
                        title: place.judul
                    }).addTo(peta);

                    marker.bindTooltip(esc(place.judul), {
                        direction: 'top',
                        className: 'sk-tooltip',
                        opacity: 1
                    });

                    const media = place.foto ?
                        `<img src="${esc(place.foto)}" alt="${esc(place.judul)}" loading="lazy" onerror="this.style.display='none'">` :
                        '';

                    marker.bindPopup(`
                        <div class="sk-pop">
                            <div class="sk-pop-media">
                                ${media}
                                <span class="sk-pop-badge">${esc(place.kategori)}</span>
                            </div>
                            <div class="sk-pop-body">
                                <h4 class="sk-pop-title">${esc(place.judul)}</h4>
                                <p class="sk-pop-mitra">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>${esc(place.nama)}</span>
                                </p>
                                <div class="sk-pop-foot">
                                    <span class="sk-pop-vol">${esc(place.volume)}</span>
                                    <a href="/pasar/${place.id}" class="sk-detail-btn">Lihat Detail <span aria-hidden="true">&rarr;</span></a>
                                </div>
                            </div>
                        </div>
                    `, {
                        className: 'sk-popup',
                        maxWidth: 260,
                        minWidth: 260,
                        autoPanPaddingTopLeft: [24, 24],
                        autoPanPaddingBottomRight: [24, 90]
                    });

                    marker.on('click', () => {
                        setActive(place.id);
                        const card = listContainer.querySelector(`[data-id="${place.id}"]`);
                        if (card) card.scrollIntoView({
                            block: 'nearest',
                            behavior: reduceMotion ? 'auto' : 'smooth'
                        });
                    });

                    markersMap[place.id] = marker;
                });
            }

            function fitToPlaces(places) {
                if (!places.length) return;
                const bounds = L.latLngBounds(places.map(p => [p.lat, p.lng]));
                peta.fitBounds(bounds, {
                    padding: [60, 60],
                    maxZoom: 13,
                    animate: !reduceMotion
                });
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