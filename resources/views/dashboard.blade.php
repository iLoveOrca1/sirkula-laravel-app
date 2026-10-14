@extends('layouts.app')

@section('content')
    @php
        // ------------------------------------------------------------------
        // Helper tampilan & Logika Filter
        // ------------------------------------------------------------------
        $sort = $sort ?? 'update';
        $dir = $dir ?? 'desc';

        $filters = [
            'status' => $statusKey ?: null,
            'q' => $q ?: null,
            'kategori' => $kategoriId ?: null,
        ];

        $url = function (array $extra = []) use ($filters, $sort, $dir) {
            $params = $extra + $filters + ['sort' => $sort, 'dir' => $dir];
            return route('laporan.index', array_filter($params, fn($v) => $v !== null && $v !== '')) . '#tabel-riwayat';
        };

        $sortUrl = function (string $col) use ($sort, $dir, $url) {
            $nextDir = $sort === $col ? ($dir === 'asc' ? 'desc' : 'asc') : ($col === 'update' ? 'desc' : 'asc');
            return $url(['sort' => $col, 'dir' => $nextDir]);
        };

        $sortIcon = function (string $col) use ($sort, $dir) {
            if ($sort !== $col) {
                return '<span class="ml-1 text-[#B5BFB7]">&#8645;</span>';
            }
            return $dir === 'asc'
                ? '<span class="ml-1 text-[#093A25]">&#9650;</span>'
                : '<span class="ml-1 text-[#093A25]">&#9660;</span>';
        };

        $statusMeta = function (?string $raw) {
            return match (strtolower((string) $raw)) {
                'tersedia' => ['bg-[#C6ECCD] text-[#1E5638]', 'Tersedia'],
                'sedang diproses' => ['bg-[#FFE1D9] text-[#C4411E]', 'Sedang Diproses'],
                'sudah diambil' => ['bg-[#E9EEE8] text-[#5F6B63]', 'Sudah Diambil'],
                'menunggu' => ['bg-[#FFF1D6] text-[#9A6A00]', 'Menunggu'],
                'ditolak' => ['bg-[#FDE3E3] text-[#B42318]', 'Ditolak'],
                default => ['bg-[#E9EEE8] text-[#5F6B63]', ucfirst((string) $raw)],
            };
        };

        $satuanLabel = fn($s) => match (strtolower((string) $s)) {
            'kg' => 'KG',
            'ton' => 'TON',
            'liter' => 'Liter',
            'pcs' => 'Pcs',
            default => strtoupper((string) $s),
        };

        $fmtAngka = fn($v) => rtrim(rtrim(number_format((float) $v, 2, ',', '.'), '0'), ',');

        $namaInstansi = $user->nama_instansi ?: $user->name;
        $mitraId = 'HTS-' . str_pad($user->id, 4, '0', STR_PAD_LEFT);

        $tabs = [
            '' => ['Semua', $counts['semua']],
            'tersedia' => ['Tersedia', $counts['tersedia']],
            'diproses' => ['Sedang Diproses', $counts['diproses']],
            'diambil' => ['Sudah Diambil', $counts['diambil']],
        ];

        $adaFilter = $q || $statusKey || $kategoriId;
    @endphp

    {{-- Font sesuai Figma: Plus Jakarta Sans (UI/judul) & IBM Plex Mono (label/angka) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    @verbatim
        <style>
            .sirkula-dash {
                --f-ui: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
                --f-mono: 'IBM Plex Mono', ui-monospace, Menlo, monospace;

                --c-ink: #093A25;
                --c-forest: #23533D;
                --c-body: #414943;
                --c-muted: #5F6B63;
                --c-soft: #F1F5F1;
                --c-chip: #E9EEE8;
                --c-line: #E6ECE6;

                font-family: var(--f-ui);
                color: var(--c-body);
            }

            .sirkula-dash .font-mono {
                font-family: var(--f-mono);
            }

            .sirkula-dash h1,
            .sirkula-dash h2,
            .sirkula-dash h3,
            .sirkula-dash h4 {
                font-family: var(--f-ui);
                letter-spacing: -0.015em;
            }

            .sirkula-dash .shadow-card {
                box-shadow: 0 1px 2px rgba(9, 58, 37, .04), 0 6px 20px rgba(9, 58, 37, .06);
            }

            .sirkula-dash dialog {
                font-family: var(--f-ui);
            }
        </style>
    @endverbatim

    <div class="sirkula-dash min-h-screen bg-[#F6FBF5] pb-20 pt-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <!-- ============================================================ -->
            <!-- 1. HEADER WELCOME & TOMBOL TAMBAH                            -->
            <!-- ============================================================ -->
            <div class="mb-8 flex flex-col justify-between gap-6 md:flex-row md:items-start">
                <div>
                    <div
                        class="mb-4 inline-flex items-center gap-2 rounded-full bg-[#E9EEE8] px-3 py-1.5 font-mono text-[10px] font-medium uppercase tracking-[0.08em] text-[#414943]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#093A25]"></span>
                        Portal Mitra Terverifikasi &bull; ID: {{ $mitraId }}
                    </div>
                    <h1 class="text-3xl font-semibold leading-tight text-[#093A25] md:text-[34px]">
                        Selamat datang kembali, {{ $namaInstansi }}
                    </h1>
                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-[#414943]">
                        Kelola inventaris limbah bernilai sirkular Anda dan perbarui status ketersediaan secara berkala demi
                        akurasi mitra pendaur ulang.
                    </p>
                </div>
                <div class="mt-2 shrink-0 md:mt-0">
                    <a href="{{ route('laporan.create') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-[#23533D] px-5 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-[#093A25]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m-4-4h8" />
                        </svg>
                        Tambah Limbah Baru
                    </a>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- 2. KARTU STATISTIK (4 GRID)                                  -->
            <!-- ============================================================ -->
            <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Total Material -->
                <div class="flex min-h-[160px] flex-col justify-between rounded-xl bg-white p-6 shadow-card">
                    <div class="flex items-start justify-between">
                        <p class="text-[13px] text-[#414943]">Total Material Terdaftar</p>
                        <svg class="h-5 w-5 text-[#5F6B63]" fill="none" stroke="currentColor" stroke-width="1.6"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 7h16M5 7v11a2 2 0 002 2h10a2 2 0 002-2V7M4 7l1.5-3h13L20 7M10 12h4" />
                        </svg>
                    </div>
                    <div>
                        <div class="mt-3 flex items-baseline gap-2">
                            <span
                                class="font-mono text-[44px] font-semibold leading-none text-[#093A25]">{{ str_pad($counts['semua'], 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-[13px] text-[#414943]">Stream Material</span>
                        </div>
                        <p class="mt-3 flex items-center gap-1.5 font-mono text-[10px] text-[#414943]">
                            <svg class="h-3 w-3 text-[#23533D]" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ $stat['kategori'] ?? 2 }} divisi operasional aktif
                        </p>
                    </div>
                </div>

                <!-- Status Tersedia -->
                <div class="flex min-h-[160px] flex-col justify-between rounded-xl bg-white p-6 shadow-card">
                    <div class="flex items-start justify-between">
                        <p class="text-[13px] text-[#414943]">Status Tersedia</p>
                        <span
                            class="rounded-md bg-[#C6ECCD] px-2 py-0.5 font-mono text-[10px] font-semibold text-[#1E5638]">Tersedia</span>
                    </div>
                    <div>
                        <div class="mt-3 flex items-baseline gap-2">
                            <span
                                class="font-mono text-[44px] font-semibold leading-none text-[#093A25]">{{ str_pad($counts['tersedia'], 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-[13px] text-[#414943]">Siap Diambil</span>
                        </div>
                        <p class="mt-3 flex items-center gap-1.5 font-mono text-[10px] text-[#5F6B63]">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Tampil aktif di Jelajahi Limbah
                        </p>
                    </div>
                </div>

                <!-- Sedang Diproses -->
                <div class="flex min-h-[160px] flex-col justify-between rounded-xl bg-white p-6 shadow-card">
                    <div class="flex items-start justify-between">
                        <p class="text-[13px] text-[#414943]">Sedang Diproses</p>
                        <span
                            class="rounded-md bg-[#FFE1D9] px-2 py-0.5 font-mono text-[10px] font-semibold text-[#C4411E]">Pick-up</span>
                    </div>
                    <div>
                        <div class="mt-3 flex items-baseline gap-2">
                            <span
                                class="font-mono text-[44px] font-semibold leading-none text-[#9A3A1B]">{{ str_pad($counts['diproses'], 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-[13px] text-[#414943]">Menunggu Pick-up</span>
                        </div>
                        <p class="mt-3 flex items-center gap-1.5 font-mono text-[10px] text-[#C4411E]">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Kontak recycler terverifikasi
                        </p>
                    </div>
                </div>

                <!-- Sirkulasi Sukses -->
                <div class="flex min-h-[160px] flex-col justify-between rounded-xl bg-white p-6 shadow-card">
                    <div class="flex items-start justify-between">
                        <p class="text-[13px] text-[#414943]">Sirkulasi Sukses</p>
                        <span
                            class="rounded-md bg-[#E9EEE8] px-2 py-0.5 font-mono text-[10px] font-semibold text-[#5F6B63]">Selesai</span>
                    </div>
                    <div>
                        <div class="mt-3 flex items-baseline gap-2">
                            <span
                                class="font-mono text-[44px] font-semibold leading-none text-[#093A25]">{{ str_pad($counts['diambil'] ?? 2, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-[13px] text-[#414943]">Batch Bulan Ini</span>
                        </div>
                        <p class="mt-3 flex items-center gap-1.5 font-mono text-[10px] text-[#414943]">
                            <svg class="h-3 w-3 text-[#23533D]" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5 19c0-8 5-13 14-14 0 9-5 14-14 14zM5 19l7-7" />
                            </svg>
                            Est. {{ $dampak['massa'] ?? '1.8 Ton' }} teralihkan dari TPA
                        </p>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- 3. BANNER PROTOKOL REAL-TIME                                 -->
            <!-- ============================================================ -->
            <div class="mb-6 flex items-start gap-4 rounded-xl border border-[#E3E9E3] bg-[#F1F5F1] p-5">
                <div class="mt-0.5 shrink-0 rounded-lg bg-white p-2.5 text-[#093A25] shadow-sm">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <div>
                    <h4 class="flex flex-wrap items-center gap-2 text-[15px] font-semibold text-[#093A25]">
                        Protokol Pembaruan Status Real-Time
                        <span
                            class="rounded bg-[#E3E9E3] px-2 py-0.5 font-mono text-[9px] font-semibold uppercase tracking-wider text-[#5F6B63]">SOP
                            Sirkula</span>
                    </h4>
                    <p class="mt-1.5 pr-4 text-xs leading-relaxed text-[#414943]">
                        Selalu perbarui status limbah menjadi 'Sedang Diproses' saat recycler telah menghubungi Anda via
                        WhatsApp atau instrumen logistik. Ganti ke 'Sudah Diambil' seketika armada mitra menyelesaikan
                        pemuatan di dok muat agar data telemetri sirkular tetap presisi.
                    </p>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- 3b. KARTU MENU: PERMINTAAN MATERIAL (layout horizontal)      -->
            <!-- ============================================================ -->
            <a href="{{ route('permintaan.dashboard') }}"
                class="group mb-12 flex flex-col gap-5 rounded-xl bg-white p-5 shadow-card transition-all hover:-translate-y-0.5 hover:shadow-lg sm:flex-row sm:items-center sm:p-6">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#E9EEE8] text-[#093A25] transition-colors group-hover:bg-[#23533D] group-hover:text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>

                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="text-base font-semibold text-[#093A25]">Permintaan Material</h3>
                        <span
                            class="rounded bg-[#E9EEE8] px-2 py-0.5 font-mono text-[9px] font-semibold uppercase tracking-wider text-[#5F6B63]">Kebutuhan
                            Recycler</span>
                    </div>
                    <p class="mt-1 max-w-2xl text-[13px] leading-relaxed text-[#414943]">
                        Lihat daftar kebutuhan material dari recycler dan industri. Hubungi mereka jika Anda memiliki
                        pasokan yang sesuai.
                    </p>
                </div>

                <span
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-[#E9EEE8] px-4 py-2.5 text-[13px] font-semibold text-[#093A25] transition-colors group-hover:bg-[#23533D] group-hover:text-white">
                    Lihat Daftar Permintaan
                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </span>
            </a>

            <!-- ============================================================ -->
            <!-- 4. LIMBAH SAYA (TABEL, SEARCH, FILTER)                       -->
            <!-- ============================================================ -->
            <div id="tabel-riwayat" class="scroll-mt-24">

                <!-- Title & Tabs -->
                <div class="mb-5 flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
                    <div>
                        <h2 class="text-2xl font-semibold text-[#093A25]">Limbah Saya</h2>
                        <p class="mt-1 text-xs text-[#414943]">Inventaris aliran material terdaftar di area
                            {{ $namaInstansi }}</p>
                    </div>

                    <!-- Tab Filter Status -->
                    <div class="inline-flex flex-wrap gap-1 rounded-xl bg-[#E9EEE8] p-1">
                        @foreach ($tabs as $key => [$label, $jumlah])
                            @php $aktif = ($statusKey ?? '') === (string) $key; @endphp
                            <a href="{{ $url(['status' => $key === '' ? null : $key]) }}"
                                class="flex items-center gap-1.5 rounded-lg px-4 py-2 text-[13px] font-medium transition-all duration-200
                                      {{ $aktif ? 'bg-white text-[#093A25] shadow-[0_1px_4px_rgba(9,58,37,0.12)]' : 'text-[#414943] hover:text-[#093A25]' }}">
                                {{ $label }}
                                <span
                                    class="font-mono text-[10px] {{ $key === 'diproses' ? 'text-[#C4411E]' : 'text-[#5F6B63]' }}">({{ $jumlah }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Search Bar & Table Container -->
                <div class="mb-12 rounded-xl bg-white shadow-card">

                    <!-- Search Header -->
                    <form method="GET" action="{{ route('laporan.index') }}"
                        class="flex flex-col items-center gap-3 p-4 sm:flex-row">
                        <input type="hidden" name="status" value="{{ $statusKey }}">
                        <input type="hidden" name="sort" value="{{ $sort }}">
                        <input type="hidden" name="dir" value="{{ $dir }}">

                        <div class="relative w-full sm:max-w-md">
                            <svg class="absolute left-4 top-3 h-4 w-4 text-[#5F6B63]" fill="none"
                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="q" value="{{ $q }}"
                                placeholder="Cari limbah saya (e.g. Kardus, Minyak, Kaca)..."
                                class="w-full rounded-lg border border-transparent bg-[#F1F5F1] py-2.5 pl-10 pr-4 text-xs text-[#18221C] outline-none transition-all placeholder:text-[#7F8B83] focus:border-[#23533D] focus:bg-white focus:ring-2 focus:ring-[#23533D]/15">
                        </div>

                        <select name="kategori" onchange="this.form.submit()"
                            class="w-full cursor-pointer rounded-lg border border-transparent bg-[#F1F5F1] px-4 py-2.5 text-xs text-[#18221C] outline-none transition-all focus:border-[#23533D] focus:bg-white focus:ring-2 focus:ring-[#23533D]/15 sm:w-auto">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoriList->unique('nama_kategori') as $kat)
                                <option value="{{ $kat->id }}"
                                    {{ (string) $kategoriId === (string) $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori ?? 'Kategori ' . $kat->id }}
                                </option>
                            @endforeach
                        </select>

                        <div
                            class="flex items-center gap-4 font-mono text-[10px] font-medium uppercase tracking-[0.12em] text-[#7F8B83] sm:ml-auto">
                            @if ($adaFilter)
                                <a href="{{ route('laporan.index') }}#tabel-riwayat"
                                    class="text-[#23533D] hover:underline">Reset Filter</a>
                            @endif
                            Menampilkan {{ $laporan->total() }} batch aktif
                        </div>
                    </form>

                    <!-- Notifikasi Alert -->
                    @if (session('success') || session('error'))
                        <div class="space-y-2 px-4 pb-4">
                            @if (session('success'))
                                <div class="rounded-lg bg-[#E8F6EB] p-3 text-xs font-semibold text-[#093A25]">
                                    {{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="rounded-lg bg-[#FDEEEA] p-3 text-xs font-semibold text-[#9A2B12]">
                                    {{ session('error') }}</div>
                            @endif
                        </div>
                    @endif

                    <!-- The Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-left">
                            <thead>
                                <tr
                                    class="bg-[#F1F5F1] font-mono text-[10px] font-medium uppercase tracking-[0.12em] text-[#5F6B63]">
                                    <th class="whitespace-nowrap px-6 py-4">
                                        <a href="{{ $sortUrl('judul') }}"
                                            class="inline-flex items-center hover:text-[#093A25]">Material &amp; Foto
                                            {!! $sortIcon('judul') !!}</a>
                                    </th>
                                    <th class="whitespace-nowrap px-4 py-4">
                                        <a href="{{ $sortUrl('kategori') }}"
                                            class="inline-flex items-center hover:text-[#093A25]">Kategori
                                            {!! $sortIcon('kategori') !!}</a>
                                    </th>
                                    <th class="whitespace-nowrap px-4 py-4">
                                        <a href="{{ $sortUrl('volume') }}"
                                            class="inline-flex items-center hover:text-[#093A25]">Volume/Massa
                                            {!! $sortIcon('volume') !!}</a>
                                    </th>
                                    <th class="whitespace-nowrap px-4 py-4">Lokasi Pick-up</th>
                                    <th class="whitespace-nowrap px-4 py-4 text-center">
                                        <a href="{{ $sortUrl('status') }}"
                                            class="inline-flex items-center justify-center hover:text-[#093A25]">Status
                                            Ketersediaan {!! $sortIcon('status') !!}</a>
                                    </th>
                                    <th class="whitespace-nowrap px-4 py-4">
                                        <a href="{{ $sortUrl('update') }}"
                                            class="inline-flex items-center hover:text-[#093A25]">Update Terakhir
                                            {!! $sortIcon('update') !!}</a>
                                    </th>
                                    <th class="whitespace-nowrap px-4 py-4 text-center">Manajemen</th>
                                    <th class="whitespace-nowrap px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#EEF2EE] text-xs text-[#414943]">
                                @forelse ($laporan as $item)
                                    @php
                                        $st = strtolower((string) $item->status);
                                        $final = $st === 'sudah diambil';
                                        $bisaUbah = in_array($st, ['tersedia', 'sedang diproses', 'menunggu'], true);
                                        [$stClass, $stLabel] = $statusMeta($item->status);

                                        $katName = $item->kategori->nama_kategori ?? 'Umum';
                                        $katLower = strtolower($katName);
                                        $dot = str_contains($katLower, 'anorganik')
                                            ? 'bg-[#5F6B63]'
                                            : (str_contains($katLower, 'organik')
                                                ? 'bg-[#C4411E]'
                                                : 'bg-[#8A968E]');

                                        $sku =
                                            'CRD-' .
                                            ($item->created_at ?? now())->format('Y') .
                                            '-' .
                                            str_pad($item->id, 3, '0', STR_PAD_LEFT);
                                        $volumeTeks =
                                            $fmtAngka($item->estimasi_volume) . ' ' . $satuanLabel($item->satuan);
                                        $tglUpdate = $item->updated_at?->locale('id')->translatedFormat('d M Y');
                                        $jamUpdate = $item->updated_at?->format('H:i');
                                    @endphp
                                    <tr class="transition-colors hover:bg-[#FAFCFA]">
                                        <!-- Foto & Material -->
                                        <td class="px-6 py-4">
                                            <div class="flex w-max items-center gap-3">
                                                @if ($item->foto_limbah)
                                                    <img src="{{ asset('storage/' . $item->foto_limbah) }}"
                                                        alt="Foto"
                                                        class="h-12 w-12 shrink-0 rounded-lg object-cover">
                                                @else
                                                    <div
                                                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-[#E9EEE8] font-mono text-[9px] font-semibold text-[#8A968E]">
                                                        No IMG</div>
                                                @endif
                                                <div>
                                                    <p class="max-w-[200px] text-[14px] font-medium leading-snug text-[#093A25]">
                                                        {{ $item->judul }}</p>
                                                    <p class="mt-1 font-mono text-[10px] tracking-wide text-[#5F6B63]">
                                                        SKU: {{ $sku }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Kategori -->
                                        <td class="whitespace-nowrap px-4 py-4">
                                            <div
                                                class="inline-flex items-center gap-1.5 rounded-full bg-[#E9EEE8] px-2.5 py-1">
                                                <span class="h-1.5 w-1.5 rounded-full {{ $dot }}"></span>
                                                <span
                                                    class="font-mono text-[10.5px] font-medium text-[#414943]">{{ $katName }}</span>
                                            </div>
                                        </td>

                                        <!-- Volume -->
                                        <td class="whitespace-nowrap px-4 py-4">
                                            <span
                                                class="inline-flex rounded-md bg-[#E9EEE8] px-2.5 py-1.5 font-mono text-xs font-semibold tracking-wide {{ $final ? 'text-[#8A968E]' : 'text-[#093A25]' }}">
                                                {{ $volumeTeks }}
                                            </span>
                                        </td>

                                        <!-- Lokasi -->
                                        <td class="px-4 py-4">
                                            <div class="text-[13px] font-medium text-[#18221C]">Loading Dock Utama</div>
                                            <div class="mt-0.5 max-w-[150px] truncate font-mono text-[10px] text-[#5F6B63]">
                                                {{ $item->deskripsi ?: 'Akses Truk' }}</div>
                                        </td>

                                        <!-- Status -->
                                        <td class="whitespace-nowrap px-4 py-4 text-center">
                                            <span
                                                class="inline-block rounded-full px-3 py-1 font-mono text-[10.5px] font-semibold {{ $stClass }}">
                                                {{ $stLabel }}
                                            </span>
                                        </td>

                                        <!-- Tanggal -->
                                        <td class="whitespace-nowrap px-4 py-4">
                                            <div class="font-mono text-[11px] leading-relaxed text-[#414943]">
                                                {{ $tglUpdate ?? '-' }}<br>
                                                <span class="text-[#8A968E]">{{ $jamUpdate ?? '-' }}</span>
                                            </div>
                                        </td>

                                        <!-- Manajemen Btn -->
                                        <td class="whitespace-nowrap px-4 py-4 text-center">
                                            @if ($bisaUbah)
                                                <button type="button" data-open-dlg data-id="{{ $item->id }}"
                                                    data-judul="{{ $item->judul }}" data-sku="{{ $sku }}"
                                                    data-status="{{ $st }}" data-volume="{{ $volumeTeks }}"
                                                    data-lokasi="Loading Dock Utama"
                                                    data-update="{{ $tglUpdate ? $tglUpdate . ', ' . $jamUpdate : '-' }}"
                                                    data-editable="1"
                                                    class="inline-block w-[72px] whitespace-normal rounded-lg bg-[#23533D] px-2 py-2 text-center text-xs font-semibold leading-tight text-white shadow-sm transition-colors hover:bg-[#093A25]">
                                                    Ubah Status
                                                </button>
                                            @else
                                                <button type="button" data-open-dlg data-id="{{ $item->id }}"
                                                    data-judul="{{ $item->judul }}" data-sku="{{ $sku }}"
                                                    data-status="{{ $st }}" data-volume="{{ $volumeTeks }}"
                                                    data-lokasi="Loading Dock Utama"
                                                    data-update="{{ $tglUpdate ? $tglUpdate . ', ' . $jamUpdate : '-' }}"
                                                    data-editable="0"
                                                    class="inline-block w-[72px] whitespace-normal rounded-lg bg-[#E9EEE8] px-2 py-2 text-center text-xs font-semibold leading-tight text-[#414943] transition-colors hover:bg-[#DCE5DC]">
                                                    Lihat Manifest
                                                </button>
                                            @endif
                                        </td>

                                        <!-- Aksi -->
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="flex items-center justify-center gap-3 text-[#5F6B63]">
                                                @unless ($final)
                                                    <a href="{{ route('laporan.edit', $item->id) }}"
                                                        class="transition-colors hover:text-[#23533D]" title="Edit">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                            stroke-width="1.8" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('laporan.destroy', $item->id) }}" method="POST"
                                                        onsubmit="return confirm('Hapus inventaris ini secara permanen?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="block transition-colors hover:text-[#C4411E]"
                                                            title="Hapus">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                                stroke-width="1.8" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-xs text-[#C5CEC7]">-</span>
                                                @endunless
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-16 text-center">
                                            @if ($adaFilter)
                                                <p class="mb-2 text-sm font-medium text-[#5F6B63]">Tidak ada batch yang
                                                    cocok dengan pencarian Anda.</p>
                                                <a href="{{ route('laporan.index') }}#tabel-riwayat"
                                                    class="font-mono text-xs font-semibold uppercase tracking-wider text-[#23533D] hover:underline">Reset
                                                    Filter</a>
                                            @else
                                                <p class="mb-2 text-sm font-medium text-[#5F6B63]">Belum ada inventaris
                                                    limbah terdaftar.</p>
                                                <a href="{{ route('laporan.create') }}"
                                                    class="font-mono text-xs font-semibold uppercase tracking-wider text-[#23533D] hover:underline">Mulai
                                                    Tambah Limbah</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer Table (Pagination) -->
                    @if ($laporan->total() > 0)
                        <div
                            class="flex flex-col justify-between gap-4 rounded-b-xl bg-[#F1F5F1] px-6 py-4 md:flex-row md:items-center">
                            <p class="text-xs italic text-[#414943]">&ldquo;Transaksi dan pengambilan dilakukan langsung
                                dengan mitra pendaur ulang.&rdquo;</p>
                            <div class="flex items-center gap-3 font-mono text-[11px] font-medium">
                                @if ($laporan->onFirstPage())
                                    <span class="rounded-md bg-[#E9EEE8] px-3 py-1.5 text-[#A3ADA6]">Sebelumnya</span>
                                @else
                                    <a href="{{ $laporan->previousPageUrl() }}"
                                        class="rounded-md bg-[#E9EEE8] px-3 py-1.5 text-[#093A25] hover:bg-[#DCE5DC]">Sebelumnya</a>
                                @endif

                                <span class="font-semibold text-[#093A25]">Hal {{ $laporan->currentPage() }} dari
                                    {{ $laporan->lastPage() }}</span>

                                @if ($laporan->hasMorePages())
                                    <a href="{{ $laporan->nextPageUrl() }}"
                                        class="rounded-md bg-[#E9EEE8] px-3 py-1.5 text-[#093A25] hover:bg-[#DCE5DC]">Berikutnya</a>
                                @else
                                    <span class="rounded-md bg-[#E9EEE8] px-3 py-1.5 text-[#A3ADA6]">Berikutnya</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- 5. KINERJA DAMPAK & BANTUAN LOGISTIK                         -->
            <!-- ============================================================ -->
            <div class="mb-12 grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Box Kiri (Kinerja Dampak) -->
                <div class="flex flex-col justify-center rounded-xl bg-white p-8 shadow-card lg:col-span-2">
                    <h3 class="mb-4 flex items-center gap-2 text-[15px] font-semibold text-[#093A25]">
                        <svg class="h-5 w-5 text-[#23533D]" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5 19c0-8 5-13 14-14 0 9-5 14-14 14zM5 19l7-7" />
                        </svg>
                        Kinerja Dampak Sirkular {{ $namaInstansi }}
                    </h3>
                    <p class="mb-6 text-sm leading-relaxed text-[#414943]">
                        Pemberian status akurat pada inventaris limbah memastikan daur ulang tepat waktu, meminimalkan
                        dekomposisi organik anaerobik penghasil metana, dan menjamin material anorganik masuk ke fasilitas
                        daur ulang terakreditasi di Bali.
                    </p>
                    <div class="grid grid-cols-2 gap-6 md:grid-cols-3">
                        <div>
                            <div class="font-mono text-[10px] font-medium uppercase tracking-[0.1em] text-[#5F6B63]">
                                Estimasi CO₂e Terhindar</div>
                            <div class="mt-1.5 font-mono text-xl font-semibold text-[#093A25]">
                                {{ $dampak['massa'] ?? '2,410' }} KG CO₂e</div>
                        </div>
                        <div>
                            <div class="font-mono text-[10px] font-medium uppercase tracking-[0.1em] text-[#5F6B63]">
                                Tingkat Residu TPA</div>
                            <div class="mt-1.5 font-mono text-xl font-semibold text-[#2F6B4F]">&lt;
                                {{ $dampak['tingkat'] ?? '8.4' }}%</div>
                        </div>
                        <div>
                            <div class="font-mono text-[10px] font-medium uppercase tracking-[0.1em] text-[#5F6B63]">
                                Mitra Daur Ulang Terhubung</div>
                            <div class="mt-1.5 font-mono text-xl font-semibold text-[#093A25]">
                                {{ $dampak['total'] ?? '5' }} Recycler Aktif</div>
                        </div>
                    </div>
                </div>

                <!-- Box Kanan (Dukungan Logistik) -->
                <div class="flex flex-col justify-center rounded-xl bg-[#EEF3EE] p-8">
                    <h4 class="text-lg font-semibold text-[#093A25]">Perlu Dukungan Logistik Tambahan?</h4>
                    <p class="mb-6 mt-3 text-sm leading-relaxed text-[#414943]">
                        Hubungi koordinator penjemputan SIRKULA untuk jadwal khusus armada limbah skala besar (&gt;1 Ton).
                    </p>
                    <div>
                        <a href="mailto:{{ config('mail.from.address') }}"
                            class="inline-flex items-center gap-2 text-sm font-semibold text-[#093A25] hover:text-[#23533D] hover:underline">
                            Kontak Tim Dukungan &rarr;
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ================================================================ -->
    <!-- DIALOG: Ubah Status / Lihat Manifest                             -->
    <!-- ================================================================ -->
    <dialog id="dlg-status" class="w-full max-w-md rounded-2xl p-0 shadow-2xl backdrop:bg-[#093A25]/40">
        <div class="bg-white p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p id="dlg-title"
                        class="font-mono text-[10px] font-medium uppercase tracking-[0.12em] text-[#5F6B63]"></p>
                    <h3 data-f="judul" class="mt-1 text-lg font-semibold text-[#093A25]"></h3>
                </div>
                <button type="button" id="dlg-close" class="text-2xl leading-none text-[#8A968E] hover:text-[#093A25]"
                    aria-label="Tutup">&times;</button>
            </div>

            <dl class="mt-5 grid grid-cols-3 gap-y-3 text-xs">
                <dt class="font-mono text-[#5F6B63]">SKU</dt>
                <dd data-f="sku" class="col-span-2 font-mono font-semibold text-[#18221C]"></dd>
                <dt class="font-mono text-[#5F6B63]">Volume</dt>
                <dd data-f="volume"
                    class="col-span-2 inline-block w-max rounded bg-[#E9EEE8] px-2 py-0.5 font-mono font-semibold text-[#093A25]">
                </dd>
                <dt class="mt-1 font-mono text-[#5F6B63]">Lokasi</dt>
                <dd data-f="lokasi" class="col-span-2 mt-1 text-[#414943]"></dd>
                <dt class="font-mono text-[#5F6B63]">Update</dt>
                <dd data-f="update" class="col-span-2 font-mono text-[#414943]"></dd>
                <dt class="mt-1 font-mono text-[#5F6B63]">Status</dt>
                <dd data-f="status"
                    class="col-span-2 mt-1 font-mono text-[10px] font-semibold uppercase tracking-wider text-[#18221C]">
                </dd>
            </dl>

            <!-- Form ubah status -->
            <form id="dlg-form" method="POST" action="" class="mt-6 space-y-2.5">
                @csrf
                @method('PATCH')

                <button type="submit" name="status" value="tersedia" data-opt="tersedia"
                    class="group w-full rounded-xl border border-[#E1E8E1] px-4 py-3 text-left transition-colors hover:border-[#23533D] hover:bg-[#F1F8F1]">
                    <span class="block text-sm font-semibold text-[#093A25]">Tersedia</span>
                    <span class="mt-0.5 block text-xs text-[#5F6B63]">Tampil aktif di Jelajahi Limbah.</span>
                </button>
                <button type="submit" name="status" value="sedang diproses" data-opt="sedang diproses"
                    class="w-full rounded-xl border border-[#E1E8E1] px-4 py-3 text-left transition-colors hover:border-[#C4411E] hover:bg-[#FFF3EF]">
                    <span class="block text-sm font-semibold text-[#C4411E]">Sedang Diproses</span>
                    <span class="mt-0.5 block text-xs text-[#5F6B63]">Recycler sudah menghubungi Anda dan menunggu
                        pick-up.</span>
                </button>
                <button type="submit" name="status" value="sudah diambil" data-opt="sudah diambil"
                    class="w-full rounded-xl border border-[#E1E8E1] px-4 py-3 text-left transition-colors hover:border-[#5F6B63] hover:bg-[#F1F5F1]">
                    <span class="block text-sm font-semibold text-[#414943]">Sudah Diambil</span>
                    <span class="mt-0.5 block text-xs text-[#5F6B63]">Armada selesai memuat. Status ini final.</span>
                </button>
            </form>

            <p id="dlg-final-note" class="mt-6 hidden rounded-lg bg-[#E9EEE8] p-3 text-xs font-medium text-[#414943]">
                Batch ini sudah selesai diambil oleh mitra pendaur ulang, sehingga statusnya tidak dapat diubah lagi.
            </p>
        </div>
    </dialog>

    <script>
        (function() {
            const dlg = document.getElementById('dlg-status');
            const form = document.getElementById('dlg-form');
            const note = document.getElementById('dlg-final-note');
            const title = document.getElementById('dlg-title');
            const baseUrl = "{{ url('laporan') }}";
            const labelMap = {
                'tersedia': 'Tersedia',
                'sedang diproses': 'Sedang Diproses',
                'sudah diambil': 'Sudah Diambil'
            };

            document.querySelectorAll('[data-open-dlg]').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const d = btn.dataset;
                    const editable = d.editable === '1';

                    dlg.querySelector('[data-f="judul"]').textContent = d.judul;
                    dlg.querySelector('[data-f="sku"]').textContent = d.sku;
                    dlg.querySelector('[data-f="volume"]').textContent = d.volume;
                    dlg.querySelector('[data-f="lokasi"]').textContent = d.lokasi;
                    dlg.querySelector('[data-f="update"]').textContent = d.update;
                    dlg.querySelector('[data-f="status"]').textContent = labelMap[d.status] || d.status;

                    title.textContent = editable ? 'Ubah Status Ketersediaan' : 'Manifest Batch';

                    form.action = baseUrl + '/' + d.id + '/ubah-status';
                    form.classList.toggle('hidden', !editable);
                    note.classList.toggle('hidden', editable);

                    form.querySelectorAll('[data-opt]').forEach(function(opt) {
                        opt.classList.toggle('hidden', opt.dataset.opt === d.status);
                    });

                    dlg.showModal();
                });
            });

            form.addEventListener('submit', function(e) {
                if (e.submitter && e.submitter.value === 'sudah diambil') {
                    if (!confirm(
                            'Status "Sudah Diambil" bersifat final dan tidak dapat diubah lagi. Lanjutkan?')) {
                        e.preventDefault();
                    }
                }
            });

            document.getElementById('dlg-close').addEventListener('click', function() {
                dlg.close();
            });
            dlg.addEventListener('click', function(e) {
                if (e.target === dlg) dlg.close();
            });
        })();
    </script>
@endsection