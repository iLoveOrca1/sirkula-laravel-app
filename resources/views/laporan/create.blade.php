@extends('layouts.app')

@section('content')
    @php
        // Pengaman: jika tabel kategori terlanjur berisi nama yang sama lebih dari sekali,
        // tampilkan satu saja (baris dengan id terkecil).
        $kategori = $kategori->sortBy('id')->unique('nama_kategori')->values();

        $kategoriPertama = $kategori->first();
        $kategoriAwal = old('kategori_id', $kategoriPertama->id ?? '');
        $namaInstansi = Auth::user()->nama_instansi ?: Auth::user()->name ?? 'Nama Instansi';
        $jenisLokasi = old('jenis_lokasi', 'utama');
        $lokasiAwal = Auth::user()->alamat_lengkap
            ? Str::limit(Auth::user()->alamat_lengkap, 38)
            : 'Sesuai alamat profil akun';
        $namaKategoriAwal = preg_replace('/^Limbah\s+/i', '', $kategoriPertama->nama_kategori ?? 'Kategori');

        $inputClass =
            'w-full bg-[#f0f3f0] border-none rounded-xl py-3.5 px-4 text-[15px] text-[#1f2a24] placeholder:text-gray-400 focus:ring-2 focus:ring-[#24523c] outline-none transition';
        $labelClass = 'text-[15px] font-medium text-[#0f3d2a]';
        $cardClass = 'rounded-2xl bg-white p-6 shadow-[0_4px_24px_rgba(15,61,42,0.06)] md:p-9';

        // Ikon & deskripsi cadangan per kategori (deskripsi DB diutamakan jika ada)
        $ikonKategori = function ($slug) {
            $slug = (string) $slug;
            if (str_contains($slug, 'anorganik')) {
                return 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4';
            }
            if (str_contains($slug, 'organik')) {
                return 'M5 19c8 0 14-6 14-14-8 0-14 6-14 14zm0 0c2-4 5-7 9-9';
            }
            return 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z';
        };

        $deskripsiKategori = function ($kat) {
            if (!empty($kat->deskripsi)) {
                return $kat->deskripsi;
            }
            $slug = (string) $kat->slug;
            if (str_contains($slug, 'anorganik')) {
                return 'Kardus corrugated, lembaran kertas arsip, plastik HDPE/PET, pecahan botol kaca bening, palet kayu bekas, atau serpih besi.';
            }
            if (str_contains($slug, 'organik')) {
                return 'Sisa makanan hotel/resto, ampas tahu/kedelai, daun pangkasan taman, minyak jelantah bekas pakai, atau limbah fermentasi.';
            }
            return '';
        };
    @endphp

    {{-- Font desain: Plus Jakarta Sans (teks) + JetBrains Mono (label kecil). Boleh dipindah ke layouts/app.blade.php. --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        #halaman-tambah-limbah {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        }

        #halaman-tambah-limbah .font-mono {
            font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, monospace;
        }
    </style>

    <div id="halaman-tambah-limbah" class="min-h-screen bg-[#f4f9f4] pb-24 pt-10">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

            {{-- ========================================== --}}
            {{-- HEADER                                      --}}
            {{-- ========================================== --}}
            <div class="mb-10 grid grid-cols-1 gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)] lg:gap-8">
                <div>
                    <div
                        class="mb-5 flex items-center gap-2 font-mono text-[10px] font-medium uppercase tracking-widest text-gray-500">
                        <a href="{{ route('dashboard') }}" class="transition hover:text-[#0f3d2a]">Dashboard Mitra</a>
                        <span>/</span>
                        <span class="font-bold text-[#0f3d2a]">Tambah Limbah Baru</span>
                    </div>

                    <div
                        class="mb-4 inline-flex w-fit items-center gap-2 rounded-full bg-[#e6eee6] px-3 py-1 font-mono text-[10px] font-medium uppercase tracking-widest text-[#24523c]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#0f3d2a]"></span>
                        Formulir Sirkulasi Material V2.4
                    </div>

                    <h1 class="mb-3 text-3xl font-semibold tracking-tight text-[#0f3d2a] md:text-4xl">Tambah Informasi
                        Limbah</h1>
                    <p class="max-w-xl text-base leading-relaxed text-[#3f4a43]">
                        Bagikan rincian material limbah yang tersedia agar dapat segera ditemukan dan dihubungi oleh
                        recycler terverifikasi, pengumpul, atau peternak lokal.
                    </p>
                </div>

                <div class="flex flex-col gap-5">
                    <a href="{{ route('dashboard') }}"
                        class="hidden items-center gap-2 self-end text-[13px] text-gray-500 transition hover:text-[#0f3d2a] lg:flex">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Dashboard Mitra
                    </a>

                    <div class="relative overflow-hidden rounded-2xl bg-[#eaf0ea] p-5">
                        <div
                            class="pointer-events-none absolute -right-6 -top-6 h-28 w-28 rounded-full bg-white/70 blur-2xl">
                        </div>
                        <div class="relative mb-2 flex items-center gap-2 text-[15px] font-medium text-[#0f3d2a]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Panduan Transparansi
                        </div>
                        <p class="relative text-[13px] leading-relaxed text-[#3f4a43]">
                            SIRKULA mempermudah recycler menemukan material Anda. Pastikan volume, foto riil, dan nomor
                            WhatsApp valid agar proses koordinasi lapangan berjalan tanpa kendala.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">

                {{-- ========================================== --}}
                {{-- KOLOM FORM                                  --}}
                {{-- ========================================== --}}
                <div class="min-w-0">

                    @if ($errors->any())
                        <div class="mb-6 rounded-xl border border-red-100 bg-red-50 p-4 text-sm text-red-600">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="form-limbah" action="{{ route('laporan.store') }}" method="POST"
                        enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        {{-- ===== SECTION 1: INFORMASI MATERIAL ===== --}}
                        <div class="{{ $cardClass }}">
                            <div class="mb-8 flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[#eef2ee] font-mono text-xs font-medium text-[#24523c]">
                                        01</div>
                                    <div>
                                        <h2 class="text-xl font-medium tracking-tight text-[#0f3d2a]">Informasi Material
                                            Limbah</h2>
                                        <p class="mt-0.5 text-[13px] text-gray-500">Karakteristik teknis, klasifikasi arus
                                            material, dan bukti foto visual</p>
                                    </div>
                                </div>
                                <span
                                    class="hidden shrink-0 rounded-full bg-[#eef2ee] px-3 py-1.5 font-mono text-[10px] font-medium uppercase tracking-widest text-[#24523c] sm:inline-block">Wajib
                                    Diisi</span>
                            </div>

                            <div class="space-y-6">

                                {{-- Nama material --}}
                                <div>
                                    <div class="mb-2 flex items-center justify-between">
                                        <label for="judul" class="{{ $labelClass }}">Nama Material Limbah <span
                                                class="text-[#b0431b]">*</span></label>
                                        <span class="font-mono text-[10px] text-gray-400">Maks 80 karakter</span>
                                    </div>
                                    <input type="text" id="judul" name="judul" value="{{ old('judul') }}"
                                        maxlength="80" required placeholder="Kardus Corrugated Duplex Kering"
                                        class="{{ $inputClass }}">
                                    <p class="mt-2 text-xs text-gray-500">Gunakan nama yang spesifik, bersih, dan jelas bagi
                                        operator pabrik atau pengolah sirkular.</p>
                                </div>

                                {{-- Kategori --}}
                                <div>
                                    <label class="{{ $labelClass }} mb-3 block">Kategori Limbah <span
                                            class="text-[#b0431b]">*</span></label>
                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                        @foreach ($kategori as $kat)
                                            <label class="block cursor-pointer">
                                                <input type="radio" name="kategori_id" value="{{ $kat->id }}"
                                                    required data-nama="{{ $kat->nama_kategori }}"
                                                    {{ (string) $kategoriAwal === (string) $kat->id ? 'checked' : '' }}
                                                    class="kategori-radio sr-only">
                                                <div
                                                    class="opsi-card h-full rounded-2xl bg-[#f0f3f0] px-5 py-5 transition-all">
                                                    <div class="mb-2 flex items-center justify-between">
                                                        <span
                                                            class="flex items-center gap-2 text-[15px] font-medium text-[#0f3d2a]">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                                stroke-width="2" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="{{ $ikonKategori($kat->slug) }}" />
                                                            </svg>
                                                            {{ $kat->nama_kategori ?? 'Kategori ' . $kat->id }}
                                                        </span>
                                                        <span
                                                            class="opsi-dot flex h-5 w-5 items-center justify-center rounded-full bg-[#dfe5df] transition-colors">
                                                            <span
                                                                class="opsi-dot-inner h-2 w-2 rounded-full bg-white opacity-0"></span>
                                                        </span>
                                                    </div>
                                                    @if ($deskripsiKategori($kat) !== '')
                                                        <p class="text-[13px] leading-relaxed text-gray-500">
                                                            {{ $deskripsiKategori($kat) }}</p>
                                                    @endif
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Foto --}}
                                <div>
                                    <div class="mb-3 flex items-center justify-between">
                                        <label for="foto_limbah" class="{{ $labelClass }}">Foto Kondisi Material <span
                                                class="text-[#b0431b]">*</span></label>
                                        <span class="font-mono text-[10px] text-gray-400">Maks 1 file (PNG, JPG hingga
                                            2MB)</span>
                                    </div>

                                    <div class="flex flex-col gap-4 sm:h-44 sm:flex-row">
                                        {{-- Preview kiri --}}
                                        <div
                                            class="relative h-44 w-full overflow-hidden rounded-2xl bg-[#e6ebe6] sm:h-auto sm:w-[40%]">
                                            <div id="foto-kosong"
                                                class="absolute inset-0 flex flex-col items-center justify-center text-gray-400">
                                                <svg class="mb-1 h-8 w-8" fill="none" stroke="currentColor"
                                                    stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span class="font-mono text-[10px]">Belum ada foto</span>
                                            </div>
                                            <img id="foto-preview" src="" alt="Preview"
                                                class="hidden h-full w-full object-cover">
                                            <div id="foto-ukuran"
                                                class="absolute bottom-2 left-2 rounded bg-black/55 px-2 py-1 font-mono text-[9px] font-medium uppercase tracking-widest text-white backdrop-blur-sm">
                                                Foto Utama</div>
                                        </div>

                                        {{-- Area upload kanan --}}
                                        <label for="foto_limbah"
                                            class="flex min-h-[11rem] w-full cursor-pointer flex-col items-center justify-center rounded-2xl bg-[#eef2ee] px-4 text-center transition-colors hover:bg-[#e6ece6] sm:w-[60%]">
                                            <span
                                                class="mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-white text-[#24523c] shadow-sm">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                    stroke-width="1.8" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 4v12m0-12l-4 4m4-4l4 4" />
                                                </svg>
                                            </span>
                                            <p class="text-[15px] font-medium text-[#0f3d2a]">Klik untuk telusuri foto</p>
                                            <p class="mb-3 mt-1 text-xs text-gray-500">Tampilkan tumpukan dan kemasan
                                                secara nyata</p>
                                            <span id="foto-nama"
                                                class="max-w-full truncate rounded bg-[#e3e9e3] px-3 py-1.5 font-mono text-[10px] font-medium uppercase tracking-widest text-[#3f4a43]">+
                                                Pilih File</span>
                                        </label>
                                        {{-- sr-only (bukan hidden) supaya validasi "required" browser tetap bisa menampilkan pesan --}}
                                        <input type="file" id="foto_limbah" name="foto_limbah" required
                                            accept="image/png,image/jpeg,image/jpg" class="sr-only">
                                    </div>
                                </div>

                                {{-- Volume & satuan --}}
                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <div>
                                        <label for="estimasi_volume" class="{{ $labelClass }} mb-2 block">Jumlah
                                            Estimasi Material <span class="text-[#b0431b]">*</span></label>
                                        <div class="relative">
                                            <input type="number" id="estimasi_volume" name="estimasi_volume"
                                                value="{{ old('estimasi_volume') }}" required placeholder="250"
                                                min="0" step="any"
                                                class="{{ $inputClass }} pr-24 font-mono">
                                            <span
                                                class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 rounded bg-[#e3e9e3] px-2 py-0.5 font-mono text-[10px] font-medium uppercase tracking-widest text-gray-500">Estimasi</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="satuan" class="{{ $labelClass }} mb-2 block">Satuan Ukuran <span
                                                class="text-[#b0431b]">*</span></label>
                                        <div class="relative">
                                            <select id="satuan" name="satuan" required
                                                class="{{ $inputClass }} cursor-pointer appearance-none pr-10">
                                                <option value="kg"
                                                    {{ old('satuan', 'kg') === 'kg' ? 'selected' : '' }}>Kilogram (KG)
                                                </option>
                                                <option value="ton" {{ old('satuan') === 'ton' ? 'selected' : '' }}>Ton
                                                </option>
                                                <option value="liter" {{ old('satuan') === 'liter' ? 'selected' : '' }}>
                                                    Liter</option>
                                                <option value="pcs" {{ old('satuan') === 'pcs' ? 'selected' : '' }}>
                                                    Unit / Buah</option>
                                            </select>
                                            <svg class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-500"
                                                fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Deskripsi --}}
                                <div>
                                    <label for="deskripsi" class="{{ $labelClass }} mb-2 block">Deskripsi &amp; Kondisi
                                        Material <span class="text-[#b0431b]">*</span></label>
                                    <textarea id="deskripsi" name="deskripsi" rows="4" required
                                        placeholder="Kardus dalam kondisi kering dan bersih dari kontaminasi minyak makanan. Seluruh kardus sudah dipipihkan, ditumpuk padat, dan diikat..."
                                        class="{{ $inputClass }} resize-none leading-relaxed">{{ old('deskripsi') }}</textarea>
                                    <p class="mt-2 text-xs text-gray-500">Deskripsi detail memangkas 70% pertanyaan teknis
                                        awal dari pihak recycler.</p>
                                </div>
                            </div>
                        </div>

                        {{-- ===== SECTION 2: LOKASI & PENGAMBILAN ===== --}}
                        <div class="{{ $cardClass }}">
                            <div class="mb-8 flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[#eef2ee] font-mono text-xs font-medium text-[#24523c]">
                                        02</div>
                                    <div>
                                        <h2 class="text-xl font-medium tracking-tight text-[#0f3d2a]">Lokasi &amp;
                                            Pengambilan</h2>
                                        <p class="mt-0.5 text-[13px] text-gray-500">Koordinat titik muat dan patokan lokasi
                                            penjemputan</p>
                                    </div>
                                </div>
                                <span
                                    class="hidden shrink-0 rounded-full bg-[#eef2ee] px-3 py-1.5 font-mono text-[10px] font-medium uppercase tracking-widest text-[#24523c] sm:inline-block">Logistik
                                    Lapangan</span>
                            </div>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <label class="block cursor-pointer">
                                        <input type="radio" name="jenis_lokasi" id="lokasi_utama" value="utama"
                                            class="lokasi-radio sr-only" {{ $jenisLokasi === 'utama' ? 'checked' : '' }}>
                                        <div class="opsi-card h-full rounded-2xl bg-[#f0f3f0] px-5 py-4 transition-all">
                                            <div class="flex items-center justify-between">
                                                <p class="text-[15px] font-medium text-[#0f3d2a]">Gunakan Alamat Utama</p>
                                                <span
                                                    class="opsi-dot flex h-5 w-5 items-center justify-center rounded-full bg-[#dfe5df] transition-colors">
                                                    <span
                                                        class="opsi-dot-inner h-2 w-2 rounded-full bg-white opacity-0"></span>
                                                </span>
                                            </div>
                                            <p class="mt-1 text-[13px] text-gray-500">Koordinat otomatis dari profil akun.
                                            </p>
                                        </div>
                                    </label>

                                    <label class="block cursor-pointer">
                                        <input type="radio" name="jenis_lokasi" id="lokasi_lainnya" value="lainnya"
                                            class="lokasi-radio sr-only"
                                            {{ $jenisLokasi === 'lainnya' ? 'checked' : '' }}>
                                        <div class="opsi-card h-full rounded-2xl bg-[#f0f3f0] px-5 py-4 transition-all">
                                            <div class="flex items-center justify-between">
                                                <p class="text-[15px] font-medium text-[#0f3d2a]">Tentukan Koordinat Manual
                                                </p>
                                                <span
                                                    class="opsi-dot flex h-5 w-5 items-center justify-center rounded-full bg-[#dfe5df] transition-colors">
                                                    <span
                                                        class="opsi-dot-inner h-2 w-2 rounded-full bg-white opacity-0"></span>
                                                </span>
                                            </div>
                                            <p class="mt-1 text-[13px] text-gray-500">Gunakan peta interaktif.</p>
                                        </div>
                                    </label>
                                </div>

                                {{-- Peta interaktif --}}
                                <div id="form-lokasi-lainnya"
                                    style="{{ $jenisLokasi === 'lainnya' ? '' : 'display: none;' }}">
                                    <p class="mb-3 text-[13px] font-medium text-[#0f3d2a]">Geser peta untuk menentukan
                                        titik lokasi penjemputan limbah:</p>

                                    {{-- isolate: z-index internal Leaflet tidak menimpa navbar sticky --}}
                                    <div class="relative isolate mb-4">
                                        <div id="map" style="height: 320px;"
                                            class="z-10 w-full overflow-hidden rounded-2xl bg-[#e6ebe6]"></div>

                                        <div
                                            class="pointer-events-none absolute left-1/2 top-1/2 z-[400] flex -translate-x-1/2 -translate-y-full flex-col items-center">
                                            <div
                                                class="mb-1 whitespace-nowrap rounded-full bg-[#0f3d2a] px-3 py-1 font-mono text-[10px] font-medium text-white shadow-md">
                                                Lokasi Muat</div>
                                            <div class="h-4 w-4 rounded-full border-2 border-white bg-[#2f8f5b] shadow-lg">
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                                    <input type="hidden" name="longitude" id="longitude"
                                        value="{{ old('longitude') }}">

                                    <div>
                                        <label for="preview_alamat" class="{{ $labelClass }} mb-2 block">Alamat Lengkap
                                            Terpilih (Otomatis)</label>
                                        <textarea id="preview_alamat" readonly rows="2" placeholder="Mendeteksi lokasi..."
                                            class="w-full resize-none rounded-xl border-none bg-[#f0f3f0] px-4 py-3.5 text-sm text-gray-500 outline-none"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ===== TOMBOL ===== --}}
                        <div class="flex items-center gap-4 pt-2">
                            <a href="{{ route('dashboard') }}"
                                class="px-5 py-4 text-sm font-medium text-gray-600 transition-colors hover:text-[#0f3d2a]">Batal</a>
                            <button type="submit"
                                class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-[#24523c] px-8 py-4 text-[15px] font-medium text-white shadow-md transition-colors hover:bg-[#1b4430]">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Publikasikan Informasi Limbah
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ========================================== --}}
                {{-- KOLOM PRATINJAU                             --}}
                {{-- ========================================== --}}
                <div class="min-w-0 lg:sticky lg:top-24">
                    <div class="rounded-2xl bg-white p-5 shadow-[0_4px_24px_rgba(15,61,42,0.06)]">
                        <div class="mb-4 flex items-center justify-between">
                            <span
                                class="font-mono text-[10px] font-medium uppercase tracking-widest text-[#24523c]">Pratinjau
                                Tampilan Recycler</span>
                            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>

                        <div class="rounded-2xl bg-[#eef2ee] p-3">
                            <div class="relative mb-4 h-36 overflow-hidden rounded-xl bg-[#dfe5df]">
                                <div id="pv-foto-kosong"
                                    class="absolute inset-0 flex items-center justify-center font-mono text-[10px] text-gray-400">
                                    Belum ada foto</div>
                                <img id="pv-foto" src="" alt="Preview"
                                    class="hidden h-full w-full object-cover">

                                <div
                                    class="absolute left-2 top-2 flex max-w-[62%] items-center gap-1.5 rounded bg-white/95 px-2 py-1 shadow-sm">
                                    <svg class="h-3 w-3 shrink-0 text-[#24523c]" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <span
                                        class="block truncate font-mono text-[10px] text-[#0f3d2a]">{{ $namaInstansi }}</span>
                                </div>
                                <div
                                    class="absolute right-2 top-2 flex items-center gap-1.5 rounded-full bg-[#c9ecd3] px-2 py-0.5 font-mono text-[10px] font-medium text-[#1d5b3a]">
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#2f8f5b]"></span> Tersedia
                                </div>
                            </div>

                            <div class="px-1 pb-1">
                                <div class="mb-2 flex items-center justify-between gap-2">
                                    <span id="pv-kategori"
                                        class="truncate font-mono text-[10px] font-medium uppercase tracking-widest text-gray-500">{{ $namaKategoriAwal }}</span>
                                    <span
                                        class="shrink-0 rounded-md bg-[#f6f9f6] px-2 py-1 font-mono text-xs font-bold text-[#0f3d2a]">
                                        <span id="pv-volume">0</span> <span id="pv-satuan">KG</span>
                                    </span>
                                </div>
                                <h3 id="pv-judul" class="mb-2 truncate text-base font-semibold text-[#0f3d2a]">Nama
                                    material Anda...</h3>
                                <p id="pv-deskripsi" class="mb-3 line-clamp-2 text-xs leading-relaxed text-gray-600">
                                    Deskripsi material akan tampil di sini.</p>

                                <p class="mb-4 flex items-center gap-1.5 font-mono text-[10px] text-gray-600">
                                    <svg class="h-3.5 w-3.5 shrink-0 text-gray-500" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span id="pv-lokasi" class="truncate">{{ $lokasiAwal }}</span>
                                </p>

                                <div
                                    class="mb-3 flex items-center justify-center gap-2 rounded-lg bg-[#24523c] py-3 text-sm font-medium text-white shadow-sm">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 10h8M8 14h5m8-2c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    Hubungi via WhatsApp
                                </div>
                                <p class="text-center text-[10px] leading-relaxed text-gray-500">Transaksi &amp;
                                    pengambilan dilakukan langsung dengan mitra</p>
                            </div>
                        </div>

                        <div class="px-1 pt-6">
                            <h4 class="mb-4 text-[15px] font-medium text-[#0f3d2a]">Tips Agar Cepat Diambil:</h4>
                            <ul class="space-y-3.5 text-[13px] leading-relaxed text-[#3f4a43]">
                                @foreach (['Foto asli tanpa editan mempercepat minat hingga 3x lipat.', 'Sebutkan apakah lokasi dapat dilalui truk engkel atau hanya pikap kecil.', 'Pastikan nomor WhatsApp merespons pada jam operasional kerja.'] as $tip)
                                    <li class="flex items-start gap-2.5">
                                        <svg class="mt-0.5 h-[18px] w-[18px] shrink-0 text-[#24523c]" fill="none"
                                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $tip }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const $ = (id) => document.getElementById(id);

            // ------------------------------------------------------------
            // Gaya kartu pilihan (dipakai kategori & jenis lokasi)
            // ------------------------------------------------------------
            function styleOpsi(radio) {
                const card = radio.parentElement.querySelector('.opsi-card');
                const dot = card.querySelector('.opsi-dot');
                const inner = card.querySelector('.opsi-dot-inner');
                const on = radio.checked;

                card.classList.toggle('bg-white', on);
                card.classList.toggle('shadow-md', on);
                card.classList.toggle('bg-[#f0f3f0]', !on);

                dot.classList.toggle('bg-[#0f3d2a]', on);
                dot.classList.toggle('bg-[#dfe5df]', !on);
                inner.classList.toggle('opacity-100', on);
                inner.classList.toggle('opacity-0', !on);
            }

            // ------------------------------------------------------------
            // Pratinjau langsung (tanpa Alpine.js)
            // ------------------------------------------------------------
            const satuanLabel = {
                kg: 'KG',
                ton: 'Ton',
                liter: 'Liter',
                pcs: 'Unit'
            };

            function syncPreview() {
                $('pv-judul').textContent = $('judul').value.trim() || 'Nama material Anda...';
                $('pv-volume').textContent = $('estimasi_volume').value || '0';
                $('pv-satuan').textContent = satuanLabel[$('satuan').value] || $('satuan').value;
                $('pv-deskripsi').textContent = $('deskripsi').value.trim() ||
                    'Deskripsi material akan tampil di sini.';
            }

            ['judul', 'estimasi_volume', 'satuan', 'deskripsi'].forEach(function(id) {
                $(id).addEventListener('input', syncPreview);
                $(id).addEventListener('change', syncPreview);
            });
            syncPreview();

            // ------------------------------------------------------------
            // Kategori (gaya kartu terpilih + pratinjau)
            // ------------------------------------------------------------
            const kategoriRadios = document.querySelectorAll('.kategori-radio');

            function syncKategori() {
                kategoriRadios.forEach(function(radio) {
                    styleOpsi(radio);
                    if (radio.checked && radio.dataset.nama) {
                        $('pv-kategori').textContent = radio.dataset.nama.replace(/^Limbah\s+/i, '');
                    }
                });
            }
            kategoriRadios.forEach(function(r) {
                r.addEventListener('change', syncKategori);
            });
            syncKategori();

            // ------------------------------------------------------------
            // Foto
            // ------------------------------------------------------------
            $('foto_limbah').addEventListener('change', function() {
                const file = this.files && this.files[0];
                if (!file) return;

                const url = URL.createObjectURL(file);

                $('foto-preview').src = url;
                $('foto-preview').classList.remove('hidden');
                $('foto-kosong').classList.add('hidden');

                $('pv-foto').src = url;
                $('pv-foto').classList.remove('hidden');
                $('pv-foto-kosong').classList.add('hidden');

                $('foto-nama').textContent = file.name;
                $('foto-ukuran').textContent = 'Foto Utama • ' + (file.size / 1048576).toFixed(1) + ' MB';
            });

            // ------------------------------------------------------------
            // Lokasi & peta
            // ------------------------------------------------------------
            const radioUtama = $('lokasi_utama');
            const radioLainnya = $('lokasi_lainnya');
            const formLainnya = $('form-lokasi-lainnya');
            const lokasiRadios = document.querySelectorAll('.lokasi-radio');
            const lokasiAwal = $('pv-lokasi').textContent;

            let map = null;
            let geocodeTimeout = null;

            function syncLokasiCard() {
                lokasiRadios.forEach(styleOpsi);
            }

            function updateLocationData(center) {
                const lat = center.lat.toFixed(6);
                const lng = center.lng.toFixed(6);

                $('latitude').value = lat;
                $('longitude').value = lng;

                const preview = $('preview_alamat');
                preview.value = lat + ', ' + lng;
                $('pv-lokasi').textContent = lat + ', ' + lng;

                clearTimeout(geocodeTimeout);
                geocodeTimeout = setTimeout(function() {
                    fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' +
                            lng)
                        .then(function(res) {
                            if (!res.ok) throw new Error('Gagal mengambil alamat.');
                            return res.json();
                        })
                        .then(function(data) {
                            if (data && data.display_name) {
                                preview.value = data.display_name;
                                $('pv-lokasi').textContent = data.display_name
                                    .split(',').slice(0, 2).map(function(s) {
                                        return s.trim();
                                    }).join(', ');
                            }
                        })
                        .catch(function(err) {
                            console.error('Gagal mendapatkan alamat:', err);
                        });
                }, 800);
            }

            function initMap(lat, lng) {
                if (map) {
                    map.invalidateSize();
                    return;
                }

                map = L.map('map').setView([lat, lng], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap'
                }).addTo(map);

                updateLocationData(map.getCenter());
                map.on('moveend', function() {
                    updateLocationData(map.getCenter());
                });
            }

            function showMap() {
                formLainnya.style.display = 'block';
                setTimeout(function() {
                    const lat = parseFloat($('latitude').value) || -8.6705;
                    const lng = parseFloat($('longitude').value) || 115.2126;
                    initMap(lat, lng);
                }, 200);
            }

            radioUtama.addEventListener('change', function() {
                if (this.checked) {
                    formLainnya.style.display = 'none';
                    $('pv-lokasi').textContent = lokasiAwal;
                }
                syncLokasiCard();
            });

            radioLainnya.addEventListener('change', function() {
                if (this.checked) showMap();
                syncLokasiCard();
            });

            syncLokasiCard();
            if (radioLainnya.checked) showMap();
        });
    </script>
@endsection
