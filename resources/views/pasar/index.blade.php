@extends('layouts.app')



@section('content')

    @php

        // ------------------------------------------------------------------

        // Helper tampilan

        // ------------------------------------------------------------------

        $statusLabel = [
            'tersedia' => 'Tersedia',

            'diproses' => 'Sedang Diproses',

            'diambil' => 'Sudah Diambil',
        ];

        $totalBatch = $totalBatch ?? $limbahTersedia->total();

        $totalMitra = $totalMitra ?? null;

        $wilayahList = $wilayahList ?? collect();

        $sortOpsi = [
            'terbaru' => 'Terbaru',

            'terlama' => 'Terlama',

            'volume_besar' => 'Volume Terbesar',
        ];

        $volumeOpsi = [50, 100, 500, 1000];

        // URL halaman ini tanpa parameter tertentu (dipakai tombol × pada chip filter)

        $hapus = fn(array $keys) => route('pasar.index', request()->except(array_merge($keys, ['page'])));

        // Chip "Filter Aktif"

        $chips = [];

        if (request()->filled('search')) {
            $chips[] = ['Pencarian: ' . request('search'), 'search'];
        }

        if (request()->filled('kategori')) {
            $chips[] = ['Kategori: ' . ucfirst(request('kategori')), 'kategori'];
        }

        if (request()->filled('wilayah')) {
            $chips[] = ['Wilayah: ' . request('wilayah'), 'wilayah'];
        }

        if (request()->filled('status')) {
            $chips[] = ['Status: ' . ($statusLabel[request('status')] ?? ucfirst(request('status'))), 'status'];
        }

        if (request()->filled('volume')) {
            $chips[] = ['Volume: min. ' . request('volume') . ' KG/Liter', 'volume'];
        }

        $adaFilter = request()->anyFilled(['search', 'kategori', 'wilayah', 'status', 'volume', 'sort']);

        // Nomor halaman untuk paginasi kustom: 1 2 3 … 22

        $cur = $limbahTersedia->currentPage();

        $last = $limbahTersedia->lastPage();

        $nomor = collect([1, $last, $cur - 1, $cur, $cur + 1]);

        if ($cur <= 2) {
            $nomor->push(3);
        }

        if ($cur >= $last - 1) {
            $nomor->push($last - 2);
        }

        $nomor = $nomor->filter(fn($n) => $n >= 1 && $n <= $last)->unique()->sort()->values();

        $selectClass =
            'w-full bg-[#F0F5F0] border-none rounded-xl py-2.5 pl-4 pr-9 text-sm text-[#414943] focus:ring-2 focus:ring-[#093A25] outline-none cursor-pointer';

        $labelClass = 'block text-[10px] font-mono font-semibold uppercase tracking-wider text-gray-500 mb-2';

    @endphp



    <style>
        @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        .sirkula-market {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #202923;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
        }

        .sirkula-market .font-mono {
            font-family: 'IBM Plex Mono', ui-monospace, SFMono-Regular, Menlo, monospace;
            letter-spacing: .015em;
        }

        .sirkula-market input,
        .sirkula-market select,
        .sirkula-market button {
            font: inherit;
        }

        .sirkula-market input,
        .sirkula-market select {
            font-size: 12px;
        }

        .sirkula-market h1,
        .sirkula-market h2,
        .sirkula-market h3 {
            letter-spacing: -0.035em;
        }

        @media (max-width: 640px) {
            .sirkula-market h1 {
                line-height: 1.2;
            }
        }
    </style>

    <div class="sirkula-market bg-white font-sans text-[#202923]">



        <!-- ========================================== -->

        <!-- HEADER BAND                                 -->

        <!-- ========================================== -->

        <section class="bg-[#F0F5F0] pt-8 pb-10">

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">



                <!-- Breadcrumb -->

                <nav class="flex items-center gap-1.5 text-xs text-gray-600 mb-3" aria-label="Breadcrumb">

                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 11l9-8 9 8M5 10v10h5v-6h4v6h5V10" />

                    </svg>

                    <a href="{{ route('home') }}" class="hover:text-[#093A25] hover:underline">Beranda</a>

                    <span class="text-gray-400">/</span>

                    <span class="font-semibold text-[#093A25]">Jelajahi Limbah</span>

                </nav>



                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">

                    <div>

                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 bg-[#C6ECCD]/60 text-[#093A25] rounded-full text-[10px] font-mono font-bold uppercase tracking-wider mb-3">

                            <span class="w-1.5 h-1.5 rounded-full bg-[#093A25]"></span>

                            Katalog Sirkulasi Material Aktif

                        </div>

                        <h1 class="text-3xl md:text-3xl font-bold text-[#093A25] tracking-tight mb-2">Jelajahi

                            Ketersediaan Limbah</h1>

                        <p class="text-sm text-[#414943] max-w-xl leading-relaxed">

                            Temukan material organik dan anorganik dari mitra terverifikasi untuk disirkulasikan kembali

                            secara transparan dan efisien.

                        </p>

                    </div>



                    <div class="shrink-0 bg-white rounded-2xl px-5 py-4 flex items-center gap-4 shadow-sm">

                        <div class="bg-[#F0F5F0] p-3 rounded-xl text-[#093A25]">

                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">

                                <rect x="4" y="4" width="16" height="16" rx="2" />

                                <path d="M4 12h16M12 4v16" />

                            </svg>

                        </div>

                        <div>

                            <p class="text-[10px] font-mono font-bold text-gray-500 uppercase tracking-widest">Frekuensi

                                Terkini</p>

                            <p class="text-base font-mono font-bold text-[#093A25]">

                                {{ number_format($totalBatch, 0, ',', '.') }} Batch Terdaftar</p>

                        </div>

                    </div>

                </div>

            </div>

        </section>



        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-2">



            <!-- ========================================== -->

            <!-- SEARCH & FILTER                             -->

            <!-- ========================================== -->

            <form action="{{ route('pasar.index') }}" method="GET"
                class="bg-white rounded-3xl p-5 md:p-6 shadow-md border border-[#EBEFEA]">



                <!-- Pencarian & tombol -->

                <div class="flex flex-col md:flex-row items-stretch gap-3 mb-5">

                    <div class="relative flex-1">

                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">

                            <circle cx="11" cy="11" r="7" />

                            <path stroke-linecap="round" d="M20 20l-3.5-3.5" />

                        </svg>

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari berdasarkan nama limbah atau penyedia..."
                            class="w-full bg-[#F0F5F0] border-none rounded-xl py-3.5 pl-12 pr-4 text-sm text-[#414943] focus:ring-2 focus:ring-[#093A25] outline-none">

                    </div>



                    <div class="flex items-center gap-3">

                        <button type="submit"
                            class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-[#1A4B36] hover:bg-[#093A25] text-white px-7 py-3.5 rounded-xl font-bold text-sm shadow-sm transition-colors">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">

                                <path stroke-linecap="round" d="M4 7h10M18 7h2M4 17h2M10 17h10" />

                                <circle cx="16" cy="7" r="2" />

                                <circle cx="8" cy="17" r="2" />

                            </svg>

                            Terapkan

                        </button>



                        @if ($adaFilter)
                            <a href="{{ route('pasar.index') }}"
                                class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-[#EBEFEA] hover:bg-gray-200 text-[#414943] px-6 py-3.5 rounded-xl font-bold text-sm transition-colors">

                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />

                                </svg>

                                Reset Filter

                            </a>
                        @endif

                    </div>

                </div>



                <!-- Dropdown filter -->

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">



                    <!-- Kategori (segmented) -->

                    <div>

                        <label class="{{ $labelClass }}">Kategori</label>

                        <div class="flex rounded-xl bg-[#F0F5F0] p-1">

                            @foreach (['' => 'Semua', 'organik' => 'Organik', 'anorganik' => 'Anorganik'] as $val => $lbl)
                                <label class="flex-1 text-center cursor-pointer">

                                    <input type="radio" name="kategori" value="{{ $val }}" class="peer sr-only"
                                        {{ (string) request('kategori') === (string) $val ? 'checked' : '' }}
                                        onchange="this.form.submit()">

                                    <span
                                        class="block text-xs py-2 rounded-lg text-gray-500 font-medium transition-all

                                                 peer-checked:bg-white peer-checked:text-[#093A25] peer-checked:font-bold peer-checked:shadow-sm">{{ $lbl }}</span>

                                </label>
                            @endforeach

                        </div>

                    </div>



                    <!-- Wilayah -->

                    <div>

                        <label class="{{ $labelClass }}">Wilayah</label>

                        <select name="wilayah" class="{{ $selectClass }}" onchange="this.form.submit()">

                            <option value="">Semua Lokasi</option>

                            @foreach ($wilayahList as $w)
                                <option value="{{ $w }}" {{ request('wilayah') === $w ? 'selected' : '' }}>

                                    {{ $w }}</option>
                            @endforeach

                        </select>

                    </div>



                    <!-- Status -->

                    <div>

                        <label class="{{ $labelClass }}">Status Material</label>

                        <select name="status" class="{{ $selectClass }}" onchange="this.form.submit()">

                            <option value="">Semua Status</option>

                            @foreach ($statusLabel as $val => $lbl)
                                <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>

                                    {{ $lbl }}</option>
                            @endforeach

                        </select>

                    </div>



                    <!-- Volume -->

                    <div>

                        <label class="{{ $labelClass }}">Volume Minimum</label>

                        <select name="volume" class="{{ $selectClass }}" onchange="this.form.submit()">

                            <option value="">Semua Volume</option>

                            @foreach ($volumeOpsi as $v)
                                <option value="{{ $v }}"
                                    {{ (string) request('volume') === (string) $v ? 'selected' : '' }}>

                                    Min. {{ number_format($v, 0, ',', '.') }} KG/Liter

                                </option>
                            @endforeach

                        </select>

                    </div>



                    <!-- Urutkan -->

                    <div>

                        <label class="{{ $labelClass }}">Urutkan</label>

                        <select name="sort" class="{{ $selectClass }}" onchange="this.form.submit()">

                            @foreach ($sortOpsi as $val => $lbl)
                                <option value="{{ $val }}"
                                    {{ request('sort', 'terbaru') === $val ? 'selected' : '' }}>{{ $lbl }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                </div>



                <!-- Filter aktif -->

                @if (count($chips))
                    <div class="mt-5 flex flex-wrap items-center gap-2">

                        <span class="text-[11px] font-mono font-bold text-gray-500 mr-1">Filter Aktif:</span>

                        @foreach ($chips as [$teks, $kunci])
                            <span
                                class="inline-flex items-center gap-1.5 bg-[#E4F0E6] text-[#093A25] pl-3 pr-2 py-1 rounded-lg text-xs font-medium">

                                {{ $teks }}

                                <a href="{{ $hapus([$kunci]) }}"
                                    class="text-gray-500 hover:text-red-600 text-base leading-none"
                                    title="Hapus filter">&times;</a>

                            </span>
                        @endforeach

                    </div>
                @endif

            </form>



            <!-- ========================================== -->

            <!-- INDIKATOR HASIL                             -->

            <!-- ========================================== -->

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mt-6 mb-6 text-sm text-gray-600">

                <div class="flex items-center gap-2">

                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M6 12h12M10 18h4" />

                    </svg>

                    <span>

                        Menampilkan <strong
                            class="text-[#093A25]">{{ number_format($limbahTersedia->total(), 0, ',', '.') }}</strong>

                        limbah aktif

                        @if ($totalMitra)
                            dari <strong class="text-[#093A25]">{{ number_format($totalMitra, 0, ',', '.') }}</strong>

                            mitra
                        @endif

                        di seluruh wilayah

                    </span>

                </div>

                <div class="text-[10px] font-mono font-bold uppercase tracking-widest text-gray-500">Mode Tampilan: Grid

                </div>

            </div>



            <!-- ========================================== -->

            <!-- GRID KATALOG                                -->

            <!-- ========================================== -->

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

                @forelse ($limbahTersedia as $item)
                    @include('pasar.partials.card', ['item' => $item])

                @empty

                    <div class="col-span-full bg-[#F6FBF5] py-16 text-center rounded-3xl border border-[#EBEFEA]">

                        <div class="text-5xl mb-4 opacity-50">🍃</div>

                        <h3 class="text-lg font-bold text-[#093A25]">Limbah tidak ditemukan</h3>

                        <p class="text-sm text-gray-500 mt-2">Coba sesuaikan kata kunci atau filter pencarian Anda.</p>

                        @if ($adaFilter)
                            <a href="{{ route('pasar.index') }}"
                                class="inline-block mt-4 text-sm font-semibold text-[#093A25] hover:underline">Reset

                                filter</a>
                        @endif

                    </div>
                @endforelse

            </div>



            <!-- ========================================== -->

            <!-- PAGINASI                                    -->

            <!-- ========================================== -->

            @if ($limbahTersedia->total() > 0)

                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 py-6">

                    <span class="text-xs text-gray-600">

                        Menampilkan <strong class="font-mono">{{ $limbahTersedia->firstItem() }} -

                            {{ $limbahTersedia->lastItem() }}</strong>

                        dari <strong class="font-mono">{{ number_format($limbahTersedia->total(), 0, ',', '.') }}</strong>

                        hasil material

                    </span>



                    @if ($limbahTersedia->hasPages())
                        <nav class="flex items-center gap-2 text-xs font-bold" aria-label="Paginasi">

                            @if ($limbahTersedia->onFirstPage())
                                <span
                                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-[#F0F5F0] text-gray-300">&larr;</span>
                            @else
                                <a href="{{ $limbahTersedia->previousPageUrl() }}"
                                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-[#F0F5F0] text-[#093A25] hover:bg-[#E4EBE4]"
                                    aria-label="Sebelumnya">&larr;</a>
                            @endif



                            @php $prev = 0; @endphp

                            @foreach ($nomor as $n)
                                @if ($n - $prev > 1)
                                    <span class="px-1 text-gray-400">&hellip;</span>
                                @endif



                                @if ($n === $cur)
                                    <span
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-[#1A4B36] text-white">{{ $n }}</span>
                                @else
                                    <a href="{{ $limbahTersedia->url($n) }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-[#F0F5F0] text-[#414943] hover:bg-[#E4EBE4]">{{ $n }}</a>
                                @endif

                                @php $prev = $n; @endphp
                            @endforeach



                            @if ($limbahTersedia->hasMorePages())
                                <a href="{{ $limbahTersedia->nextPageUrl() }}"
                                    class="h-9 px-4 flex items-center gap-1.5 rounded-lg bg-[#F0F5F0] text-[#093A25] hover:bg-[#E4EBE4]">Selanjutnya

                                    &rarr;</a>
                            @else
                                <span
                                    class="h-9 px-4 flex items-center gap-1.5 rounded-lg bg-[#F0F5F0] text-gray-300">Selanjutnya

                                    &rarr;</span>
                            @endif

                        </nav>
                    @endif

                </div>

            @endif



            <!-- ========================================== -->

            <!-- CTA BAWAH                                   -->

            <!-- ========================================== -->

            <div
                class="mt-10 mb-20 bg-gradient-to-br from-[#093A25] to-[#0E4A31] rounded-3xl p-8 md:p-12 flex flex-col md:flex-row md:items-center justify-between gap-8 relative overflow-hidden shadow-md">

                <div class="absolute right-10 top-0 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>



                <div class="relative z-10 max-w-xl">

                    <span
                        class="inline-flex items-center gap-1.5 bg-[#C6ECCD] text-[#093A25] px-3 py-1 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider mb-4">

                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">

                            <circle cx="12" cy="12" r="9" />

                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.5 9.5a2.5 2.5 0 114 2c-.8.5-1.5 1-1.5 2M12 17h.01" />

                        </svg>

                        Kebutuhan Khusus

                    </span>

                    <h3 class="text-2xl font-bold text-white mb-2">Tidak menemukan limbah atau material yang Anda cari?

                    </h3>

                    <p class="text-sm text-[#BFD3C6] leading-relaxed">

                        Daftarkan spesifikasi volume atau preferensi material circular yang Anda butuhkan. Sistem SIRKULA

                        akan menghubungkan Anda dengan jaringan mitra industri penyedia.

                    </p>

                </div>



                <div class="relative z-10 flex flex-col sm:flex-row gap-3 shrink-0">

                    <a href="{{ route('permintaan') }}"
                        class="bg-white text-[#093A25] px-6 py-3.5 rounded-xl font-bold text-sm text-center shadow-sm hover:bg-[#F0F5F0] transition-colors">

                        Daftarkan Permintaan

                    </a>

                    <a href="mailto:{{ config('mail.from.address') }}"
                        class="bg-white/10 text-white px-6 py-3.5 rounded-xl font-bold text-sm text-center hover:bg-white/20 transition-colors">

                        Pusat Bantuan

                    </a>

                </div>

            </div>

        </div>



        {{-- ========================================== --}}

        {{-- 7. FOOTER --}}

        {{-- ========================================== --}}

        {{-- <footer class="bg-[#F6FBF5] pt-8 border-t border-[#EBEFEA] pb-10">

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

        </footer> --}}

    </div>

@endsection
