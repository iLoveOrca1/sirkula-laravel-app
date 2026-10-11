@extends('layouts.app')

@section('content')
    @php
        // Pengaman: jika tabel kategori terlanjur berisi nama yang sama lebih dari sekali,
        // tampilkan satu saja (baris dengan id terkecil).
        $kategori = $kategori->sortBy('id')->unique('nama_kategori')->values();

        $kategoriPertama = $kategori->first();
        $kategoriAwal    = old('kategori_id', $kategoriPertama->id ?? '');
        $namaInstansi    = Auth::user()->nama_instansi ?: (Auth::user()->name ?? 'Nama Instansi');
        $jenisLokasi     = old('jenis_lokasi', 'utama');

        $inputClass = 'w-full bg-[#F6FBF5] border-none rounded-xl py-3.5 px-4 text-sm text-[#414943] focus:ring-2 focus:ring-[#093A25] outline-none transition-all';
    @endphp

    <div class="bg-[#F6FBF5] min-h-screen pb-24 pt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ========================================== --}}
            {{-- HEADER                                      --}}
            {{-- ========================================== --}}
            <div class="flex items-start justify-between gap-6 mb-8">
                <div class="flex flex-col">
                    <div class="flex items-center gap-2 text-[10px] font-mono font-bold uppercase tracking-widest text-gray-400 mb-3">
                        <a href="{{ route('dashboard') }}" class="hover:text-[#093A25]">Dashboard Mitra</a>
                        <span>/</span>
                        <span class="text-[#093A25]">Tambah Limbah Baru</span>
                    </div>
                    <div class="inline-flex items-center gap-2 px-2.5 py-1 bg-[#F0F5F0] text-gray-500 rounded font-mono text-[9px] font-bold uppercase tracking-widest mb-4 w-fit border border-[#EBEFEA]">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                        Formulir Sirkulasi Material
                    </div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-[#093A25] tracking-tight mb-2">Tambah Informasi Limbah</h1>
                    <p class="text-sm text-[#414943] max-w-xl leading-relaxed">
                        Bagikan rincian material limbah yang tersedia agar dapat segera ditemukan dan dihubungi oleh recycler terverifikasi, pengumpul, atau peternak lokal.
                    </p>
                </div>

                <a href="{{ route('dashboard') }}" class="hidden md:flex shrink-0 items-center gap-2 text-sm font-medium text-gray-500 hover:text-[#093A25] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Dashboard Mitra
                </a>
            </div>

            <div class="flex flex-col lg:flex-row gap-8 items-start">

                {{-- ========================================== --}}
                {{-- KOLOM FORM                                  --}}
                {{-- ========================================== --}}
                <div class="w-full lg:w-2/3">

                    @if ($errors->any())
                        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm border border-red-100">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="form-limbah" action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        {{-- ===== SECTION 1: INFORMASI MATERIAL ===== --}}
                        <div class="bg-white rounded-[2rem] border border-[#EBEFEA] shadow-sm p-6 md:p-10">
                            <div class="flex items-center justify-between gap-3 border-b border-[#EBEFEA] pb-6 mb-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 shrink-0 rounded-full bg-[#F0F5F0] text-[#1A4B36] font-bold font-mono text-lg flex items-center justify-center border border-[#EBEFEA]">01</div>
                                    <div>
                                        <h2 class="text-xl font-bold text-[#093A25]">Informasi Material Limbah</h2>
                                        <p class="text-xs text-gray-500 mt-1">Karakteristik teknis, klasifikasi arus material, dan bukti foto visual</p>
                                    </div>
                                </div>
                                <span class="hidden sm:inline-block bg-[#F0F5F0] text-[#093A25] text-[10px] font-mono font-bold px-3 py-1.5 rounded uppercase tracking-widest border border-[#EBEFEA]">Wajib Diisi</span>
                            </div>

                            <div class="space-y-6">

                                {{-- Nama material --}}
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <label for="judul" class="text-sm font-bold text-[#093A25]">Nama Material Limbah <span class="text-red-500">*</span></label>
                                        <span class="text-[10px] font-mono text-gray-400">Maks 80 karakter</span>
                                    </div>
                                    <input type="text" id="judul" name="judul" value="{{ old('judul') }}" maxlength="80" required
                                           placeholder="Kardus Corrugated Duplex Kering" class="{{ $inputClass }}">
                                    <p class="text-xs text-gray-500 mt-2">Gunakan nama yang spesifik, bersih, dan jelas bagi operator pabrik atau pengolah sirkular.</p>
                                </div>

                                {{-- Kategori --}}
                                <div>
                                    <label class="block text-sm font-bold text-[#093A25] mb-4">Kategori Limbah <span class="text-red-500">*</span></label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach ($kategori as $kat)
                                            <label class="cursor-pointer block">
                                                <input type="radio" name="kategori_id" value="{{ $kat->id }}" required
                                                       data-nama="{{ $kat->nama_kategori }}"
                                                       {{ (string) $kategoriAwal === (string) $kat->id ? 'checked' : '' }}
                                                       class="kategori-radio sr-only">
                                                <div class="kategori-card h-full px-5 py-5 rounded-2xl border-2 transition-all bg-[#F6FBF5] border-transparent">
                                                    <div class="flex items-center justify-between mb-3">
                                                        <span class="flex items-center gap-2 font-bold text-[#093A25]">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                                            {{ $kat->nama_kategori ?? 'Kategori ' . $kat->id }}
                                                        </span>
                                                        <span class="kategori-dot w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center transition-colors">
                                                            <span class="kategori-dot-inner w-2 h-2 rounded-full bg-white opacity-0"></span>
                                                        </span>
                                                    </div>
                                                    @if (! empty($kat->deskripsi))
                                                        <p class="text-xs text-gray-500 leading-relaxed">{{ $kat->deskripsi }}</p>
                                                    @endif
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Foto --}}
                                <div class="pt-4 border-t border-[#EBEFEA]">
                                    <div class="flex justify-between items-center mb-4">
                                        <label for="foto_limbah" class="text-sm font-bold text-[#093A25]">Foto Kondisi Material <span class="text-red-500">*</span></label>
                                        <span class="text-[10px] font-mono text-gray-400">Maks 1 file (PNG, JPG hingga 2MB)</span>
                                    </div>

                                    <div class="flex flex-col sm:flex-row gap-4 sm:h-48">
                                        {{-- Preview kiri --}}
                                        <div class="w-full h-48 sm:h-auto sm:w-1/3 bg-[#EBEFEA] rounded-2xl overflow-hidden relative border border-gray-200">
                                            <div id="foto-kosong" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400">
                                                <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                <span class="text-[10px] font-mono">Belum ada foto</span>
                                            </div>
                                            <img id="foto-preview" src="" alt="Preview" class="hidden w-full h-full object-cover">
                                            <div class="absolute bottom-2 left-2 bg-black/60 backdrop-blur-sm text-white text-[9px] font-mono font-bold uppercase tracking-widest px-2 py-1 rounded">Foto Utama</div>
                                        </div>

                                        {{-- Area upload kanan --}}
                                        <label for="foto_limbah"
                                               class="w-full sm:w-2/3 min-h-[11rem] border-2 border-dashed border-[#DCE5DC] rounded-2xl bg-[#F6FBF5] flex flex-col items-center justify-center text-center px-4 hover:bg-[#F0F5F0] transition-colors cursor-pointer">
                                            <svg class="w-8 h-8 text-[#093A25] mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 4v12m0-12l-4 4m4-4l4 4"/></svg>
                                            <p class="text-sm font-bold text-[#093A25]">Klik untuk telusuri foto</p>
                                            <p class="text-xs text-gray-500 mt-1 mb-3">Tampilkan tumpukan dan kemasan secara nyata</p>
                                            <span id="foto-nama" class="bg-[#EBEFEA] text-[#414943] text-[10px] font-mono font-bold uppercase tracking-widest px-3 py-1.5 rounded max-w-full truncate">+ Pilih File</span>
                                        </label>
                                        {{-- sr-only (bukan hidden) supaya validasi "required" browser tetap bisa menampilkan pesan --}}
                                        <input type="file" id="foto_limbah" name="foto_limbah" required accept="image/png,image/jpeg,image/jpg" class="sr-only">
                                    </div>
                                </div>

                                {{-- Volume & satuan --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4">
                                    <div>
                                        <label for="estimasi_volume" class="block text-sm font-bold text-[#093A25] mb-2">Jumlah Estimasi Material <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="number" id="estimasi_volume" name="estimasi_volume" value="{{ old('estimasi_volume') }}"
                                                   required placeholder="250" min="0" step="any" class="{{ $inputClass }} pr-24">
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-mono font-bold uppercase tracking-widest text-gray-400 bg-[#EBEFEA] px-2 py-0.5 rounded pointer-events-none">Estimasi</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="satuan" class="block text-sm font-bold text-[#093A25] mb-2">Satuan Ukuran <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select id="satuan" name="satuan" required
                                                    class="{{ $inputClass }} font-medium appearance-none cursor-pointer pr-10">
                                                <option value="kg" {{ old('satuan', 'kg') === 'kg' ? 'selected' : '' }}>Kilogram (KG)</option>
                                                <option value="ton" {{ old('satuan') === 'ton' ? 'selected' : '' }}>Ton</option>
                                                <option value="liter" {{ old('satuan') === 'liter' ? 'selected' : '' }}>Liter</option>
                                                <option value="pcs" {{ old('satuan') === 'pcs' ? 'selected' : '' }}>Unit / Buah</option>
                                            </select>
                                            <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Deskripsi --}}
                                <div class="pt-4 border-t border-[#EBEFEA]">
                                    <label for="deskripsi" class="block text-sm font-bold text-[#093A25] mb-2">Deskripsi &amp; Kondisi Material <span class="text-red-500">*</span></label>
                                    <textarea id="deskripsi" name="deskripsi" rows="4" required
                                              placeholder="Kardus dalam kondisi kering dan bersih dari kontaminasi minyak makanan. Seluruh kardus sudah dipipihkan, ditumpuk padat, dan diikat..."
                                              class="{{ $inputClass }} resize-none">{{ old('deskripsi') }}</textarea>
                                    <p class="text-xs text-gray-500 mt-2">Deskripsi yang detail membantu mengurangi pertanyaan teknis awal dari pihak recycler.</p>
                                </div>
                            </div>
                        </div>

                        {{-- ===== SECTION 2: LOKASI & PENGAMBILAN ===== --}}
                        <div class="bg-white rounded-[2rem] border border-[#EBEFEA] shadow-sm p-6 md:p-10">
                            <div class="flex items-center justify-between gap-3 border-b border-[#EBEFEA] pb-6 mb-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 shrink-0 rounded-full bg-[#F0F5F0] text-[#1A4B36] font-bold font-mono text-lg flex items-center justify-center border border-[#EBEFEA]">02</div>
                                    <div>
                                        <h2 class="text-xl font-bold text-[#093A25]">Lokasi &amp; Pengambilan</h2>
                                        <p class="text-xs text-gray-500 mt-1">Koordinat titik muat dan patokan lokasi penjemputan</p>
                                    </div>
                                </div>
                                <span class="hidden sm:inline-block bg-[#F0F5F0] text-[#093A25] text-[10px] font-mono font-bold px-3 py-1.5 rounded uppercase tracking-widest border border-[#EBEFEA]">Logistik Lapangan</span>
                            </div>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label class="cursor-pointer block">
                                        <input type="radio" name="jenis_lokasi" id="lokasi_utama" value="utama" class="lokasi-radio sr-only"
                                               {{ $jenisLokasi === 'utama' ? 'checked' : '' }}>
                                        <div class="lokasi-card h-full border-2 border-[#EBEFEA] rounded-xl p-4 transition-all">
                                            <p class="text-sm font-bold text-[#093A25]">Gunakan Alamat Utama</p>
                                            <p class="text-xs text-[#414943] mt-1">Koordinat otomatis dari profil akun.</p>
                                        </div>
                                    </label>

                                    <label class="cursor-pointer block">
                                        <input type="radio" name="jenis_lokasi" id="lokasi_lainnya" value="lainnya" class="lokasi-radio sr-only"
                                               {{ $jenisLokasi === 'lainnya' ? 'checked' : '' }}>
                                        <div class="lokasi-card h-full border-2 border-[#EBEFEA] rounded-xl p-4 transition-all">
                                            <p class="text-sm font-bold text-[#093A25]">Tentukan Koordinat Manual</p>
                                            <p class="text-xs text-[#414943] mt-1">Gunakan peta interaktif.</p>
                                        </div>
                                    </label>
                                </div>

                                {{-- Peta interaktif --}}
                                <div id="form-lokasi-lainnya" style="{{ $jenisLokasi === 'lainnya' ? '' : 'display: none;' }}" class="pt-4 border-t border-[#EBEFEA]">
                                    <p class="text-xs font-bold text-[#093A25] mb-3">Geser peta untuk menentukan titik lokasi penjemputan limbah:</p>

                                    <div class="relative mb-4">
                                        <div id="map" style="height: 320px;" class="w-full rounded-2xl border border-[#EBEFEA] z-10 overflow-hidden"></div>

                                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-full pointer-events-none z-[400] flex flex-col items-center">
                                            <div class="bg-[#093A25] text-white text-[10px] px-3 py-1 rounded-full shadow-md mb-1 whitespace-nowrap font-bold">Lokasi Muat</div>
                                            <div class="w-4 h-4 bg-green-500 border-2 border-white rounded-full shadow-lg"></div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

                                    <div>
                                        <label for="preview_alamat" class="block text-sm font-bold text-[#093A25] mb-2">Alamat Lengkap Terpilih (Otomatis)</label>
                                        <textarea id="preview_alamat" readonly rows="2" placeholder="Mendeteksi lokasi..."
                                                  class="w-full bg-[#F6FBF5] border-none rounded-xl py-3.5 px-4 text-sm text-gray-500 font-medium outline-none resize-none"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ===== TOMBOL ===== --}}
                        <div class="flex items-center gap-4 pt-4">
                            <a href="{{ route('dashboard') }}" class="px-6 py-4 rounded-xl text-sm font-bold text-gray-500 bg-[#EBEFEA] hover:bg-gray-300 transition-colors">Batal</a>
                            <button type="submit" class="flex-1 bg-[#1A4B36] text-white px-8 py-4 rounded-xl font-bold text-sm text-center shadow-md hover:bg-[#093A25] transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                Publikasikan Informasi Limbah
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ========================================== --}}
                {{-- KOLOM PRATINJAU                             --}}
                {{-- ========================================== --}}
                <div class="w-full lg:w-1/3 flex flex-col gap-6 lg:sticky lg:top-8">

                    <div class="bg-[#F0F5F0] rounded-2xl border border-[#EBEFEA] p-6 shadow-sm">
                        <div class="flex items-center gap-2 text-[#093A25] font-bold text-sm mb-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Panduan Transparansi
                        </div>
                        <p class="text-xs text-[#414943] leading-relaxed">
                            SIRKULA mempermudah recycler menemukan material Anda. Pastikan volume, foto riil, dan lokasi akurat agar proses koordinasi lapangan berjalan tanpa kendala.
                        </p>
                    </div>

                    <div class="bg-white rounded-[1.5rem] border border-[#EBEFEA] shadow-sm p-2 flex flex-col">
                        <div class="flex items-center justify-between px-3 py-3 border-b border-[#EBEFEA] mb-3">
                            <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-[#1A4B36]">Pratinjau Tampilan Recycler</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </div>

                        <div class="px-2">
                            <div class="w-full h-40 bg-[#EBEFEA] rounded-xl overflow-hidden mb-4 relative">
                                <div id="pv-foto-kosong" class="absolute inset-0 flex items-center justify-center text-[10px] font-mono text-gray-400">Belum ada foto</div>
                                <img id="pv-foto" src="" alt="Preview" class="hidden w-full h-full object-cover">

                                <div class="absolute top-2 left-2 bg-white/90 backdrop-blur-sm px-2 py-1 rounded shadow-sm max-w-[65%]">
                                    <span class="block truncate text-[10px] font-bold text-[#093A25]">{{ $namaInstansi }}</span>
                                </div>
                                <div class="absolute top-2 right-2 bg-[#C6ECCD] text-[#093A25] px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-widest">Tersedia</div>
                            </div>

                            <div class="flex justify-between items-center mb-2 gap-2">
                                <span id="pv-kategori" class="text-[9px] font-mono font-bold uppercase tracking-widest text-gray-400 truncate">{{ $kategoriPertama->nama_kategori ?? 'Kategori' }}</span>
                                <span class="shrink-0 bg-[#F0F5F0] px-2 py-1 rounded text-xs font-bold font-mono text-[#093A25]">
                                    <span id="pv-volume">0</span> <span id="pv-satuan">KG</span>
                                </span>
                            </div>
                            <h3 id="pv-judul" class="text-base font-bold text-[#093A25] mb-2 truncate">Nama material Anda...</h3>
                            <p id="pv-deskripsi" class="text-[10px] text-gray-500 mb-4 line-clamp-2">Deskripsi material akan tampil di sini.</p>

                            <div class="bg-[#204E3A] text-white text-xs font-bold py-3 rounded-lg text-center shadow-sm mb-4">Hubungi via WhatsApp</div>
                        </div>
                    </div>

                    <div class="bg-white rounded-[1.5rem] border border-[#EBEFEA] shadow-sm p-6">
                        <h4 class="text-sm font-bold text-[#093A25] mb-4">Tips Agar Cepat Diambil:</h4>
                        <ul class="space-y-4 text-xs text-[#414943]">
                            <li class="flex gap-2 items-start">
                                <svg class="w-4 h-4 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Foto asli tanpa editan membuat material lebih dipercaya recycler.
                            </li>
                            <li class="flex gap-2 items-start">
                                <svg class="w-4 h-4 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Sebutkan apakah lokasi dapat dilalui truk engkel atau hanya pikap kecil.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const $ = (id) => document.getElementById(id);

            // ------------------------------------------------------------
            // Pratinjau langsung (tanpa Alpine.js)
            // ------------------------------------------------------------
            const satuanLabel = { kg: 'KG', ton: 'Ton', liter: 'Liter', pcs: 'Unit' };

            function syncPreview() {
                $('pv-judul').textContent = $('judul').value.trim() || 'Nama material Anda...';
                $('pv-volume').textContent = $('estimasi_volume').value || '0';
                $('pv-satuan').textContent = satuanLabel[$('satuan').value] || $('satuan').value;
                $('pv-deskripsi').textContent = $('deskripsi').value.trim() || 'Deskripsi material akan tampil di sini.';
            }

            ['judul', 'estimasi_volume', 'satuan', 'deskripsi'].forEach(function (id) {
                $(id).addEventListener('input', syncPreview);
                $(id).addEventListener('change', syncPreview);
            });
            syncPreview();

            // ------------------------------------------------------------
            // Kategori (gaya kartu terpilih + pratinjau)
            // ------------------------------------------------------------
            const kategoriRadios = document.querySelectorAll('.kategori-radio');

            function syncKategori() {
                kategoriRadios.forEach(function (radio) {
                    const card = radio.parentElement.querySelector('.kategori-card');
                    const dot = card.querySelector('.kategori-dot');
                    const inner = card.querySelector('.kategori-dot-inner');
                    const on = radio.checked;

                    card.classList.toggle('bg-white', on);
                    card.classList.toggle('border-[#093A25]', on);
                    card.classList.toggle('shadow-sm', on);
                    card.classList.toggle('bg-[#F6FBF5]', !on);
                    card.classList.toggle('border-transparent', !on);

                    dot.classList.toggle('bg-[#093A25]', on);
                    dot.classList.toggle('border-[#093A25]', on);
                    dot.classList.toggle('border-gray-300', !on);
                    inner.classList.toggle('opacity-100', on);
                    inner.classList.toggle('opacity-0', !on);

                    if (on && radio.dataset.nama) {
                        $('pv-kategori').textContent = radio.dataset.nama;
                    }
                });
            }
            kategoriRadios.forEach(function (r) { r.addEventListener('change', syncKategori); });
            syncKategori();

            // ------------------------------------------------------------
            // Foto
            // ------------------------------------------------------------
            $('foto_limbah').addEventListener('change', function () {
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
            });

            // ------------------------------------------------------------
            // Lokasi & peta
            // ------------------------------------------------------------
            const radioUtama = $('lokasi_utama');
            const radioLainnya = $('lokasi_lainnya');
            const formLainnya = $('form-lokasi-lainnya');
            const lokasiRadios = document.querySelectorAll('.lokasi-radio');

            let map = null;
            let geocodeTimeout = null;

            function syncLokasiCard() {
                lokasiRadios.forEach(function (radio) {
                    const card = radio.parentElement.querySelector('.lokasi-card');
                    card.classList.toggle('bg-[#F0F5F0]', radio.checked);
                    card.classList.toggle('border-[#093A25]', radio.checked);
                    card.classList.toggle('border-[#EBEFEA]', !radio.checked);
                });
            }

            function updateLocationData(center) {
                const lat = center.lat.toFixed(6);
                const lng = center.lng.toFixed(6);

                $('latitude').value = lat;
                $('longitude').value = lng;

                const preview = $('preview_alamat');
                preview.value = lat + ', ' + lng;

                clearTimeout(geocodeTimeout);
                geocodeTimeout = setTimeout(function () {
                    fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng)
                        .then(function (res) {
                            if (!res.ok) throw new Error('Gagal mengambil alamat.');
                            return res.json();
                        })
                        .then(function (data) {
                            if (data && data.display_name) preview.value = data.display_name;
                        })
                        .catch(function (err) { console.error('Gagal mendapatkan alamat:', err); });
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
                map.on('moveend', function () { updateLocationData(map.getCenter()); });
            }

            function showMap() {
                formLainnya.style.display = 'block';
                setTimeout(function () {
                    const lat = parseFloat($('latitude').value) || -8.6705;
                    const lng = parseFloat($('longitude').value) || 115.2126;
                    initMap(lat, lng);
                }, 200);
            }

            radioUtama.addEventListener('change', function () {
                if (this.checked) formLainnya.style.display = 'none';
                syncLokasiCard();
            });

            radioLainnya.addEventListener('change', function () {
                if (this.checked) showMap();
                syncLokasiCard();
            });

            syncLokasiCard();
            if (radioLainnya.checked) showMap();
        });
    </script>
@endsection