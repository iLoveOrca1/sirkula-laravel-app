@extends('layouts.app')

@section('content')

    @php
        // ---------- DATA STATIS & DINAMIS ----------
        \$totalListing = isset($limbahMap) ? count($limbahMap) : 128;
        \$katalogLimbah = isset($limbahMap) ? collect($limbahMap)->take(6) : collect();

        \$kategori = [
            [
                'slug' => 'organik',
                'nama' => 'Limbah Organik',
                'badge' => 'Biodegradable',
                'badgeClass' => 'bg-[#c9ecd3] text-[#1d5b3a]',
                'badgeIcon' => 'M5 19c8 0 14-6 14-14-8 0-14 6-14 14zm0 0c2-4 5-7 9-9',
                'glow' => 'bg-green-200/60',
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
                'badgeClass' => 'bg-gray-200/80 text-gray-700',
                'badgeIcon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
                'glow' => 'bg-orange-200/50',
                'listing' => 86,
                'deskripsi' => 'Material kering terpilah langsung dari sumber komersial. Siap diolah oleh pabrik peleburan, pengolah plastik sirkular, dan fasilitas daur ulang kertas karton.',
                'tags' => ['Kardus Corrugated (OCC)', 'Botol PET Bening Pres', 'Pecahan Botol Kaca', 'Palet Kayu Bekas'],
                'link' => 'Lihat Direktori Anorganik',
                'estimasi' => '109.8',
            ],
        ];

        \$statusConf = [
            'tersedia' => ['label' => 'Tersedia', 'wrap' => 'bg-[#c9ecd3] text-[#1d5b3a]', 'dot' => 'bg-[#2f8f5b]'],
            'diproses' => ['label' => 'Sedang Diproses', 'wrap' => 'bg-[#fde3d8] text-[#b0431b]', 'dot' => 'bg-[#e0592a]'],
            'diambil' => ['label' => 'Sudah Diambil', 'wrap' => 'bg-gray-100 text-gray-600', 'dot' => 'bg-gray-400'],
        ];
    @endphp

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- ========================================== --}}
    {{-- STYLE TAMBAHAN --}}
    {{-- ========================================== --}}
    @verbatim
        <style>
            .sirkula-page {
                font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
                -webkit-font-smoothing: antialiased;
                text-rendering: optimizeLegibility;
            }
            .sirkula-page .font-mono {
                font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, monospace;
                font-variant-numeric: tabular-nums;
            }
            .sirkula-page h1, .sirkula-page h2 { text-wrap: balance; }
            .sirkula-page p { text-wrap: pretty; }

            .sirkula-page a:focus-visible,
            .sirkula-page button:focus-visible,
            .sirkula-page input:focus-visible,
            .sirkula-page select:focus-visible {
                outline: 2px solid #24523c;
                outline-offset: 3px;
                border-radius: 12px;
            }

            .sirkula-page .rise {
                opacity: 0;
                transform: translateY(14px);
                animation: sirkula-rise .7s cubic-bezier(.2, .7, .2, 1) forwards;
                animation-delay: var(--d, 0ms);
            }
            @keyframes sirkula-rise {
                to { opacity: 1; transform: none; }
            }

            .sirkula-dots {
                background-image: radial-gradient(#24523c1f 1px, transparent 1px);
                background-size: 22px 22px;
                -webkit-mask-image: radial-gradient(ellipse 70% 60% at 50% 30%, #000 30%, transparent 75%);
                mask-image: radial-gradient(ellipse 70% 60% at 50% 30%, #000 30%, transparent 75%);
            }

            .sirkula-scroll {
                scrollbar-width: thin;
                scrollbar-color: #b7c9b9 transparent;
            }
            .sirkula-scroll::-webkit-scrollbar { width: 6px; }
            .sirkula-scroll::-webkit-scrollbar-thumb { background: #b7c9b9; border-radius: 999px; }

            .sirkula-page .leaflet-container { font-family: inherit; background: #e5ebe5; }
            .sirkula-page .leaflet-popup-content-wrapper { border-radius: 16px; box-shadow: 0 12px 32px rgba(15, 61, 42, .18); padding: 0; }
            .sirkula-page .leaflet-popup-content { margin: 14px 16px; }
            .sirkula-page .leaflet-bar { border: none !important; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 14px rgba(15, 61, 42, .15) !important; }
            .sirkula-page .leaflet-bar a { color: #0f3d2a; border-bottom-color: #e5ebe5; }
            .sirkula-page .leaflet-tooltip { border: none; border-radius: 8px; box-shadow: 0 4px 14px rgba(15, 61, 42, .18); font-size: 11px; font-weight: 600; color: #0f3d2a; }
        </style>
    @endverbatim

    <div class="sirkula-page">

        {{-- ========================================== --}}
        {{-- 1. HERO --}}
        {{-- ========================================== --}}
        <section class="relative overflow-hidden bg-gradient-to-b from-[#eef6ef] via-[#f6faf6] to-white pb-20 pt-24">
            <div class="sirkula-dots pointer-events-none absolute inset-0"></div>
            <div class="pointer-events-none absolute -right-40 top-48 h-[26rem] w-[26rem] rounded-full bg-orange-100/60 blur-3xl"></div>
            <div class="pointer-events-none absolute -left-40 top-10 h-[22rem] w-[22rem] rounded-full bg-green-200/40 blur-3xl"></div>

            <div class="relative z-10 mx-auto max-w-6xl px-4 text-center sm:px-6 lg:px-8">
                <h1 class="rise mb-5 text-4xl font-extrabold leading-[1.08] tracking-tight text-[#0f3d2a] md:text-5xl lg:text-6xl" style="--d: 0ms">
                    Temukan Limbah yang Masih Bernilai.
                </h1>
                <p class="rise mx-auto mb-12 max-w-2xl text-base leading-relaxed text-gray-600 md:text-lg" style="--d: 90ms">
                    Jelajahi informasi limbah organik dan anorganik dari berbagai mitra bisnis dan perhotelan, lalu hubungi mereka secara langsung via WhatsApp.
                </p>

                <form action="{{ route('pasar.index') }}" method="GET" class="rise mx-auto mb-5 flex max-w-4xl flex-col items-stretch gap-2 rounded-2xl bg-white p-2.5 shadow-[0_20px_50px_-12px_rgba(15,61,42,0.18)] ring-1 ring-[#0f3d2a]/5 md:flex-row" style="--d: 180ms">
                    <label class="group flex flex-1 cursor-text items-center gap-3 rounded-xl bg-[#f0f3f0] px-4 py-3 ring-1 ring-transparent transition focus-within:bg-white focus-within:ring-[#24523c]/50 hover:bg-[#eaf0ea]">
                        <svg class="h-5 w-5 shrink-0 text-gray-400 transition group-focus-within:text-[#24523c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari limbah: kardus, ampas..." class="w-full border-none bg-transparent p-0 text-sm text-gray-800 outline-none placeholder:text-gray-400 focus:ring-0">
                    </label>

                    <label class="group flex flex-1 cursor-pointer items-center gap-3 rounded-xl bg-[#f0f3f0] px-4 py-3 ring-1 ring-transparent transition focus-within:bg-white focus-within:ring-[#24523c]/50 hover:bg-[#eaf0ea]">
                        <svg class="h-5 w-5 shrink-0 text-gray-400 transition group-focus-within:text-[#24523c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z" /></svg>
                        <select name="kategori" class="w-full cursor-pointer appearance-none border-none bg-transparent p-0 text-sm text-gray-800 outline-none focus:ring-0">
                            <option value="">Semua Kategori</option>
                            <option value="organik" @selected(request('kategori') === 'organik')>Organik</option>
                            <option value="anorganik" @selected(request('kategori') === 'anorganik')>Anorganik</option>
                        </select>
                    </label>

                    <button type="submit" class="group flex items-center justify-center gap-2 whitespace-nowrap rounded-xl bg-[#24523c] px-7 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-[#1b4430]">
                        Jelajahi Limbah <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </form>

                <a href="{{ route('register') }}" class="rise group mb-14 inline-flex items-center gap-1 text-xs font-medium text-[#24523c] transition hover:text-[#0f3d2a]" style="--d: 260ms">
                    Saya pengelola usaha &amp; ingin menyediakan informasi limbah <svg class="h-3.5 w-3.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
            </div>
        </section>

        {{-- ========================================== --}}
        {{-- 2. KATEGORI --}}
        {{-- ========================================== --}}
        <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="mb-10 flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <div>
                    <p class="mb-2 font-mono text-[10px] uppercase tracking-widest text-gray-500">Klasifikasi Aliran Material</p>
                    <h2 class="text-3xl font-bold tracking-tight text-[#0f3d2a]">Jelajahi Berdasarkan Jenis</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                @foreach ($kategori as$item)
                    <div class="group relative flex flex-col overflow-hidden rounded-3xl bg-white p-8 shadow-[0_8px_30px_rgba(15,61,42,0.07)] ring-1 ring-[#0f3d2a]/5 transition duration-300 hover:-translate-y-1">
                        <div class="pointer-events-none absolute -right-10 -top-10 h-44 w-44 rounded-full {{ \$item['glow'] }} blur-2xl"></div>
                        <div class="relative mb-6 flex items-start justify-between gap-3">
                            <span class="{{ \$item['badgeClass'] }} inline-flex items-center gap-1.5 rounded-full px-3 py-1 font-mono text-[10px] font-semibold uppercase">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ \$item['badgeIcon'] }}" /></svg>
                                {{ \$item['badge'] }}
                            </span>
                        </div>
                        <h3 class="relative mb-3 text-2xl font-semibold tracking-tight text-[#0f3d2a]">{{ \$item['nama'] }}</h3>
                        <p class="relative mb-6 text-sm leading-relaxed text-gray-600">{{ \$item['deskripsi'] }}</p>
                        <div class="relative mt-auto flex items-center justify-between border-t border-gray-100 pt-5">
                            <a href="{{ route('pasar.index', ['kategori' => \$item['slug']]) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#0f3d2a] transition hover:text-green-700">
                                {{ \$item['link'] }} <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ========================================== --}}
        {{-- 3. KETERSEDIAAN LIMBAH TERBARU (DARI DB) --}}
        {{-- ========================================== --}}
        <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="mb-10 flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-[#0f3d2a]">Ketersediaan Limbah Terbaru</h2>
                </div>
                <a href="{{ route('pasar.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#eaf0ea] px-5 py-3 text-sm font-medium text-gray-800 transition hover:bg-[#dfe8df]">
                    Lihat Semua Limbah ({{ \$totalListing }})
                </a>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @if (isset($limbahMap) && count($limbahMap) > 0)
                    @foreach ($katalogLimbah as$row)
                        @php
                            $statusLower = strtolower($row->status ?? '');
                            \$statusKey = $statusLower === 'sedang dijemput' ? 'diproses' : ($statusLower === 'selesai' ? 'diambil' : 'tersedia');
                            $st =$statusConf[$statusKey] ?? $statusConf['tersedia'];
                            $selesai =$statusKey === 'diambil';
                        @endphp
                        <article class="flex flex-col overflow-hidden rounded-2xl bg-white shadow-[0_4px_20px_rgba(15,61,42,0.06)] ring-1 ring-[#0f3d2a]/5 transition hover:shadow-lg">
                            <div class="relative h-44 bg-gray-200">
                                @if (\$row->foto_limbah)
                                    <img src="{{ asset('storage/' . $row->foto_limbah) }}" alt="{{ $row->judul }}" class="h-full w-full object-cover {{ \$selesai ? 'grayscale-[40%]' : '' }}" onerror="this.style.display='none'">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-gray-100 text-sm text-gray-400">Tanpa Foto</div>
                                @endif
                                <span class="absolute left-3 top-3 rounded-md bg-white/95 px-2 py-1 font-mono text-[10px] font-semibold uppercase text-gray-800">
                                    {{ \$row->kategori->nama_kategori ?? 'Umum' }}
                                </span>
                                <span class="absolute right-3 top-3 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 font-mono text-[10px] font-semibold shadow-sm {{ \$st['wrap'] }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $st['dot'] }}"></span> {{ $st['label'] }}
                                </span>
                            </div>
                            <div class="flex flex-1 flex-col p-5">
                                <p class="mb-2 font-mono text-[10px] text-gray-500">🏭 {{ \$row->mitra->name ?? 'Mitra Sirkula' }}</p>
                                <h3 class="mb-2 text-lg font-semibold text-[#0f3d2a]">{{ \$row->judul }}</h3>
                                <p class="mb-4 line-clamp-2 text-xs text-gray-600">{{ \$row->deskripsi }}</p>
                                <div class="mt-auto">
                                    <a href="{{ route('pasar.show', \$row->id) }}" class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#eaf0ea] py-3 text-sm font-medium text-[#0f3d2a] transition hover:bg-[#24523c] hover:text-white">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                @else
                    <div class="col-span-full rounded-2xl border border-dashed border-gray-200 bg-gray-50 py-12 text-center text-gray-500">Belum ada data limbah tersedia.</div>
                @endif
            </div>
        </section>

        {{-- ========================================== --}}
        {{-- 4. PETA INTERAKTIF REAL-TIME DARI DATABASE --}}
        {{-- ========================================== --}}
        <section class="mx-auto max-w-6xl px-4 pb-24 pt-8 sm:px-6 lg:px-8">
            <div class="rounded-3xl bg-[#eff4ee] p-6 shadow-sm ring-1 ring-[#0f3d2a]/5 md:p-8">
                <div class="mb-6 flex flex-col justify-between gap-2 md:flex-row md:items-end">
                    <div>
                        <p class="mb-1 font-mono text-[10px] uppercase tracking-widest text-[#2f5d46]">Eksplorasi Geografis</p>
                        <h2 class="text-3xl font-bold tracking-tight text-[#0f3d2a]">Peta Persebaran Limbah</h2>
                    </div>
                    <p class="text-xs text-gray-500">Klik item di daftar untuk memfokuskan lokasi pada peta.</p>
                </div>

                <div class="flex flex-col gap-6 lg:flex-row">
                    <!-- Kolom Kiri: Pencarian & Daftar Lokasi -->
                    <div class="flex w-full flex-col gap-4 lg:w-1/3">
                        <div class="relative">
                            <label for="mapSearch" class="sr-only">Cari mitra atau jenis limbah</label>
                            <input type="text" id="mapSearch" placeholder="Cari mitra atau jenis limbah..." autocomplete="off"
                                class="w-full rounded-xl border-none bg-white py-3.5 pl-11 pr-12 text-sm text-gray-700 shadow-sm outline-none ring-1 ring-transparent transition placeholder:text-gray-400 focus:ring-2 focus:ring-[#24523c]">
                            <svg class="absolute left-4 top-3.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <span id="mapCount" class="pointer-events-none absolute right-4 top-[15px] font-mono text-[11px] text-gray-400"></span>
                        </div>

                        <div id="placesList" class="sirkula-scroll flex max-h-[400px] flex-col gap-3 overflow-y-auto pr-1"></div>
                    </div>

                    <!-- Kolom Kanan: Peta Leaflet -->
                    <div class="w-full lg:w-2/3">
                        <div class="relative isolate h-[400px] w-full overflow-hidden rounded-2xl bg-gray-200 shadow-inner ring-1 ring-[#0f3d2a]/10 lg:h-[465px]">
                            <div id="peta-sirkula" class="absolute inset-0 z-0"></div>
                            <div class="pointer-events-none absolute right-4 top-4 z-[600] rounded-lg bg-[#0f3d2a]/80 px-3 py-1.5 font-mono text-[10px] font-semibold uppercase tracking-widest text-white shadow-sm backdrop-blur">
                                ↹ Real-Time Map
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ========================================== --}}
        {{-- 5. FOOTER & CTA --}}
        {{-- ========================================== --}}
        <footer class="bg-[#f0f5ef] pb-10 pt-20">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 text-center text-xs text-gray-600">
                <p>&copy; 2026 SIRKULA. Hak cipta dilindungi undang-undang.</p>
            </div>
        </footer>

    </div>

    {{-- ========================================== --}}
    {{-- SCRIPT PETA TERHUBUNG KE DATABASE --}}
    {{-- ========================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // TERIMA DATA ASLI DARI DATABASE LARAVEL (Hanya ambil yang memiliki koordinat valid)
            const rawLimbah = @json(\$limbahMap ?? []);

            // Petakan data database ke format JavaScript secara akurat tanpa koordinat random
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

            // Inisialisasi Peta berpusat di Bali
            const peta = L.map('peta-sirkula', {
                center: [-8.6705, 115.2126],
                zoom: 11,
                scrollWheelZoom: false,
                zoomControl: false,
            });
            L.control.zoom({ position: 'bottomright' }).addTo(peta);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
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
                    card.classList.toggle('ring-2', isActive);
                    card.classList.toggle('ring-[#24523c]', isActive);
                    card.classList.toggle('bg-[#f7fbf7]', isActive);
                });
            }

            function renderList(places) {
                listContainer.innerHTML = '';
                if (countEl) countEl.textContent = places.length + '/' + realPlaces.length;

                if (places.length === 0) {
                    listContainer.innerHTML = `
                        <div class="flex flex-col items-center gap-2 rounded-2xl bg-white px-4 py-8 text-center shadow-sm">
                            <p class="text-xs text-gray-500">Belum ada data limbah berkoordinat valid dari database.</p>
                        </div>`;
                    return;
                }

                places.forEach(function(place) {
                    const card = document.createElement('button');
                    card.type = 'button';
                    card.dataset.id = place.id;
                    card.className = "group flex w-full cursor-pointer flex-col gap-1.5 rounded-2xl border border-gray-100 bg-white p-4 text-left shadow-sm transition hover:-translate-y-0.5 hover:shadow-md hover:ring-2 hover:ring-[#24523c]/30";
                    card.innerHTML = `
                        <div class="flex items-center justify-between gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2 py-0.5 font-mono text-[9px] font-semibold uppercase tracking-wider text-green-800">${esc(place.kategori)}</span>
                            <span class="font-mono text-xs font-bold text-gray-800">${esc(place.volume)}</span>
                        </div>
                        <h4 class="text-sm font-bold leading-snug text-[#0f3d2a] transition group-hover:text-green-700">${esc(place.judul)}</h4>
                        <p class="text-xs text-gray-500">🏭 ${esc(place.nama)}</p>
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
                        radius: 7, color: '#ffffff', weight: 2, fillColor: '#24523c', fillOpacity: 1
                    }).addTo(peta);

                    marker.bindPopup(`
                        <div class="min-w-[190px] text-left">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2 py-0.5 font-mono text-[9px] font-bold uppercase text-green-800">${esc(place.kategori)}</span>
                            <h4 class="mt-2 text-xs font-bold leading-tight text-[#0f3d2a]">${esc(place.judul)}</h4>
                            <p class="mt-0.5 text-[11px] text-gray-500">${esc(place.nama)}</p>
                            <div class="mt-2.5 flex items-center justify-between gap-3 border-t border-gray-100 pt-2">
                                <span class="font-mono text-[11px] font-bold text-gray-800">${esc(place.volume)}</span>
                                <a href="/pasar/${place.id}" class="rounded bg-[#0f3d2a] px-2.5 py-1 text-[10px] text-white hover:bg-green-800">Detail</a>
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

            // Jalankan awal
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