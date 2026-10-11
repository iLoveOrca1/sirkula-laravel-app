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
        $st             = strtolower((string) $laporan->status);
        $final          = $st === 'sudah diambil';
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

        $inputClass = 'w-full bg-[#F0F5F0] border border-transparent rounded-xl py-3 px-4 text-sm text-[#2b332e] focus:bg-white focus:ring-2 focus:ring-[#1A4B36] focus:border-transparent outline-none transition-all';
        $labelClass = 'block text-sm font-bold text-[#093A25] mb-2';
    @endphp

    <div class="bg-[#F6FBF5] min-h-screen pb-24 pt-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ========================================== --}}
            {{-- HEADER                                      --}}
            {{-- ========================================== --}}
            <div class="flex items-center justify-between gap-4 mb-5">
                <nav class="flex flex-wrap items-center gap-1.5 text-[11px] font-mono text-gray-500" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard') }}" class="hover:text-[#093A25] hover:underline">Dashboard Mitra</a>
                    <span>/</span>
                    <a href="{{ route('dashboard') }}#tabel-riwayat" class="hover:text-[#093A25] hover:underline">Limbah Saya</a>
                    <span>/</span>
                    <span class="font-bold text-[#093A25]">Edit: {{ \Illuminate\Support\Str::limit($laporan->judul, 28) }}</span>
                    <span class="bg-[#E4EBE4] text-gray-600 rounded px-1.5 py-0.5 text-[10px]">{{ $sku }}</span>
                </nav>

                <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex shrink-0 items-center gap-2 text-sm font-medium text-gray-600 hover:text-[#093A25] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Dashboard Mitra
                </a>
            </div>

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-6 mb-8 border-b border-[#E1E9E1]">
                <div>
                    <p class="flex items-center gap-2 text-[10px] font-mono font-bold uppercase tracking-widest text-[#093A25] mb-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#093A25]"></span>
                        Formulir Pemutakhiran Neraca Sirkular
                    </p>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-[#093A25] tracking-tight">Edit Informasi Limbah</h1>
                    <p class="mt-2 text-sm text-[#414943] max-w-xl leading-relaxed">
                        Perbarui spesifikasi material, lokasi pemuatan, atau status ketersediaan. Perubahan akan langsung tersinkronisasi ke katalog publik SIRKULA.
                    </p>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <span class="rounded-md bg-[#E4EBE4] px-3 py-1.5 font-mono text-[10px] font-semibold text-gray-600">ID Log: #{{ $sku }}</span>
                    @if ($statusPublik)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-[#C6ECCD] px-3 py-1.5 font-mono text-[10px] font-bold text-[#093A25]">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#093A25]"></span> Terindeks Publik
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-yellow-100 px-3 py-1.5 font-mono text-[10px] font-bold text-yellow-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-600"></span> {{ $pvStatusLabel }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="mb-6 bg-green-50 text-green-800 p-4 rounded-xl border border-green-200 text-sm font-medium">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-xl border border-red-200 text-sm font-medium">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-6 bg-red-50 text-red-600 p-4 rounded-xl border border-red-100 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-8 items-start">

                {{-- ========================================== --}}
                {{-- KOLOM FORM                                  --}}
                {{-- ========================================== --}}
                <div class="w-full lg:w-[62%] min-w-0">

                    <form id="form-edit" action="{{ route('laporan.update', $laporan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Tanggal pencatatan dikelola sistem; dikirim ulang agar validasi controller tetap terpenuhi --}}
                        <input type="hidden" name="tanggal_pencatatan" value="{{ old('tanggal_pencatatan', $tglCatat) }}">

                        {{-- ===== 01 INFORMASI MATERIAL ===== --}}
                        <section class="bg-white rounded-2xl border border-[#EBEFEA] shadow-sm p-6 md:p-8">
                            <div class="flex items-center justify-between gap-3 pb-4 mb-6 border-b border-[#EBEFEA]">
                                <h2 class="flex items-center gap-2.5 text-lg font-bold text-[#093A25]">
                                    <span class="rounded-md bg-[#1A4B36] px-2 py-0.5 font-mono text-xs font-bold text-white">01</span>
                                    Informasi Material Limbah
                                </h2>
                                <span class="font-mono text-[10px] font-semibold text-gray-500">Terakhir diubah: {{ $terakhirUbah ?? '-' }}</span>
                            </div>

                            <div class="space-y-6">

                                {{-- Nama --}}
                                <div>
                                    <div class="flex items-center justify-between">
                                        <label for="judul" class="{{ $labelClass }}">Nama Residu / Material <span class="text-red-500">*</span></label>
                                        <span class="font-mono text-[10px] text-gray-500 mb-2"><span id="judul-count">0</span> / 80 Karakter</span>
                                    </div>
                                    <input type="text" id="judul" name="judul" value="{{ old('judul', $laporan->judul) }}" maxlength="80" required class="{{ $inputClass }}">
                                </div>

                                {{-- Kategori --}}
                                <div>
                                    <label class="{{ $labelClass }}">Kategori Aliran Limbah <span class="text-red-500">*</span></label>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($kategori as $kat)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="kategori_id" value="{{ $kat->id }}" required
                                                       data-nama="{{ $kat->nama_kategori }}"
                                                       {{ (string) $kategoriDipilih === (string) $kat->id ? 'checked' : '' }}
                                                       class="kategori-radio sr-only">
                                                <span class="kategori-pill inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-medium transition-colors bg-[#F0F5F0] text-[#2b332e]">
                                                    <span class="kategori-dot w-3.5 h-3.5 rounded-full border-2 border-gray-400 bg-white"></span>
                                                    {{ $kat->nama_kategori ?? 'Kategori ' . $kat->id }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Foto --}}
                                <div>
                                    <div class="flex items-center justify-between">
                                        <label class="{{ $labelClass }}">Foto Verifikasi Kondisi Material</label>
                                        <span class="font-mono text-[10px] text-gray-500 mb-2">{{ $fotoUrl ? '1 Terunggah' : 'Belum ada foto' }} (Maks. 1)</span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3 sm:gap-4">
                                        <div class="relative aspect-[4/3] overflow-hidden rounded-2xl bg-[#EBEFEA] border border-[#E1E9E1]">
                                            @if ($fotoUrl)
                                                <img id="foto-tile" src="{{ $fotoUrl }}" alt="Foto {{ $laporan->judul }}" class="h-full w-full object-cover">
                                            @else
                                                <img id="foto-tile" src="" alt="Preview" class="hidden h-full w-full object-cover">
                                                <div id="foto-tile-kosong" class="absolute inset-0 flex items-center justify-center text-[10px] font-mono text-gray-400">Tidak ada foto</div>
                                            @endif
                                            <span id="foto-badge" class="absolute left-2 bottom-2 rounded bg-[#093A25] px-2 py-1 font-mono text-[9px] font-bold uppercase tracking-widest text-white">Utama</span>
                                        </div>

                                        <label for="foto_limbah"
                                               class="aspect-[4/3] flex flex-col items-center justify-center gap-2 rounded-2xl border-2 border-dashed border-[#C9D6C9] bg-[#F6FBF5] text-center text-sm font-semibold text-[#093A25] hover:bg-[#F0F5F0] transition-colors cursor-pointer px-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2zM12 8v4m-2-2h4"/></svg>
                                            <span id="foto-label">Unggah Foto Baru</span>
                                        </label>
                                        <input type="file" id="foto_limbah" name="foto_limbah" accept="image/png,image/jpeg,image/jpg" class="sr-only">
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500">Biarkan kosong jika tidak ingin mengganti foto. Format JPG atau PNG, maksimal 2MB.</p>
                                </div>

                                {{-- Volume & satuan --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label for="estimasi_volume" class="{{ $labelClass }}">Jumlah Estimasi Batch <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="number" id="estimasi_volume" name="estimasi_volume" step="any" min="0" required
                                                   value="{{ old('estimasi_volume', $laporan->estimasi_volume) }}" class="{{ $inputClass }} pr-20 font-mono font-bold">
                                            <span id="chip-satuan" class="absolute right-3 top-1/2 -translate-y-1/2 rounded bg-[#E1E9E1] px-2 py-1 font-mono text-[10px] font-bold text-gray-600 pointer-events-none">{{ $satuanLabel[$satuanAwal] ?? strtoupper($satuanAwal) }}</span>
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
                        <section class="bg-white rounded-2xl border border-[#EBEFEA] shadow-sm p-6 md:p-8">
                            <div class="flex items-center justify-between gap-3 pb-4 mb-6 border-b border-[#EBEFEA]">
                                <h2 class="flex items-center gap-2.5 text-lg font-bold text-[#093A25]">
                                    <span class="rounded-md bg-[#1A4B36] px-2 py-0.5 font-mono text-xs font-bold text-white">02</span>
                                    Lokasi &amp; Titik Pengambilan
                                </h2>
                                <span class="inline-flex items-center gap-1.5 font-mono text-[10px] font-semibold text-gray-500">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.686 7-11a7 7 0 10-14 0c0 5.314 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                                    Titik Koordinat
                                </span>
                            </div>

                            <div class="space-y-5">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label class="cursor-pointer block">
                                        <input type="radio" name="jenis_lokasi" id="lokasi_utama" value="utama" class="lokasi-radio sr-only" {{ $jenisLokasi === 'utama' ? 'checked' : '' }}>
                                        <div class="lokasi-card h-full rounded-xl border-2 border-[#EBEFEA] p-4 transition-all">
                                            <p class="text-sm font-bold text-[#093A25]">Gunakan Alamat Utama Profil</p>
                                            <p class="mt-1 text-xs text-[#414943]">Koordinat otomatis dari profil akun Anda.</p>
                                        </div>
                                    </label>

                                    <label class="cursor-pointer block">
                                        <input type="radio" name="jenis_lokasi" id="lokasi_lainnya" value="lainnya" class="lokasi-radio sr-only" {{ $jenisLokasi === 'lainnya' ? 'checked' : '' }}>
                                        <div class="lokasi-card h-full rounded-xl border-2 border-[#EBEFEA] p-4 transition-all">
                                            <p class="text-sm font-bold text-[#093A25]">Tentukan Titik Lain di Peta</p>
                                            <p class="mt-1 text-xs text-[#414943]">Klik peta atau geser pin untuk memindahkan titik.</p>
                                        </div>
                                    </label>
                                </div>

                                {{-- Alamat profil (referensi) --}}
                                <div id="info-lokasi-utama" class="rounded-xl bg-[#F0F5F0] px-4 py-3 text-sm text-[#2b332e]" style="{{ $jenisLokasi === 'utama' ? '' : 'display: none;' }}">
                                    <p class="font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-1">Alamat Utama Profil</p>
                                    @if ($user->alamat_lengkap || $user->kelurahan)
                                        <p class="leading-relaxed">{{ $user->alamat_lengkap ?: $user->kelurahan }}@if ($user->kode_pos), {{ $user->kode_pos }}@endif</p>
                                    @else
                                        <p class="text-gray-600">Alamat profil belum diisi.</p>
                                    @endif
                                    <a href="{{ route('profile.edit') }}" class="mt-1.5 inline-block text-xs font-semibold text-[#1A4B36] hover:underline">Ubah alamat di Pengaturan Profil &rarr;</a>
                                </div>

                                {{-- Peta --}}
                                <div id="form-lokasi-lainnya" style="{{ $jenisLokasi === 'lainnya' ? '' : 'display: none;' }}">
                                    <div id="map" style="height: 300px;" class="w-full rounded-2xl border border-[#E1E9E1] z-10 overflow-hidden"></div>

                                    <input type="hidden" name="latitude" id="latitude" value="{{ $latAwal }}">
                                    <input type="hidden" name="longitude" id="longitude" value="{{ $lngAwal }}">

                                    <div class="mt-3">
                                        <label for="preview_alamat" class="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-1">Alamat Titik Terpilih (Otomatis)</label>
                                        <textarea id="preview_alamat" readonly rows="2" placeholder="Mendeteksi lokasi..."
                                                  class="w-full rounded-xl bg-[#F0F5F0] px-4 py-3 text-sm text-gray-600 outline-none resize-none"></textarea>
                                        <p class="mt-1 font-mono text-[10px] text-gray-500">Koordinat: <span id="koordinat-teks">-</span></p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        {{-- ===== 03 KONTAK ===== --}}
                        <section class="bg-white rounded-2xl border border-[#EBEFEA] shadow-sm p-6 md:p-8">
                            <div class="flex items-center justify-between gap-3 pb-4 mb-6 border-b border-[#EBEFEA]">
                                <h2 class="flex items-center gap-2.5 text-lg font-bold text-[#093A25]">
                                    <span class="rounded-md bg-[#1A4B36] px-2 py-0.5 font-mono text-xs font-bold text-white">03</span>
                                    Kontak PIC Pengambilan
                                </h2>
                            </div>

                            <label class="{{ $labelClass }}">Nomor WhatsApp Koordinator Lapangan</label>
                            <div class="flex flex-wrap items-center gap-3">
                                <div class="flex items-center gap-2.5 rounded-xl bg-[#F0F5F0] px-4 py-3 text-sm font-mono text-[#2b332e] min-w-[14rem]">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.68l1.5 4.5a1 1 0 01-.5 1.2l-2.26 1.13a11 11 0 005.5 5.5l1.13-2.26a1 1 0 011.2-.5l4.5 1.5a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C9.72 21 3 14.28 3 6V5z"/></svg>
                                    {{ $user->no_whatsapp ?: 'Belum diisi' }}
                                </div>
                                <a href="{{ route('profile.edit') }}" class="text-xs font-semibold text-[#1A4B36] hover:underline">Ubah di Pengaturan Profil &rarr;</a>
                            </div>
                            <p class="mt-3 text-xs italic text-gray-500">*Transaksi dan kesepakatan logistik dikoordinasikan langsung melalui kanal ini. Nomor mengikuti data profil akun Anda.</p>
                        </section>

                        {{-- ===== 04 STATUS ===== --}}
                        <section class="bg-white rounded-2xl border border-[#EBEFEA] shadow-sm p-6 md:p-8">
                            <div class="flex items-center justify-between gap-3 pb-4 mb-6 border-b border-[#EBEFEA]">
                                <h2 class="flex items-center gap-2.5 text-lg font-bold text-[#093A25]">
                                    <span class="rounded-md bg-[#1A4B36] px-2 py-0.5 font-mono text-xs font-bold text-white">04</span>
                                    Status Ketersediaan Listing
                                </h2>
                            </div>

                            @php
                                $opsiStatus = [
                                    'tersedia' => [
                                        'Tersedia',
                                        'Tampil di katalog publik, terbuka menerima penawaran mitra daur ulang.',
                                        'text-[#093A25]',
                                    ],
                                    'sedang diproses' => [
                                        'Sedang Diproses',
                                        'Kemitraan sedang berjalan, menunggu jadwal penjemputan armada.',
                                        'text-[#C77D14]',
                                    ],
                                    'sudah diambil' => [
                                        'Sudah Diambil',
                                        'Batch telah diangkut. Status ini final dan tersimpan di rekap sirkulasi Anda.',
                                        'text-gray-600',
                                    ],
                                ];
                            @endphp

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                @foreach ($opsiStatus as $nilai => [$judulOpsi, $keterangan, $warnaJudul])
                                    @php $aktif = $st === $nilai; @endphp
                                    <button type="submit" form="form-status" name="status" value="{{ $nilai }}"
                                            {{ (! $bisaUbahStatus || $aktif) ? 'disabled' : '' }}
                                            @if ($nilai === 'sudah diambil')
                                                onclick="return confirm('Status &quot;Sudah Diambil&quot; bersifat final dan tidak dapat diubah lagi. Lanjutkan?');"
                                            @endif
                                            class="text-left rounded-xl border-2 p-4 transition-all
                                                   {{ $aktif ? 'bg-[#E8F5EA] border-[#1A4B36]' : 'bg-[#F0F5F0] border-transparent' }}
                                                   {{ (! $bisaUbahStatus || $aktif) ? 'cursor-default' : 'hover:border-[#9DB8A5] hover:bg-white' }}
                                                   {{ (! $bisaUbahStatus && ! $aktif) ? 'opacity-60' : '' }}">
                                        <span class="flex items-center justify-between gap-2">
                                            <span class="text-sm font-bold {{ $warnaJudul }}">{{ $judulOpsi }}</span>
                                            <span class="w-4 h-4 shrink-0 rounded-full border-2 flex items-center justify-center {{ $aktif ? 'border-[#1A4B36]' : 'border-gray-300 bg-white' }}">
                                                @if ($aktif)
                                                    <span class="w-2 h-2 rounded-full bg-[#1A4B36]"></span>
                                                @endif
                                            </span>
                                        </span>
                                        <span class="mt-2 block text-xs leading-relaxed text-[#414943]">{{ $keterangan }}</span>
                                    </button>
                                @endforeach
                            </div>

                            @if ($final)
                                <p class="mt-4 rounded-lg bg-[#F0F5F0] px-4 py-3 text-xs text-gray-600">Batch ini sudah diambil, sehingga statusnya tidak dapat diubah lagi.</p>
                            @elseif (! $bisaUbahStatus)
                                <p class="mt-4 rounded-lg bg-[#F0F5F0] px-4 py-3 text-xs text-gray-600">Status baru bisa diubah setelah laporan disetujui admin. Saat ini: <strong>{{ $pvStatusLabel }}</strong>.</p>
                            @else
                                <p class="mt-4 text-xs text-gray-500">Mengubah status langsung tersimpan dan terpisah dari formulir di atas. Simpan dulu perubahan formulir bila ada.</p>
                            @endif
                        </section>

                        {{-- ===== TOMBOL ===== --}}
                        <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                            <button type="submit" form="form-hapus"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus informasi limbah ini? Tindakan ini tidak dapat dibatalkan.');"
                                    class="inline-flex items-center gap-2 rounded-xl bg-[#FFD9D4] px-5 py-3 text-sm font-bold text-[#8A1F10] hover:bg-[#FFC9C1] transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M9 7V4h6v3M6 7l1 13h10l1-13M10 11v6M14 11v6"/></svg>
                                Hapus Informasi Ini
                            </button>

                            <div class="flex items-center gap-3 ml-auto">
                                <a href="{{ route('dashboard') }}" class="rounded-xl bg-[#E4EBE4] px-6 py-3 text-sm font-bold text-gray-600 hover:bg-[#D8E2D8] transition-colors">Batal</a>
                                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-[#1A4B36] px-6 py-3 text-sm font-bold text-white shadow-md hover:bg-[#093A25] transition-colors">
                                    Simpan Perubahan Limbah
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Form terpisah untuk ubah status & hapus (dipicu tombol di atas lewat atribut form="...") --}}
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
                {{-- KOLOM PRATINJAU                             --}}
                {{-- ========================================== --}}
                <aside class="w-full lg:w-[38%] lg:sticky lg:top-6 space-y-5 min-w-0">

                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <p class="flex items-center gap-2 font-mono text-[10px] font-bold uppercase tracking-widest text-[#093A25]">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#093A25]"></span> Pratinjau Tampilan Recycler
                            </p>
                            <span class="font-mono text-[10px] text-gray-500">Live Render</span>
                        </div>

                        <div class="overflow-hidden rounded-3xl bg-white border border-[#EBEFEA] shadow-md">
                            <div class="relative h-52 bg-[#EBEFEA]">
                                @if ($fotoUrl)
                                    <img id="pv-foto" src="{{ $fotoUrl }}" alt="Pratinjau" class="h-full w-full object-cover">
                                    <div id="pv-foto-kosong" class="hidden absolute inset-0 items-center justify-center text-[10px] font-mono text-gray-400">Belum ada foto</div>
                                @else
                                    <img id="pv-foto" src="" alt="Pratinjau" class="hidden h-full w-full object-cover">
                                    <div id="pv-foto-kosong" class="absolute inset-0 flex items-center justify-center text-[10px] font-mono text-gray-400">Belum ada foto</div>
                                @endif

                                <span class="absolute left-3 top-3 inline-flex items-center rounded-full px-2.5 py-1 font-mono text-[10px] font-bold shadow-sm {{ $pvStatusClass }}">{{ $pvStatusLabel }}</span>
                                <span class="absolute right-3 bottom-3 rounded-lg bg-[#0B2E20]/85 px-3 py-1.5 font-mono text-base font-bold text-white">
                                    <span id="pv-volume">0</span> <span id="pv-satuan" class="text-[10px] font-semibold text-gray-300">KG</span>
                                </span>
                            </div>

                            <div class="p-5">
                                <div class="flex items-center justify-between gap-3 font-mono text-[10px] text-gray-500">
                                    <span class="truncate">{{ $namaInstansi }}@if ($wilayah) &bull; {{ $wilayah }}@endif</span>
                                    <span id="pv-kategori" class="shrink-0 uppercase tracking-wide">{{ $namaKategoriAwal }}</span>
                                </div>
                                <h3 id="pv-judul" class="mt-2 text-xl font-bold leading-tight text-[#093A25]">{{ old('judul', $laporan->judul) }}</h3>
                                <p id="pv-deskripsi" class="mt-2 text-xs leading-relaxed text-gray-600 line-clamp-3">{{ old('deskripsi', $laporan->deskripsi) }}</p>

                                <div class="mt-5 rounded-xl bg-[#2F5E47] py-3 text-center text-sm font-bold text-white">Hubungi Pengelola Fasilitas</div>
                                <p class="mt-3 text-center text-[10px] text-gray-500">Transaksi dan pengambilan dilakukan langsung dengan mitra.</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-[#E8EFE8] p-5">
                        <p class="flex items-center gap-2 font-bold text-[#093A25]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 12.5l2.5 2.5L16 9.5"/></svg>
                            Protokol Transparansi SIRKULA
                        </p>
                        <p class="mt-2 text-xs leading-relaxed text-[#414943]">
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

                    pill.classList.toggle('bg-[#1A4B36]', on);
                    pill.classList.toggle('text-white', on);
                    pill.classList.toggle('bg-[#F0F5F0]', !on);
                    pill.classList.toggle('text-[#2b332e]', !on);

                    dot.classList.toggle('border-white', on);
                    dot.classList.toggle('bg-[#1A4B36]', on);
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
            let marker = null;
            let geocodeTimeout = null;

            function syncLokasiCard() {
                lokasiRadios.forEach(function (radio) {
                    const card = radio.parentElement.querySelector('.lokasi-card');
                    card.classList.toggle('bg-[#F0F5F0]', radio.checked);
                    card.classList.toggle('border-[#1A4B36]', radio.checked);
                    card.classList.toggle('border-[#EBEFEA]', !radio.checked);
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

                marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                setLocation(lat, lng);

                marker.on('dragend', function () {
                    const p = marker.getLatLng();
                    setLocation(p.lat, p.lng);
                });

                map.on('click', function (e) {
                    marker.setLatLng(e.latlng);
                    setLocation(e.latlng.lat, e.latlng.lng);
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