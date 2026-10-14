@extends('layouts.app')

@section('content')
    @php
        $user = Auth::user();

        // ------------------------------------------------------------------
        // Kategori (pengaman jika tabel kategori terlanjur memiliki nama ganda)
        // ------------------------------------------------------------------
        $kategoriAsli        = $kategori;
        $kategori            = $kategoriAsli->sortBy('id')->unique('nama_kategori')->values();
        $kategoriDipilihAsli = old('kategori_id', $laporan->kategori_id);
        $namaDipilih         = $kategoriAsli->first(fn ($k) => (string) $k->id === (string) $kategoriDipilihAsli)?->nama_kategori;
        $kategoriDipilih     = $kategori->firstWhere('nama_kategori', $namaDipilih)?->id ?? $kategoriDipilihAsli;
        $namaKategoriAwal    = $kategori->first(fn ($k) => (string) $k->id === (string) $kategoriDipilih)?->nama_kategori ?? 'Kategori';

        // ------------------------------------------------------------------
        // Status
        // ------------------------------------------------------------------
        $st           = strtolower((string) $laporan->status);
        $final        = $st === 'sudah diambil';
        $bisaUbahStatus = in_array($st, ['tersedia', 'sedang diproses'], true);
        $statusPublik   = in_array($st, ['tersedia', 'sedang diproses', 'sudah diambil'], true);

        [$pvStatusClass, $pvStatusLabel] = match ($st) {
            'tersedia'        => ['bg-[#C6ECCD] text-[#093A25]', 'Tersedia'],
            'sedang diproses' => ['bg-[#FDECE8] text-[#C4411E]', 'Sedang Diproses'],
            'sudah diambil'   => ['bg-[#EBEFEA] text-gray-600', 'Sudah Diambil'],
            'ditolak'         => ['bg-red-100 text-red-700', 'Ditolak'],
            default           => ['bg-yellow-100 text-yellow-800', 'Menunggu'],
        };

        // ------------------------------------------------------------------
        // Data turunan
        // ------------------------------------------------------------------
        $sku          = 'LMB-' . ($laporan->created_at ?? now())->format('Y') . '-' . str_pad($laporan->id, 3, '0', STR_PAD_LEFT);
        $terakhirUbah = $laporan->updated_at?->locale('id')->diffForHumans();
        $tglCatat     = $laporan->tanggal_pencatatan
            ? \Carbon\Carbon::parse($laporan->tanggal_pencatatan)->format('Y-m-d')
            : now()->format('Y-m-d');

        $satuanAwal = strtolower((string) old('satuan', $laporan->satuan));
        if ($satuanAwal === 'unit') {
            $satuanAwal = 'pcs';
        }
        $satuanLabel = ['kg' => 'KG', 'ton' => 'Ton', 'liter' => 'Liter', 'pcs' => 'Unit'];

        $namaInstansi = $user->nama_instansi ?: $user->name;
        $wilayah      = $user->kelurahan ?: null;

        // Lokasi: bila koordinat laporan berbeda dari profil, anggap lokasi kustom
        $punyaKoordinat = $laporan->latitude !== null && $laporan->longitude !== null
            && $laporan->latitude !== '' && $laporan->longitude !== '';
        $samaProfil = $punyaKoordinat
            && $user->latitude !== null && $user->longitude !== null
            && abs((float) $laporan->latitude - (float) $user->latitude) < 0.00005
            && abs((float) $laporan->longitude - (float) $user->longitude) < 0.00005;

        $jenisLokasi = old('jenis_lokasi', ($punyaKoordinat && ! $samaProfil) ? 'lainnya' : 'utama');
        $latAwal     = old('latitude', $laporan->latitude);
        $lngAwal     = old('longitude', $laporan->longitude);

        $fotoUrl = $laporan->foto_limbah ? asset('storage/' . $laporan->foto_limbah) : null;

        // Gaya input & label mengikuti desain: latar abu-hijau lembut tanpa border, label medium
        $inputClass = 'w-full bg-[#F0F3EF] border border-transparent rounded-xl py-3 px-4 text-[15px] text-[#18221C] placeholder-[#9BA69E] focus:bg-white focus:ring-2 focus:ring-[#14452F] focus:border-transparent outline-none transition-all';
        $labelClass = 'block text-[15px] font-medium text-[#18221C] mb-2';
    @endphp

    {{-- Font: Inter (teks) + IBM Plex Mono (label teknis), sesuai desain --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        .edit-limbah,
        .edit-limbah input,
        .edit-limbah select,
        .edit-limbah textarea,
        .edit-limbah button {
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif;
        }
        #map-pin, #map-pin-shadow {
            position: absolute; left: 50%; top: 150px; /* setengah tinggi peta (300px) */
            pointer-events: none; z-index: 20;
        }
        #map-pin { transform: translate(-50%, -100%); filter: drop-shadow(0 2px 3px rgba(15,56,36,.25)); transition: transform .15s ease; }
        #map-pin-shadow { width: 12px; height: 5px; border-radius: 50%; background: rgba(15,56,36,.28); transform: translate(-50%, -50%); transition: transform .15s ease, opacity .15s ease; }
        #map-pin.is-lifting { transform: translate(-50%, calc(-100% - 8px)); }
        #map-pin-shadow.is-lifting { transform: translate(-50%, -50%) scale(.7); opacity: .6; }
        .edit-limbah h1,
        .edit-limbah h2,
        .edit-limbah h3 { letter-spacing: -0.02em; }
        .edit-limbah .font-mono {
            font-family: 'IBM Plex Mono', ui-monospace, SFMono-Regular, Menlo, Consolas, monospace !important;
            letter-spacing: 0;
        }
    </style>

    <div class="edit-limbah bg-[#F6FBF5] min-h-screen pb-24 pt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ========================================== --}}
            {{-- HEADER                                     --}}
            {{-- ========================================== --}}
            <div class="flex items-center justify-between gap-4 mb-5">
                <nav class="flex flex-wrap items-center gap-1.5 text-xs font-mono text-[#5D6A62]" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard') }}" class="hover:text-[#14452F] hover:underline">Dashboard Mitra</a>
                    <span>/</span>
                    <a href="{{ route('dashboard') }}#tabel-riwayat" class="hover:text-[#14452F] hover:underline">Limbah Fasilitas</a>
                    <span>/</span>
                    <span class="font-semibold text-[#18221C]">Edit: {{ \Illuminate\Support\Str::limit($laporan->judul, 28) }}</span>
                    <span class="bg-[#E4EBE4] text-[#5D6A62] rounded px-1.5 py-0.5 font-mono text-[10px]">{{ $sku }}</span>
                </nav>

                <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex shrink-0 items-center gap-2 text-[15px] font-medium text-[#18221C] hover:text-[#14452F] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Dashboard Mitra
                </a>
            </div>

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-6 mb-8 border-b border-[#E1E9E1]">
                <div>
                    <p class="flex items-center gap-2 text-[10px] font-mono font-medium uppercase tracking-widest text-[#14452F] mb-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#14452F]"></span>
                        Formulir Pemutakhiran Neraca Sirkular
                    </p>
                    <h1 class="text-3xl md:text-4xl font-medium text-[#111C16] tracking-tight">Edit Informasi Limbah</h1>
                    <p class="mt-2 text-[15px] text-[#4A5650] max-w-xl leading-relaxed">
                        Perbarui spesifikasi material, lokasi pemuatan, atau status ketersediaan. Perubahan akan langsung tersinkronisasi ke katalog publik SIRKULA.
                    </p>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <span class="rounded-full bg-[#E4EBE4] px-3.5 py-1.5 font-mono text-xs font-medium text-[#5D6A62]">ID Log: #{{ $sku }}</span>
                    @if ($statusPublik)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-[#C6ECCD] px-3.5 py-1.5 font-mono text-xs font-medium text-[#093A25]">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#093A25]"></span> Terindeks Publik
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-yellow-100 px-3.5 py-1.5 font-mono text-xs font-medium text-yellow-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-600"></span> {{ $pvStatusLabel }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="mb-6 bg-green-50 text-green-800 p-4 rounded-2xl border border-green-200 text-sm font-medium">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-2xl border border-red-200 text-sm font-medium">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-6 bg-red-50 text-red-600 p-4 rounded-2xl border border-red-100 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-8 items-start">

                {{-- ========================================== --}}
                {{-- KOLOM FORM                                 --}}
                {{-- ========================================== --}}
                <div class="w-full lg:w-[62%] min-w-0">

                    <form id="form-edit" action="{{ route('laporan.update', $laporan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Tanggal pencatatan dikelola sistem; dikirim ulang agar validasi controller tetap terpenuhi --}}
                        <input type="hidden" name="tanggal_pencatatan" value="{{ old('tanggal_pencatatan', $tglCatat) }}">

                        {{-- ===== 01 INFORMASI MATERIAL ===== --}}
                        <section class="bg-white rounded-3xl border border-[#EDF1EC] shadow-[0_4px_20px_rgba(0,0,0,0.02)] p-6 md:p-8">
                            <div class="flex items-center justify-between gap-3 pb-4 mb-6 border-b border-[#EFEFEE]">
                                <h2 class="flex items-center gap-2.5 text-lg font-medium text-[#111C16]">
                                    <span class="rounded-md bg-[#14452F] px-2 py-0.5 font-mono text-xs font-medium text-white">01</span>
                                    Informasi Material Limbah
                                </h2>
                                <span class="font-mono text-xs text-[#5D6A62]">Terakhir diubah: {{ $terakhirUbah ?? '-' }}</span>
                            </div>

                            <div class="space-y-6">

                                {{-- Nama --}}
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label for="judul" class="{{ $labelClass }} !mb-0">Nama Residu / Material <span class="text-red-500">*</span></label>
                                        <span class="font-mono text-xs text-[#5D6A62]"><span id="judul-count">0</span> / 80 Karakter</span>
                                    </div>
                                    <input type="text" id="judul" name="judul" value="{{ old('judul', $laporan->judul) }}" maxlength="80" required class="{{ $inputClass }}">
                                </div>

                                {{-- Kategori --}}
                                <div>
                                    <label class="{{ $labelClass }}">Kategori Aliran Limbah <span class="text-red-500">*</span></label>
                                    <div class="flex flex-wrap gap-2.5">
                                        @foreach ($kategori as $kat)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="kategori_id" value="{{ $kat->id }}" required
                                                       data-nama="{{ $kat->nama_kategori }}"
                                                       {{ (string) $kategoriDipilih === (string) $kat->id ? 'checked' : '' }}
                                                       class="kategori-radio sr-only">
                                                <span class="kategori-pill inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-[13px] font-medium transition-all bg-[#F0F3EF] text-[#3B4740] border border-transparent">
                                                    <span class="kategori-dot w-3.5 h-3.5 rounded-full border border-gray-400 bg-white"></span>
                                                    {{ $kat->nama_kategori ?? 'Kategori ' . $kat->id }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Foto --}}
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="{{ $labelClass }} !mb-0">Foto Verifikasi Kondisi Material</label>
                                        <span class="font-mono text-xs text-[#14452F]">{{ $fotoUrl ? '1 Terunggah' : 'Belum ada foto' }} (Maks. 1)</span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="relative aspect-[4/3] overflow-hidden rounded-2xl bg-[#F0F3EF]">
                                            @if ($fotoUrl)
                                                <img id="foto-tile" src="{{ $fotoUrl }}" alt="Foto {{ $laporan->judul }}" class="h-full w-full object-cover">
                                            @else
                                                <img id="foto-tile" src="" alt="Preview" class="hidden h-full w-full object-cover">
                                                <div id="foto-tile-kosong" class="absolute inset-0 flex items-center justify-center font-mono text-xs text-[#5D6A62]">Tidak ada foto</div>
                                            @endif
                                            <span id="foto-badge" class="absolute left-2.5 bottom-2.5 rounded-md bg-[#14452F] px-2 py-1 font-mono text-[10px] font-medium uppercase tracking-widest text-white">Utama</span>
                                        </div>

                                        <label for="foto_limbah"
                                               class="aspect-[4/3] flex flex-col items-center justify-center gap-2.5 rounded-2xl border border-dashed border-[#C9D4C7] bg-[#F0F3EF] text-center font-mono text-xs font-medium text-[#3B4740] hover:bg-[#E7ECE6] transition-all cursor-pointer px-4">
                                            <svg class="w-6 h-6 text-[#3B4740]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2zM12 8v4m-2-2h4"/></svg>
                                            <span id="foto-label">Unggah Foto Baru</span>
                                        </label>
                                        <input type="file" id="foto_limbah" name="foto_limbah" accept="image/png,image/jpeg,image/jpg" class="sr-only">
                                    </div>
                                    <p class="mt-2 text-xs text-[#5D6A62]">Biarkan kosong jika tidak ingin mengganti foto. Format JPG atau PNG, maksimal 2MB.</p>
                                </div>

                                {{-- Volume & satuan --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="estimasi_volume" class="{{ $labelClass }}">Jumlah Estimasi Batch <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="number" id="estimasi_volume" name="estimasi_volume" step="any" min="0" required
                                                   value="{{ old('estimasi_volume', $laporan->estimasi_volume) }}" class="{{ $inputClass }} pr-20 font-mono font-semibold">
                                            <span id="chip-satuan" class="absolute right-3.5 top-1/2 -translate-y-1/2 rounded-md bg-[#E4EBE4] px-2 py-1 font-mono text-[11px] font-medium text-[#5D6A62] pointer-events-none">{{ $satuanLabel[$satuanAwal] ?? strtoupper($satuanAwal) }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="satuan" class="{{ $labelClass }}">Satuan Pengukuran <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select id="satuan" name="satuan" required class="{{ $inputClass }} appearance-none cursor-pointer pr-10">
                                                <option value="kg" {{ $satuanAwal === 'kg' ? 'selected' : '' }}>Kilogram (KG)</option>
                                                <option value="ton" {{ $satuanAwal === 'ton' ? 'selected' : '' }}>Ton</option>
                                                <option value="liter" {{ $satuanAwal === 'liter' ? 'selected' : '' }}>Liter</option>
                                                <option value="pcs" {{ $satuanAwal === 'pcs' ? 'selected' : '' }}>Unit / Buah</option>
                                            </select>
                                            <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Deskripsi --}}
                                <div>
                                    <label for="deskripsi" class="{{ $labelClass }}">Spesifikasi &amp; Kondisi Residu <span class="text-red-500">*</span></label>
                                    <textarea id="deskripsi" name="deskripsi" rows="5" required class="{{ $inputClass }} resize-none leading-relaxed"
                                              placeholder="Jelaskan kondisi fisik limbah, asal limbah, dan cara penyimpanannya saat ini...">{{ old('deskripsi', $laporan->deskripsi) }}</textarea>
                                </div>
                            </div>
                        </section>

                        {{-- ===== 02 LOKASI ===== --}}
                        <section class="bg-white rounded-3xl border border-[#EDF1EC] shadow-[0_4px_20px_rgba(0,0,0,0.02)] p-6 md:p-8">
                            <div class="flex items-center justify-between gap-3 pb-4 mb-6 border-b border-[#EFEFEE]">
                                <h2 class="flex items-center gap-2.5 text-lg font-medium text-[#111C16]">
                                    <span class="rounded-md bg-[#14452F] px-2 py-0.5 font-mono text-xs font-medium text-white">02</span>
                                    Lokasi &amp; Titik Pengambilan
                                </h2>
                                <span class="inline-flex items-center gap-1.5 font-mono text-xs text-[#5D6A62]">
                                    <svg class="w-3.5 h-3.5 text-[#14452F]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.686 7-11a7 7 0 10-14 0c0 5.314 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                                    Titik Koordinat
                                </span>
                            </div>

                            <div class="space-y-5">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label class="cursor-pointer block">
                                        <input type="radio" name="jenis_lokasi" id="lokasi_utama" value="utama" class="lokasi-radio sr-only" {{ $jenisLokasi === 'utama' ? 'checked' : '' }}>
                                        <div class="lokasi-card h-full rounded-2xl border-2 border-transparent p-4 transition-all bg-[#F0F3EF]">
                                            <p class="text-[15px] font-medium text-[#111C16]">Gunakan Alamat Utama Profil</p>
                                            <p class="mt-1 text-xs text-[#4A5650]">Koordinat otomatis dari profil akun Anda.</p>
                                        </div>
                                    </label>

                                    <label class="cursor-pointer block">
                                        <input type="radio" name="jenis_lokasi" id="lokasi_lainnya" value="lainnya" class="lokasi-radio sr-only" {{ $jenisLokasi === 'lainnya' ? 'checked' : '' }}>
                                        <div class="lokasi-card h-full rounded-2xl border-2 border-transparent p-4 transition-all bg-[#F0F3EF]">
                                            <p class="text-[15px] font-medium text-[#111C16]">Tentukan Titik Lain di Peta</p>
                                            <p class="mt-1 text-xs text-[#4A5650]">Geser peta agar pin berada tepat di titik pengambilan.</p>
                                        </div>
                                    </label>
                                </div>

                                {{-- Alamat profil (referensi) --}}
                                <div id="info-lokasi-utama" class="rounded-2xl bg-[#F0F3EF] p-4 text-sm text-[#18221C]" style="{{ $jenisLokasi === 'utama' ? '' : 'display: none;' }}">
                                    <p class="font-mono text-[10px] uppercase tracking-widest text-[#5D6A62] mb-1">Alamat Utama Profil</p>
                                    @if ($user->alamat_lengkap || $user->kelurahan)
                                        <p class="leading-relaxed">{{ $user->alamat_lengkap ?: $user->kelurahan }}@if ($user->kode_pos), {{ $user->kode_pos }}@endif</p>
                                    @else
                                        <p class="text-[#5D6A62]">Alamat profil belum diisi.</p>
                                    @endif
                                    <a href="{{ route('profile.edit') }}" class="mt-2 inline-block text-xs font-medium text-[#14452F] hover:underline">Ubah alamat di Pengaturan Profil &rarr;</a>
                                </div>

                                {{-- Peta --}}
                                <div id="form-lokasi-lainnya" style="{{ $jenisLokasi === 'lainnya' ? '' : 'display: none;' }}">
                                    <div class="relative">
                                        <div id="map" style="height: 300px;" class="w-full rounded-2xl border border-[#E5ECE4] z-10 overflow-hidden"></div>

                                        {{-- Pin tetap di tengah peta; peta yang digeser --}}
                                        <div id="map-pin-shadow" aria-hidden="true"></div>
                                        <div id="map-pin" aria-hidden="true">
                                            <svg width="40" height="46" viewBox="0 0 40 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M20 46C20 46 4 30 4 18a16 16 0 1132 0c0 12-16 28-16 28z" fill="#14452F" stroke="#FFFFFF" stroke-width="2.5" stroke-linejoin="round"/>
                                                <circle cx="20" cy="18" r="6.5" fill="#FFFFFF"/>
                                                <circle cx="20" cy="18" r="2.8" fill="#14452F"/>
                                            </svg>
                                        </div>
                                    </div>

                                    <input type="hidden" name="latitude" id="latitude" value="{{ $latAwal }}">
                                    <input type="hidden" name="longitude" id="longitude" value="{{ $lngAwal }}">

                                    <div class="mt-3.5">
                                        <label for="preview_alamat" class="block font-mono text-[10px] uppercase tracking-widest text-[#5D6A62] mb-1">Alamat Titik Terpilih (Otomatis)</label>
                                        <textarea id="preview_alamat" readonly rows="2" placeholder="Mendeteksi lokasi..."
                                                  class="w-full rounded-xl bg-[#F0F3EF] border border-transparent px-4 py-3 text-sm text-[#3B4740] outline-none resize-none"></textarea>
                                        <p class="mt-1 font-mono text-xs text-[#5D6A62]">Koordinat: <span id="koordinat-teks" class="font-semibold text-[#111C16]">-</span></p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        {{-- ===== 03 KONTAK ===== --}}
                        <section class="bg-white rounded-3xl border border-[#EDF1EC] shadow-[0_4px_20px_rgba(0,0,0,0.02)] p-6 md:p-8">
                            <div class="flex items-center justify-between gap-3 pb-4 mb-6 border-b border-[#EFEFEE]">
                                <h2 class="flex items-center gap-2.5 text-lg font-medium text-[#111C16]">
                                    <span class="rounded-md bg-[#14452F] px-2 py-0.5 font-mono text-xs font-medium text-white">03</span>
                                    Kontak PIC Pengambilan
                                </h2>
                            </div>

                            <label class="{{ $labelClass }}">Nomor WhatsApp Koordinator Lapangan</label>
                            <div class="flex flex-wrap items-center gap-3">
                                <div class="flex items-center gap-2.5 rounded-xl bg-[#F0F3EF] px-4 py-3 text-sm font-mono text-[#18221C] min-w-[16rem]">
                                    <svg class="w-4 h-4 text-[#3B4740]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.68l1.5 4.5a1 1 0 01-.5 1.2l-2.26 1.13a11 11 0 005.5 5.5l1.13-2.26a1 1 0 011.2-.5l4.5 1.5a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C9.72 21 3 14.28 3 6V5z"/></svg>
                                    {{ $user->no_whatsapp ?: 'Belum diisi' }}
                                </div>
                                <a href="{{ route('profile.edit') }}" class="text-xs font-medium text-[#14452F] hover:underline">Ubah di Pengaturan Profil &rarr;</a>
                            </div>
                            <p class="mt-3 text-xs italic text-[#5D6A62]">*Transaksi dan kesepakatan logistik dikoordinasikan langsung melalui kanal ini. Nomor mengikuti data profil akun Anda.</p>
                        </section>

                        {{-- ===== 04 STATUS ===== --}}
                        <section class="bg-white rounded-3xl border border-[#EDF1EC] shadow-[0_4px_20px_rgba(0,0,0,0.02)] p-6 md:p-8">
                            <div class="flex items-center justify-between gap-3 pb-4 mb-6 border-b border-[#EFEFEE]">
                                <h2 class="flex items-center gap-2.5 text-lg font-medium text-[#111C16]">
                                    <span class="rounded-md bg-[#14452F] px-2 py-0.5 font-mono text-xs font-medium text-white">04</span>
                                    Status Ketersediaan Listing
                                </h2>
                            </div>

                            @php
                                $opsiStatus = [
                                    'tersedia' => [
                                        'Tersedia',
                                        'Tampil di katalog publik, terbuka menerima penawaran mitra daur ulang.',
                                        'text-[#18221C]',
                                    ],
                                    'sedang diproses' => [
                                        'Sedang Diproses',
                                        'Kemitraan sedang berjalan, menunggu jadwal penjemputan armada.',
                                        'text-[#D98A1F]',
                                    ],
                                    'sudah diambil' => [
                                        'Sudah Diambil',
                                        'Batch telah diangkut. Status ini final dan tersimpan di rekap sirkulasi Anda.',
                                        'text-[#6B756E]',
                                    ],
                                ];
                            @endphp

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                                @foreach ($opsiStatus as $nilai => [$judulOpsi, $keterangan, $warnaJudul])
                                    @php $aktif = $st === $nilai; @endphp
                                    <button type="submit" form="form-status" name="status" value="{{ $nilai }}"
                                            {{ (! $bisaUbahStatus || $aktif) ? 'disabled' : '' }}
                                            @if ($nilai === 'sudah diambil')
                                                onclick="return confirm('Status &quot;Sudah Diambil&quot; bersifat final dan tidak dapat diubah lagi. Lanjutkan?');"
                                            @endif
                                            class="text-left rounded-xl border-2 p-4 transition-all
                                                   {{ $aktif ? 'bg-[#E8F5EA] border-[#14452F]' : 'bg-[#F0F3EF] border-transparent' }}
                                                   {{ (! $bisaUbahStatus || $aktif) ? 'cursor-default' : 'hover:border-[#14452F] hover:bg-white' }}
                                                   {{ (! $bisaUbahStatus && ! $aktif) ? 'opacity-60' : '' }}">
                                        <span class="flex items-center justify-between gap-2">
                                            <span class="text-[15px] font-medium {{ $warnaJudul }}">{{ $judulOpsi }}</span>
                                            <span class="w-4 h-4 shrink-0 rounded-full border flex items-center justify-center {{ $aktif ? 'border-[#14452F]' : 'border-gray-400 bg-white' }}">
                                                @if ($aktif)
                                                    <span class="w-2 h-2 rounded-full bg-[#14452F]"></span>
                                                @endif
                                            </span>
                                        </span>
                                        <span class="mt-2 block text-xs leading-relaxed text-[#4A5650] font-normal">{{ $keterangan }}</span>
                                    </button>
                                @endforeach
                            </div>

                            @if ($final)
                                <p class="mt-4 rounded-xl bg-[#F0F3EF] p-3.5 text-xs text-[#4A5650]">Batch ini sudah diambil, sehingga statusnya tidak dapat diubah lagi.</p>
                            @elseif (! $bisaUbahStatus)
                                <p class="mt-4 rounded-xl bg-[#F0F3EF] p-3.5 text-xs text-[#4A5650]">Status baru bisa diubah setelah laporan disetujui admin. Saat ini: <strong>{{ $pvStatusLabel }}</strong>.</p>
                            @else
                                <p class="mt-4 text-xs text-[#5D6A62]">Mengubah status langsung tersimpan dan terpisah dari formulir di atas. Simpan dulu perubahan formulir bila ada.</p>
                            @endif
                        </section>

                        {{-- ===== TOMBOL ===== --}}
                        <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                            <button type="submit" form="form-hapus"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus informasi limbah ini? Tindakan ini tidak dapat dibatalkan.');"
                                    class="inline-flex items-center gap-2 rounded-xl bg-[#FFD9D4] px-5 py-3 text-[15px] font-medium text-[#8A1F10] hover:bg-[#FFC9C1] transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M9 7V4h6v3M6 7l1 13h10l1-13M10 11v6M14 11v6"/></svg>
                                Hapus Informasi Ini
                            </button>

                            <div class="flex items-center gap-3 ml-auto">
                                <a href="{{ route('dashboard') }}" class="rounded-xl bg-[#E4EBE4] px-6 py-3 text-[15px] font-medium text-[#4A5650] hover:bg-[#D8E2D8] transition-colors">Batal</a>
                                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-[#14452F] hover:bg-[#0C3B26] text-white px-6 py-3 text-[15px] font-medium transition-colors">
                                    Simpan Perubahan Limbah
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Form terpisah untuk ubah status & hapus --}}
                    <form id="form-status" action="{{ route('laporan.ubahStatus', $laporan->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('PATCH')
                    </form>
                    <form id="form-hapus" action="{{ route('laporan.destroy', $laporan->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>

                {{-- ========================================== --}}
                {{-- KOLOM PRATINJAU                            --}}
                {{-- ========================================== --}}
                <aside class="w-full lg:w-[38%] lg:sticky lg:top-6 space-y-5 min-w-0">

                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <p class="flex items-center gap-2 font-mono text-[10px] font-medium uppercase tracking-widest text-[#14452F]">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#14452F]"></span> Pratinjau Tampilan Recycler
                            </p>
                            <span class="font-mono text-xs text-[#5D6A62]">Live Render</span>
                        </div>

                        <div class="overflow-hidden rounded-3xl bg-white border border-[#EDF1EC] shadow-md">
                            <div class="relative h-56 bg-[#EBEFEA]">
                                @if ($fotoUrl)
                                    <img id="pv-foto" src="{{ $fotoUrl }}" alt="Pratinjau" class="h-full w-full object-cover">
                                    <div id="pv-foto-kosong" class="hidden absolute inset-0 items-center justify-center font-mono text-xs text-[#5D6A62]">Belum ada foto</div>
                                @else
                                    <img id="pv-foto" src="" alt="Pratinjau" class="hidden h-full w-full object-cover">
                                    <div id="pv-foto-kosong" class="absolute inset-0 flex items-center justify-center font-mono text-xs text-[#5D6A62]">Belum ada foto</div>
                                @endif

                                <span class="absolute left-3.5 top-3.5 inline-flex items-center rounded-full px-3 py-1 font-mono text-xs font-medium {{ $pvStatusClass }}">{{ $pvStatusLabel }}</span>
                                <span class="absolute right-3.5 bottom-3.5 rounded-lg bg-[#0F3824]/90 backdrop-blur px-3 py-1.5 font-mono text-base font-medium text-white">
                                    <span id="pv-volume">0</span> <span id="pv-satuan" class="text-[10px] font-normal text-gray-300">KG</span>
                                </span>
                            </div>

                            <div class="p-6">
                                <div class="flex items-center justify-between gap-3 font-mono text-xs text-[#5D6A62] mb-2">
                                    <span class="truncate">{{ $namaInstansi }}@if ($wilayah) &bull; {{ $wilayah }}@endif</span>
                                    <span id="pv-kategori" class="shrink-0 tracking-wide font-medium text-[#14452F]">{{ $namaKategoriAwal }}</span>
                                </div>
                                <h3 id="pv-judul" class="text-xl font-medium leading-snug text-[#111C16]">{{ old('judul', $laporan->judul) }}</h3>
                                <p id="pv-deskripsi" class="mt-2.5 text-[13px] leading-relaxed text-[#4A5650] line-clamp-3">{{ old('deskripsi', $laporan->deskripsi) }}</p>

                                <div class="mt-6 rounded-xl bg-[#14452F] py-3.5 text-center text-[15px] font-medium text-white">Hubungi Pengelola Fasilitas</div>
                                <p class="mt-3 text-center text-xs text-[#5D6A62]">Transaksi dan pengambilan dilakukan langsung dengan mitra.</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl bg-[#EAF2E9] p-5 border border-[#D5E4D3]">
                        <p class="flex items-center gap-2 text-[15px] font-medium text-[#111C16]">
                            <svg class="w-5 h-5 text-[#14452F]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 12.5l2.5 2.5L16 9.5"/></svg>
                            Protokol Transparansi SIRKULA
                        </p>
                        <p class="mt-2 text-[13px] leading-relaxed text-[#4A5650]">
                            Data material yang akurat mempercepat konfirmasi recycler. Pastikan berat tertera merefleksikan estimasi terkini, dan perbarui status begitu pengambilan berjalan.
                        </p>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const $ = (id) => document.getElementById(id);
            const satuanLabel = { kg: 'KG', ton: 'Ton', liter: 'Liter', pcs: 'Unit' };

            // ------------------------------------------------------------
            // Pratinjau & penghitung karakter
            // ------------------------------------------------------------
            function syncPreview() {
                const judul = $('judul').value.trim();
                $('judul-count').textContent = $('judul').value.length;
                $('pv-judul').textContent = judul || 'Nama material Anda...';
                $('pv-volume').textContent = $('estimasi_volume').value || '0';

                const satuan = satuanLabel[$('satuan').value] || $('satuan').value;
                $('pv-satuan').textContent = satuan;
                $('chip-satuan').textContent = satuan;

                $('pv-deskripsi').textContent = $('deskripsi').value.trim() || 'Deskripsi material akan tampil di sini.';
            }

            ['judul', 'estimasi_volume', 'satuan', 'deskripsi'].forEach(function (id) {
                $(id).addEventListener('input', syncPreview);
                $(id).addEventListener('change', syncPreview);
            });
            syncPreview();

            // ------------------------------------------------------------
            // Kategori (pill)
            // ------------------------------------------------------------
            const kategoriRadios = document.querySelectorAll('.kategori-radio');

            function syncKategori() {
                kategoriRadios.forEach(function (radio) {
                    const pill = radio.parentElement.querySelector('.kategori-pill');
                    const dot = pill.querySelector('.kategori-dot');
                    const on = radio.checked;

                    pill.classList.toggle('bg-[#14452F]', on);
                    pill.classList.toggle('text-white', on);
                    pill.classList.toggle('bg-[#F0F3EF]', !on);
                    pill.classList.toggle('text-[#3B4740]', !on);

                    dot.classList.toggle('border-white', on);
                    dot.classList.toggle('bg-[#14452F]', on);
                    dot.classList.toggle('border-gray-400', !on);
                    dot.classList.toggle('bg-white', !on);

                    if (on && radio.dataset.nama) $('pv-kategori').textContent = radio.dataset.nama;
                });
            }
            kategoriRadios.forEach(function (r) { r.addEventListener('change', syncKategori); });
            syncKategori();

            // ------------------------------------------------------------
            // Foto baru
            // ------------------------------------------------------------
            $('foto_limbah').addEventListener('change', function () {
                const file = this.files && this.files[0];
                if (!file) return;

                const url = URL.createObjectURL(file);

                const tile = $('foto-tile');
                tile.src = url;
                tile.classList.remove('hidden');
                if ($('foto-tile-kosong')) $('foto-tile-kosong').classList.add('hidden');

                const pv = $('pv-foto');
                pv.src = url;
                pv.classList.remove('hidden');
                $('pv-foto-kosong').classList.add('hidden');
                $('pv-foto-kosong').classList.remove('flex');

                $('foto-label').textContent = file.name.length > 22 ? file.name.slice(0, 20) + '…' : file.name;
            });

            // ------------------------------------------------------------
            // Lokasi & peta
            // ------------------------------------------------------------
            const radioUtama = $('lokasi_utama');
            const radioLainnya = $('lokasi_lainnya');
            const formLainnya = $('form-lokasi-lainnya');
            const infoUtama = $('info-lokasi-utama');
            const lokasiRadios = document.querySelectorAll('.lokasi-radio');

            let map = null;
            let geocodeTimeout = null;

            function syncLokasiCard() {
                lokasiRadios.forEach(function (radio) {
                    const card = radio.parentElement.querySelector('.lokasi-card');
                    card.classList.toggle('bg-[#F0F3EF]', !radio.checked);
                    card.classList.toggle('bg-[#E8F5EA]', radio.checked);
                    card.classList.toggle('border-[#14452F]', radio.checked);
                    card.classList.toggle('border-transparent', !radio.checked);
                });
            }

            function setLocation(lat, lng) {
                const la = lat.toFixed(6);
                const lo = lng.toFixed(6);

                $('latitude').value = la;
                $('longitude').value = lo;
                $('koordinat-teks').textContent = la + ', ' + lo;

                const preview = $('preview_alamat');
                preview.value = la + ', ' + lo;

                clearTimeout(geocodeTimeout);
                geocodeTimeout = setTimeout(function () {
                    fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + la + '&lon=' + lo)
                        .then(function (res) {
                            if (!res.ok) throw new Error('Gagal mengambil alamat.');
                            return res.json();
                        })
                        .then(function (data) {
                            if (data && data.display_name) preview.value = data.display_name;
                        })
                        .catch(function (err) { console.error('Gagal mendapatkan alamat:', err); });
                }, 700);
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

                setLocation(lat, lng);

                const pin = $('map-pin');
                const pinShadow = $('map-pin-shadow');

                // Pin terkunci di tengah; titik koordinat mengikuti pusat peta saat digeser
                map.on('movestart', function () {
                    pin.classList.add('is-lifting');
                    pinShadow.classList.add('is-lifting');
                });

                map.on('moveend', function () {
                    pin.classList.remove('is-lifting');
                    pinShadow.classList.remove('is-lifting');
                    const c = map.getCenter();
                    setLocation(c.lat, c.lng);
                });

                // Klik peta memindahkan peta sehingga titik yang diklik berada di bawah pin
                map.on('click', function (e) {
                    map.panTo(e.latlng);
                });
            }

            function showMap() {
                formLainnya.style.display = 'block';
                infoUtama.style.display = 'none';
                setTimeout(function () {
                    const lat = parseFloat($('latitude').value) || -8.6705;
                    const lng = parseFloat($('longitude').value) || 115.2126;
                    initMap(lat, lng);
                }, 200);
            }

            radioUtama.addEventListener('change', function () {
                if (this.checked) {
                    formLainnya.style.display = 'none';
                    infoUtama.style.display = 'block';
                }
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