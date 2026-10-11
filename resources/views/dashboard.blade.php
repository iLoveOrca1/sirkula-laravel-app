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
                return '<span class="text-gray-300 ml-1">&#8645;</span>';
            }
            return $dir === 'asc'
                ? '<span class="text-[#093A25] ml-1">&#9650;</span>'
                : '<span class="text-[#093A25] ml-1">&#9660;</span>';
        };

        $statusMeta = function (?string $raw) {
            return match (strtolower((string) $raw)) {
                'tersedia' => ['bg-[#C6ECCD] text-[#093A25]', 'Tersedia'],
                'sedang diproses' => ['bg-[#FDECE8] text-[#C4411E]', 'Sedang Diproses'],
                'sudah diambil' => ['bg-[#EBEFEA] text-[#414943]', 'Sudah Diambil'],
                'menunggu' => ['bg-[#FDECE8] text-[#C4411E]', 'Menunggu'],
                'ditolak' => ['bg-red-100 text-red-800', 'Ditolak'],
                default => ['bg-gray-100 text-gray-700', ucfirst((string) $raw)],
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

    <div class="bg-[#F6FBF5] min-h-screen font-sans pb-20 pt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- ============================================================ -->
            <!-- 1. HEADER WELCOME & TOMBOL TAMBAH                            -->
            <!-- ============================================================ -->
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-8">
                <div>
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 bg-[#EBEFEA] text-[#414943] rounded-full text-[10px] font-bold uppercase tracking-wider mb-4">
                        <span class="w-2 h-2 rounded-full bg-[#093A25]"></span>
                        Portal Mitra Terverifikasi &bull; ID: {{ $mitraId }}
                    </div>
                    <h1 class="text-3xl md:text-[34px] leading-tight font-bold text-[#093A25] tracking-tight">
                        Selamat datang kembali, {{ $namaInstansi }}
                    </h1>
                    <p class="mt-2 text-sm text-[#414943] max-w-2xl leading-relaxed">
                        Kelola inventaris limbah bernilai sirkular Anda dan perbarui status ketersediaan secara berkala demi
                        akurasi mitra pendaur ulang.
                    </p>
                </div>
                <div class="shrink-0 mt-2 md:mt-0">
                    <a href="{{ route('laporan.create') }}"
                        class="inline-flex items-center gap-2 bg-[#093A25] text-white px-5 py-3 rounded-xl text-sm font-semibold hover:bg-[#1A4B36] transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Limbah Baru
                    </a>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- 2. KARTU STATISTIK (4 GRID)                                  -->
            <!-- ============================================================ -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <!-- Total Material -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#EBEFEA] flex flex-col justify-between h-40">
                    <div class="flex items-start justify-between text-gray-500">
                        <p class="text-[11px] font-semibold uppercase tracking-wider">Total Material Terdaftar</p>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-baseline gap-2 mt-2">
                            <span
                                class="text-4xl font-extrabold text-[#093A25] font-mono">{{ str_pad($counts['semua'], 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-xs text-gray-500 font-medium">Stream Material</span>
                        </div>
                        <p class="mt-2 text-[10px] text-[#414943] flex items-center gap-1 font-mono">
                            <svg class="w-3 h-3 text-[#093A25]" fill="none" stroke="currentColor" stroke-width="3"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ $stat['kategori'] ?? 2 }} divisi operasional aktif
                        </p>
                    </div>
                </div>

                <!-- Status Tersedia -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#EBEFEA] flex flex-col justify-between h-40">
                    <div class="flex items-start justify-between">
                        <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Status Tersedia</p>
                        <span
                            class="text-[10px] font-bold bg-[#C6ECCD] text-[#093A25] px-2.5 py-0.5 rounded-full uppercase tracking-wider">Tersedia</span>
                    </div>
                    <div>
                        <div class="flex items-baseline gap-2 mt-2">
                            <span
                                class="text-4xl font-extrabold text-[#093A25] font-mono">{{ str_pad($counts['tersedia'], 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-xs text-gray-500 font-medium">Siap Diambil</span>
                        </div>
                        <p class="mt-2 text-[10px] text-gray-400 flex items-center gap-1 font-mono">
                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Tampil aktif di Jelajahi Limbah
                        </p>
                    </div>
                </div>

                <!-- Sedang Diproses -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#EBEFEA] flex flex-col justify-between h-40">
                    <div class="flex items-start justify-between">
                        <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Sedang Diproses</p>
                        <span
                            class="text-[10px] font-bold bg-[#FDECE8] text-[#C4411E] px-2.5 py-0.5 rounded-full uppercase tracking-wider">Pick-up</span>
                    </div>
                    <div>
                        <div class="flex items-baseline gap-2 mt-2">
                            <span
                                class="text-4xl font-extrabold text-[#C4411E] font-mono">{{ str_pad($counts['diproses'], 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-xs text-gray-500 font-medium">Menunggu Pick-up</span>
                        </div>
                        <p class="mt-2 text-[10px] text-[#C4411E] flex items-center gap-1 font-mono">
                            <svg class="w-3 h-3 text-[#C4411E]" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Kontak recycler terverifikasi
                        </p>
                    </div>
                </div>

                <!-- Sirkulasi Sukses -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#EBEFEA] flex flex-col justify-between h-40">
                    <div class="flex items-start justify-between">
                        <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Sirkulasi Sukses</p>
                        <span
                            class="text-[10px] font-bold bg-[#EBEFEA] text-[#414943] px-2.5 py-0.5 rounded-full uppercase tracking-wider">Selesai</span>
                    </div>
                    <div>
                        <div class="flex items-baseline gap-2 mt-2">
                            <span
                                class="text-4xl font-extrabold text-[#093A25] font-mono">{{ str_pad($counts['diambil'] ?? 2, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-xs text-gray-500 font-medium">Batch Bulan Ini</span>
                        </div>
                        <p class="mt-2 text-[10px] text-[#414943] flex items-center gap-1 font-mono">
                            <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
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
            <div class="bg-[#F0F5F0] border border-[#EBEFEA] rounded-2xl p-5 mb-12 flex items-start gap-4 shadow-sm">
                <div class="bg-white p-2.5 rounded-xl shadow-sm text-[#093A25] shrink-0 mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-[#093A25] flex items-center gap-2">
                        Protokol Pembaruan Status Real-Time
                        <span
                            class="bg-[#EBEFEA] text-gray-600 text-[9px] font-mono px-2 py-0.5 rounded uppercase tracking-wider font-bold">SOP
                            Sirkula</span>
                    </h4>
                    <p class="text-xs text-[#414943] mt-1.5 leading-relaxed pr-4">
                        Selalu perbarui status limbah menjadi 'Sedang Diproses' saat recycler telah menghubungi Anda via
                        WhatsApp atau instrumen logistik. Ganti ke 'Sudah Diambil' seketika armada mitra menyelesaikan
                        pemuatan di dok muat agar data telemetri sirkular tetap presisi.
                    </p>
                </div>
            </div>

            <!-- Card Menu: Permintaan Material -->
            <a href="{{ route('permintaan.dashboard') }}"
                class="group flex flex-col bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-[#EBEFEA] transition-all hover:-translate-y-1 hover:shadow-md">
                <div
                    class="w-14 h-14 rounded-2xl bg-[#F0F5F0] text-[#093A25] flex items-center justify-center mb-6 transition-colors group-hover:bg-[#093A25] group-hover:text-white border border-[#EBEFEA]">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#093A25] mb-2">Permintaan Material</h3>
                <p class="text-sm text-[#414943] leading-relaxed mb-6 flex-1">
                    Lihat daftar kebutuhan material dari recycler dan industri. Hubungi mereka jika Anda memiliki pasokan
                    yang sesuai.
                </p>
                <div class="mt-auto flex items-center text-sm font-bold text-[#1A4B36] group-hover:text-[#093A25]">
                    Lihat Daftar Permintaan <svg class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </div>
            </a>

            <!-- ============================================================ -->
            <!-- 4. LIMBAH SAYA (TABEL, SEARCH, FILTER)                       -->
            <!-- ============================================================ -->
            <div id="tabel-riwayat" class="scroll-mt-10">

                <!-- Title & Tabs -->
                <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-5">
                    <div>
                        <h2 class="text-2xl font-bold text-[#093A25]">Limbah Saya</h2>
                        <p class="text-xs text-gray-500 mt-1">Inventaris aliran material terdaftar di area
                            {{ $namaInstansi }}</p>
                    </div>

                    <!-- Tab Filter Status -->
                    <div class="inline-flex bg-[#EBEFEA] rounded-xl p-1 gap-1">
                        @foreach ($tabs as $key => [$label, $jumlah])
                            @php $aktif = ($statusKey ?? '') === (string) $key; @endphp
                            <a href="{{ $url(['status' => $key === '' ? null : $key]) }}"
                                class="px-4 py-2 rounded-lg text-xs font-semibold transition-all duration-200 flex items-center gap-1.5
                                      {{ $aktif ? 'bg-white text-[#093A25] shadow-sm' : 'text-gray-600 hover:text-black' }}">
                                {{ $label }}
                                <span class="text-[10px] font-mono text-gray-400">({{ $jumlah }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Search Bar & Table Container -->
                <div class="bg-white rounded-2xl border border-[#EBEFEA] shadow-sm mb-12">

                    <!-- Search Header -->
                    <form method="GET" action="{{ route('laporan.index') }}"
                        class="p-4 border-b border-[#EBEFEA] flex flex-col sm:flex-row items-center gap-3">
                        <input type="hidden" name="status" value="{{ $statusKey }}">
                        <input type="hidden" name="sort" value="{{ $sort }}">
                        <input type="hidden" name="dir" value="{{ $dir }}">

                        <div class="relative w-full sm:max-w-md">
                            <svg class="absolute left-4 top-3 w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="q" value="{{ $q }}"
                                placeholder="Cari limbah saya (e.g. Kardus, Minyak, Kaca)..."
                                class="w-full pl-10 pr-4 py-2.5 text-xs bg-[#F6FBF5] border border-transparent rounded-xl focus:bg-white focus:ring-1 focus:ring-[#093A25] focus:border-[#093A25] outline-none transition-all">
                        </div>

                        <!-- Dropdown Filter Kategori (Diperbaiki) -->
                        <select name="kategori" onchange="this.form.submit()"
                            class="w-full sm:w-auto px-4 py-2.5 text-xs bg-[#F6FBF5] border border-transparent rounded-xl focus:bg-white focus:ring-1 focus:ring-[#093A25] focus:border-[#093A25] outline-none transition-all cursor-pointer">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoriList->unique('nama_kategori') as $kat)
                                <option value="{{ $kat->id }}"
                                    {{ (string) $kategoriId === (string) $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori ?? 'Kategori ' . $kat->id }}
                                </option>
                            @endforeach
                        </select>

                        <div
                            class="sm:ml-auto text-[10px] font-mono text-gray-400 uppercase tracking-widest font-semibold flex items-center gap-4">
                            @if ($adaFilter)
                                <a href="{{ route('laporan.index') }}#tabel-riwayat"
                                    class="hover:text-[#093A25] hover:underline">Reset Filter</a>
                            @endif
                            MENAMPILKAN {{ $laporan->total() }} BATCH AKTIF
                        </div>
                    </form>

                    <!-- Notifikasi Alert -->
                    @if (session('success') || session('error'))
                        <div class="p-4 border-b border-[#EBEFEA]">
                            @if (session('success'))
                                <div class="bg-green-50 text-[#093A25] p-3 rounded-lg text-xs font-semibold">
                                    {{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="bg-red-50 text-red-700 p-3 rounded-lg text-xs font-semibold">
                                    {{ session('error') }}</div>
                            @endif
                        </div>
                    @endif

                    <!-- The Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="bg-gray-50/50 text-[10px] font-bold text-gray-500 uppercase tracking-widest border-b border-[#EBEFEA]">
                                    <th class="py-4 px-6 whitespace-nowrap">
                                        <a href="{{ $sortUrl('judul') }}"
                                            class="hover:text-black inline-flex items-center">MATERIAL &amp; FOTO
                                            {!! $sortIcon('judul') !!}</a>
                                    </th>
                                    <th class="py-4 px-4 whitespace-nowrap">
                                        <a href="{{ $sortUrl('kategori') }}"
                                            class="hover:text-black inline-flex items-center">KATEGORI
                                            {!! $sortIcon('kategori') !!}</a>
                                    </th>
                                    <th class="py-4 px-4 whitespace-nowrap">
                                        <a href="{{ $sortUrl('volume') }}"
                                            class="hover:text-black inline-flex items-center">VOLUME/MASSA
                                            {!! $sortIcon('volume') !!}</a>
                                    </th>
                                    <th class="py-4 px-4 whitespace-nowrap">LOKASI PICK-UP</th>
                                    <th class="py-4 px-4 whitespace-nowrap text-center">
                                        <a href="{{ $sortUrl('status') }}"
                                            class="hover:text-black inline-flex items-center justify-center">STATUS
                                            KETERSEDIAAN {!! $sortIcon('status') !!}</a>
                                    </th>
                                    <th class="py-4 px-4 whitespace-nowrap">
                                        <a href="{{ $sortUrl('update') }}"
                                            class="hover:text-black inline-flex items-center">UPDATE TERAKHIR
                                            {!! $sortIcon('update') !!}</a>
                                    </th>
                                    <th class="py-4 px-4 whitespace-nowrap text-center">MANAJEMEN</th>
                                    <th class="py-4 px-6 whitespace-nowrap text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs text-[#414943] divide-y divide-[#EBEFEA]">
                                @forelse ($laporan as $item)
                                    @php
                                        $st = strtolower((string) $item->status);
                                        $final = $st === 'sudah diambil';
                                        $bisaUbah = in_array($st, ['tersedia', 'sedang diproses', 'menunggu'], true);
                                        [$stClass, $stLabel] = $statusMeta($item->status);

                                        $katName = $item->kategori->nama_kategori ?? 'Umum';
                                        $katLower = strtolower($katName);
                                        $dot = str_contains($katLower, 'anorganik')
                                            ? 'bg-gray-500'
                                            : (str_contains($katLower, 'organik')
                                                ? 'bg-[#C4411E]'
                                                : 'bg-gray-400');

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
                                    <tr class="hover:bg-gray-50/60 transition-colors">
                                        <!-- Foto & Material -->
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-3 w-max">
                                                @if ($item->foto_limbah)
                                                    <img src="{{ asset('storage/' . $item->foto_limbah) }}"
                                                        alt="Foto"
                                                        class="h-12 w-12 object-cover rounded-xl border border-gray-200 shrink-0 shadow-sm">
                                                @else
                                                    <div
                                                        class="h-12 w-12 bg-gray-100 rounded-xl border border-gray-200 flex items-center justify-center text-[9px] font-semibold text-gray-400 shrink-0">
                                                        No IMG</div>
                                                @endif
                                                <div>
                                                    <p class="font-bold text-[#093A25] text-[13px]">{{ $item->judul }}
                                                    </p>
                                                    <p class="mt-0.5 text-[10px] font-mono text-gray-500 tracking-wider">
                                                        SKU: {{ $sku }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Kategori -->
                                        <td class="py-4 px-4 whitespace-nowrap">
                                            <div
                                                class="inline-flex items-center gap-1.5 bg-[#F0F5F0] px-2.5 py-1 rounded-full border border-gray-100">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
                                                <span
                                                    class="text-[11px] font-mono font-semibold text-gray-600">{{ $katName }}</span>
                                            </div>
                                        </td>

                                        <!-- Volume -->
                                        <td class="py-4 px-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex bg-[#EBEFEA] text-[#093A25] font-mono font-bold text-xs px-2.5 py-1.5 rounded-lg tracking-wide">
                                                {{ $volumeTeks }}
                                            </span>
                                        </td>

                                        <!-- Lokasi -->
                                        <td class="py-4 px-4">
                                            <div class="font-semibold text-gray-800 text-[13px]">Loading Dock Utama</div>
                                            <div class="text-[10px] font-mono text-gray-500 mt-0.5 truncate max-w-[150px]">
                                                {{ $item->deskripsi ?: 'Akses Truk' }}</div>
                                        </td>

                                        <!-- Status -->
                                        <td class="py-4 px-4 whitespace-nowrap text-center">
                                            <span
                                                class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full {{ $stClass }}">
                                                {{ $stLabel }}
                                            </span>
                                        </td>

                                        <!-- Tanggal -->
                                        <td class="py-4 px-4 whitespace-nowrap">
                                            <div class="text-[11px] font-mono text-gray-600">
                                                {{ $tglUpdate ?? '-' }}<br>
                                                <span class="text-gray-400">{{ $jamUpdate ?? '-' }}</span>
                                            </div>
                                        </td>

                                        <!-- Manajemen Btn -->
                                        <td class="py-4 px-4 whitespace-nowrap text-center">
                                            @if ($bisaUbah)
                                                <button type="button" data-open-dlg data-id="{{ $item->id }}"
                                                    data-judul="{{ $item->judul }}" data-sku="{{ $sku }}"
                                                    data-status="{{ $st }}" data-volume="{{ $volumeTeks }}"
                                                    data-lokasi="Loading Dock Utama"
                                                    data-update="{{ $tglUpdate ? $tglUpdate . ', ' . $jamUpdate : '-' }}"
                                                    data-editable="1"
                                                    class="bg-[#093A25] text-white text-xs font-semibold px-4 py-2 rounded-xl hover:bg-[#143B25] transition-colors shadow-sm">
                                                    Ubah Status
                                                </button>
                                            @else
                                                <button type="button" data-open-dlg data-id="{{ $item->id }}"
                                                    data-judul="{{ $item->judul }}" data-sku="{{ $sku }}"
                                                    data-status="{{ $st }}" data-volume="{{ $volumeTeks }}"
                                                    data-lokasi="Loading Dock Utama"
                                                    data-update="{{ $tglUpdate ? $tglUpdate . ', ' . $jamUpdate : '-' }}"
                                                    data-editable="0"
                                                    class="bg-[#EBEFEA] text-[#414943] text-xs font-semibold px-4 py-2 rounded-xl hover:bg-gray-200 transition-colors">
                                                    Lihat Manifest
                                                </button>
                                            @endif
                                        </td>

                                        <!-- Aksi -->
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <div class="flex items-center justify-center gap-3 text-gray-400">
                                                @unless ($final)
                                                    <a href="{{ route('laporan.edit', $item->id) }}"
                                                        class="hover:text-blue-600 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            stroke-width="1.8" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('laporan.destroy', $item->id) }}" method="POST"
                                                        onsubmit="return confirm('Hapus inventaris ini secara permanen?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="hover:text-red-600 transition-colors block">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                stroke-width="1.8" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-xs text-gray-300">-</span>
                                                @endunless
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-16 text-center">
                                            @if ($adaFilter)
                                                <p class="text-gray-500 text-sm font-medium mb-2">Tidak ada batch yang
                                                    cocok dengan pencarian Anda.</p>
                                                <a href="{{ route('laporan.index') }}#tabel-riwayat"
                                                    class="text-[#093A25] text-xs font-bold uppercase tracking-wider hover:underline">Reset
                                                    Filter</a>
                                            @else
                                                <p class="text-gray-500 text-sm font-medium mb-2">Belum ada inventaris
                                                    limbah terdaftar.</p>
                                                <a href="{{ route('laporan.create') }}"
                                                    class="text-[#093A25] text-xs font-bold uppercase tracking-wider hover:underline">Mulai
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
                            class="px-6 py-4 flex flex-col md:flex-row md:items-center justify-between gap-4 border-t border-[#EBEFEA] bg-white rounded-b-2xl">
                            <p class="text-xs text-gray-500 italic">&ldquo;Transaksi dan pengambilan dilakukan langsung
                                dengan mitra pendaur ulang.&rdquo;</p>
                            <div class="flex items-center gap-3 text-xs font-mono font-semibold">
                                @if ($laporan->onFirstPage())
                                    <span class="text-gray-400 uppercase tracking-widest">Sebelumnya</span>
                                @else
                                    <a href="{{ $laporan->previousPageUrl() }}"
                                        class="text-[#093A25] hover:underline uppercase tracking-widest">Sebelumnya</a>
                                @endif

                                <span
                                    class="bg-[#F0F5F0] px-3 py-1.5 rounded-md text-gray-700 tracking-widest uppercase">Hal
                                    {{ $laporan->currentPage() }} dari {{ $laporan->lastPage() }}</span>

                                @if ($laporan->hasMorePages())
                                    <a href="{{ $laporan->nextPageUrl() }}"
                                        class="text-[#093A25] hover:underline uppercase tracking-widest">Berikutnya</a>
                                @else
                                    <span class="text-gray-400 uppercase tracking-widest">Berikutnya</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- 5. KINERJA DAMPAK & BANTUAN LOGISTIK                         -->
            <!-- ============================================================ -->
            <div class="mb-12 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Box Kiri (Kinerja Dampak) -->
                <div
                    class="lg:col-span-2 bg-white rounded-3xl p-8 border border-[#EBEFEA] shadow-sm flex flex-col justify-center">
                    <h3 class="flex items-center gap-2 font-bold text-[#093A25] mb-4">
                        <svg class="w-5 h-5 text-[#093A25]" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Kinerja Dampak Sirkular {{ $namaInstansi }}
                    </h3>
                    <p class="text-sm text-[#414943] leading-relaxed mb-6">
                        Pemberian status akurat pada inventaris limbah memastikan daur ulang tepat waktu, meminimalkan
                        dekomposisi organik anaerobik penghasil metana, dan menjamin material anorganik masuk ke fasilitas
                        daur ulang terakreditasi di Bali.
                    </p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                        <div>
                            <div class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Estimasi CO₂e
                                Terhindar</div>
                            <div class="text-xl font-extrabold text-[#093A25] font-mono mt-1">
                                {{ $dampak['massa'] ?? '2,410' }} KG CO₂e</div>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Tingkat Residu TPA
                            </div>
                            <div class="text-xl font-extrabold text-[#414943] font-mono mt-1">&lt;
                                {{ $dampak['tingkat'] ?? '8.4' }}%</div>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Mitra Daur Ulang
                                Terhubung</div>
                            <div class="text-xl font-extrabold text-[#093A25] font-mono mt-1">
                                {{ $dampak['total'] ?? '5' }} Recycler Aktif</div>
                        </div>
                    </div>
                </div>

                <!-- Box Kanan (Dukungan Logistik) -->
                <div class="bg-[#F0F5F0] rounded-3xl p-8 flex flex-col justify-center shadow-sm">
                    <h4 class="font-bold text-[#093A25] text-lg">Perlu Dukungan Logistik Tambahan?</h4>
                    <p class="text-sm text-[#414943] mt-3 leading-relaxed mb-6">
                        Hubungi koordinator penjemputan SIRKULA untuk jadwal khusus armada limbah skala besar (&gt;1 Ton).
                    </p>
                    <div>
                        <a href="mailto:{{ config('mail.from.address') }}"
                            class="inline-flex items-center gap-2 text-sm font-bold text-[#093A25] hover:text-[#1A4B36] hover:underline">
                            Kontak Tim Dukungan &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- FOOTER SIMPLE AREA                                           -->
            <!-- ============================================================ -->
            {{-- <div class="pt-8 border-t border-[#EBEFEA] pb-10 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <!-- Penggunaan Logo Asset Baru -->
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
                        <li><a href="#" class="hover:text-[#093A25] hover:underline">Jelajahi Limbah</a></li>
                        <li><a href="#" class="hover:text-[#093A25] hover:underline">Tentang SIRKULA</a></li>
                        <li><a href="#" class="hover:text-[#093A25] hover:underline">Untuk Mitra</a></li>
                        <li><a href="#" class="hover:text-[#093A25] hover:underline">Bantuan &amp; Panduan</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-[#093A25] text-sm mb-4">Pemberitahuan Sistem</h4>
                    <div class="bg-[#EBEFEA] p-4 rounded-xl">
                        <p class="text-[10px] font-mono font-bold text-gray-500 uppercase tracking-wider mb-1">Protokol Transaksi</p>
                        <p class="text-xs text-[#414943] italic leading-relaxed">
                            "Transaksi dan kesepakatan pengambilan dilakukan langsung antara mitra dan recycler."
                        </p>
                    </div>
                </div>
            </div> --}}

        </div>
    </div>

    <!-- ================================================================ -->
    <!-- DIALOG: Ubah Status / Lihat Manifest                             -->
    <!-- ================================================================ -->
    <dialog id="dlg-status" class="rounded-2xl p-0 w-full max-w-md shadow-2xl backdrop:bg-black/40">
        <div class="p-6 bg-white">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p id="dlg-title" class="text-[10px] font-mono uppercase tracking-wider text-gray-500 font-bold"></p>
                    <h3 data-f="judul" class="mt-1 text-lg font-bold text-[#093A25]"></h3>
                </div>
                <button type="button" id="dlg-close" class="text-gray-400 hover:text-gray-700 text-2xl leading-none"
                    aria-label="Tutup">&times;</button>
            </div>

            <dl class="mt-5 grid grid-cols-3 gap-y-3 text-xs">
                <dt class="text-gray-500 font-mono">SKU</dt>
                <dd data-f="sku" class="col-span-2 font-mono font-semibold text-gray-900"></dd>
                <dt class="text-gray-500 font-mono">Volume</dt>
                <dd data-f="volume"
                    class="col-span-2 font-mono font-semibold text-[#093A25] bg-[#EBEFEA] inline-block px-2 py-0.5 rounded w-max">
                </dd>
                <dt class="text-gray-500 font-mono mt-1">Lokasi</dt>
                <dd data-f="lokasi" class="col-span-2 text-[#414943] mt-1"></dd>
                <dt class="text-gray-500 font-mono">Update</dt>
                <dd data-f="update" class="col-span-2 font-mono text-gray-600"></dd>
                <dt class="text-gray-500 font-mono mt-1">Status</dt>
                <dd data-f="status" class="col-span-2 font-bold text-gray-900 mt-1 uppercase tracking-wider text-[10px]">
                </dd>
            </dl>

            <!-- Form ubah status -->
            <form id="dlg-form" method="POST" action="" class="mt-6 space-y-2.5">
                @csrf
                @method('PATCH')

                <button type="submit" name="status" value="tersedia" data-opt="tersedia"
                    class="w-full text-left px-4 py-3 rounded-xl border border-gray-200 hover:border-green-600 hover:bg-[#F6FBF5] transition-colors group">
                    <span class="block text-sm font-bold text-[#093A25]">Tersedia</span>
                    <span class="block text-xs text-gray-500 mt-0.5">Tampil aktif di Jelajahi Limbah.</span>
                </button>
                <button type="submit" name="status" value="sedang diproses" data-opt="sedang diproses"
                    class="w-full text-left px-4 py-3 rounded-xl border border-gray-200 hover:border-orange-400 hover:bg-[#FDECE8] transition-colors">
                    <span class="block text-sm font-bold text-[#C4411E]">Sedang Diproses</span>
                    <span class="block text-xs text-gray-500 mt-0.5">Recycler sudah menghubungi Anda dan menunggu
                        pick-up.</span>
                </button>
                <button type="submit" name="status" value="sudah diambil" data-opt="sudah diambil"
                    class="w-full text-left px-4 py-3 rounded-xl border border-gray-200 hover:border-gray-500 hover:bg-gray-50 transition-colors">
                    <span class="block text-sm font-bold text-[#414943]">Sudah Diambil</span>
                    <span class="block text-xs text-gray-500 mt-0.5">Armada selesai memuat. Status ini final.</span>
                </button>
            </form>

            <p id="dlg-final-note" class="hidden mt-6 text-xs text-[#414943] bg-[#EBEFEA] rounded-lg p-3 font-medium">
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
