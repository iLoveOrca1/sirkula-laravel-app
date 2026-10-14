@extends('layouts.app')

@section('content')
@php
    $user    = auth()->user();
    $isMitra = $user->role === 'mitra';

    $totalLaporan = 0;
    $totalSelesai = 0;
    $totalKg      = 0;

    if ($isMitra) {
        $laporanUser  = \App\Models\LaporanLimbah::where('mitra_id', $user->id);
        $totalLaporan = (clone $laporanUser)->count();
        $totalSelesai = (clone $laporanUser)->whereRaw('LOWER(status) = ?', ['sudah diambil'])->count();

        $totalKg = (clone $laporanUser)
            ->whereRaw('LOWER(status) = ?', ['sudah diambil'])
            ->get(['estimasi_volume', 'satuan'])
            ->sum(function ($row) {
                $vol = (float) $row->estimasi_volume;
                return match (strtolower((string) $row->satuan)) {
                    'ton'   => $vol * 1000,
                    'kg'    => $vol,
                    default => 0,
                };
            });
    }

    $namaTampil = ($isMitra && ! empty($user->nama_instansi)) ? $user->nama_instansi : $user->name;
    $inisial    = strtoupper(mb_substr($namaTampil, 0, 1));
    $latAwal    = old('latitude', $user->latitude);
    $lngAwal    = old('longitude', $user->longitude);
    $bukaModal  = $errors->has('password');
@endphp

{{-- Font sesuai Figma: Plus Jakarta Sans (judul/label), Inter (isian/teks), JetBrains Mono (angka & label kecil) --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* Token warna & font — ubah di sini kalau ada yang perlu disesuaikan lagi dengan Figma */
    .sk-profile {
        --sk-bg:         #F7FAF6;
        --sk-surface:    #FFFFFF;
        --sk-border:     #E4EAE5;
        --sk-primary:    #1F4D3A;
        --sk-primary-hv: #173D2E;
        --sk-heading:    #0F3D2A;
        --sk-text:       #1A1F1C;
        --sk-muted:      #5E6B63;
        --sk-faint:      #8A958E;
        --sk-accent:     #E0643C;
        --sk-rust:       #7A2E12;
        --sk-badge-bg:   #CDEED3;
        --sk-badge-tx:   #14532D;
        --sk-soft:       #EDF3EE;
        --sk-danger:     #DC2626;

        --sk-font-head:  'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        --sk-font-body:  'Inter', ui-sans-serif, system-ui, sans-serif;
        --sk-font-mono:  'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, monospace;

        background: var(--sk-bg);
        font-family: var(--sk-font-body);
        color: var(--sk-text);
    }
    .sk-profile .sk-head  { font-family: var(--sk-font-head); }
    .sk-profile .sk-mono  { font-family: var(--sk-font-mono); }

    .sk-profile .sk-card {
        background: var(--sk-surface);
        border: 1px solid var(--sk-border);
        border-radius: 16px;
        box-shadow: 0 1px 2px rgba(15, 61, 42, .04);
    }
    .sk-profile .sk-title {
        font-family: var(--sk-font-head);
        font-size: 17px;
        font-weight: 600;
        letter-spacing: -0.01em;
        color: var(--sk-heading);
    }
    .sk-profile .sk-sub   { font-size: 12px; color: var(--sk-muted); }
    .sk-profile .sk-label {
        display: block;
        margin-bottom: 6px;
        font-family: var(--sk-font-head);
        font-size: 13px;
        font-weight: 500;
        color: var(--sk-text);
    }
    .sk-profile .sk-label .req { color: var(--sk-danger); }
    .sk-profile .sk-input {
        width: 100%;
        padding: 10px 14px;
        background: var(--sk-surface);
        border: 1px solid var(--sk-border);
        border-radius: 12px;
        font-family: var(--sk-font-body);
        font-size: 14px;
        color: var(--sk-text);
        transition: border-color .15s, box-shadow .15s;
    }
    .sk-profile .sk-input::placeholder { color: var(--sk-faint); }
    .sk-profile .sk-input:focus {
        outline: none;
        border-color: var(--sk-primary);
        box-shadow: 0 0 0 3px rgba(31, 77, 58, .12);
    }
    .sk-profile .sk-btn-primary {
        background: var(--sk-primary);
        color: #fff;
        font-family: var(--sk-font-head);
        font-weight: 600;
        box-shadow: 0 1px 2px rgba(15, 61, 42, .2);
    }
    .sk-profile .sk-btn-primary:hover { background: var(--sk-primary-hv); }
    .sk-profile .sk-btn-soft {
        background: var(--sk-soft);
        color: var(--sk-heading);
        font-family: var(--sk-font-head);
        font-weight: 600;
    }
    .sk-profile .sk-btn-soft:hover { background: #E0EBE3; }
    .sk-profile .sk-meta-label {
        font-family: var(--sk-font-mono);
        font-size: 10px;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--sk-faint);
    }
    .sk-profile .sk-divider { border-color: var(--sk-border); }
</style>

<div class="sk-profile min-h-screen">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <p class="sk-mono text-[11px] tracking-wider uppercase mb-2" style="color: var(--sk-faint);">
                {{ $isMitra ? 'PORTAL MITRA' : 'PORTAL AKUN' }} / <span class="font-bold" style="color: var(--sk-heading);">PROFIL AKUN</span>
            </p>
            <h2 class="sk-head text-[32px] leading-tight font-bold tracking-tight" style="color: var(--sk-heading); letter-spacing: -0.03em;">{{ $isMitra ? 'Profil Akun Mitra' : 'Profil Akun' }}</h2>
            <p class="text-sm mt-1" style="color: var(--sk-muted);">
                Perbarui rincian operasional, kontak penanggung jawab, dan keamanan akun Anda.
            </p>
        </div>

        <div>
            <button
                form="profile-form"
                type="submit"
                class="sk-btn-primary inline-flex items-center justify-center gap-2 text-sm px-6 py-3 rounded-xl transition"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Simpan Perubahan
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 font-medium text-sm flex items-center gap-2">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-xl border border-red-200 text-sm">
            <p class="font-semibold mb-1">Periksa kembali isian Anda:</p>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="profile-form" action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- SIDEBAR KIRI --}}
            <div class="lg:col-span-4 space-y-6">
                <div class="sk-card p-6 text-center">
                    <div class="sk-head mx-auto mb-4 w-28 h-28 rounded-full text-white flex items-center justify-center text-4xl font-bold border-4 shadow-inner" style="background: var(--sk-primary); border-color: var(--sk-bg);">
                        {{ $inisial }}
                    </div>

                    <h3 class="sk-head text-lg font-bold" style="color: var(--sk-text); letter-spacing: -0.01em;">{{ $namaTampil }}</h3>
                    <p class="sk-mono text-[11px] mt-0.5" style="color: var(--sk-faint);">
                        {{ $isMitra ? 'ID MITRA' : 'ID PENGGUNA' }}: {{ $isMitra ? 'MTS-' : 'USR-' }}{{ str_pad((string) $user->id, 4, '0', STR_PAD_LEFT) }}
                    </p>

                    <div class="sk-mono mt-4 inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold tracking-wider rounded-full" style="background: var(--sk-badge-bg); color: var(--sk-badge-tx);">
                        <span class="w-1.5 h-1.5 rounded-full" style="background: var(--sk-badge-tx);"></span>
                        AKUN AKTIF
                    </div>

                    <hr class="sk-divider my-6">

                    <div class="text-left space-y-4">
                        <div>
                            <p class="sk-meta-label">Peran</p>
                            <p class="sk-head text-sm font-medium mt-1" style="color: var(--sk-text);">{{ ucfirst($user->role) }}</p>
                        </div>
                        <div>
                            <p class="sk-meta-label">Wilayah</p>
                            <p class="sk-head text-sm font-medium mt-1" style="color: var(--sk-text);">{{ $user->kelurahan ?: 'Belum diisi' }}</p>
                        </div>
                        <div>
                            <p class="sk-meta-label">Tanggal Gabung</p>
                            <p class="sk-head text-sm font-medium mt-1" style="color: var(--sk-text);">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KANAN --}}
            <div class="lg:col-span-8 space-y-6">

                @if ($isMitra)
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sk-card p-5">
                            <p class="text-xs" style="color: var(--sk-muted);">Total Laporan Limbah</p>
                            <p class="sk-mono text-[32px] leading-none font-bold mt-2" style="color: var(--sk-heading);">{{ $totalLaporan }} <span class="sk-head text-sm font-normal" style="color: var(--sk-muted);">Laporan</span></p>
                        </div>
                        <div class="sk-card p-5">
                            <p class="text-xs" style="color: var(--sk-muted);">Sudah Diambil</p>
                            <p class="sk-mono text-[32px] leading-none font-bold mt-2" style="color: var(--sk-heading);">{{ $totalSelesai }} <span class="sk-head text-sm font-normal" style="color: var(--sk-muted);">Batch</span></p>
                        </div>
                        <div class="sk-card p-5">
                            <p class="text-xs" style="color: var(--sk-muted);">Limbah Tersirkulasi</p>
                            <p class="sk-mono text-[32px] leading-none font-bold mt-2" style="color: var(--sk-rust);">{{ number_format($totalKg, 0, ',', '.') }} <span class="sk-head text-sm font-normal" style="color: var(--sk-muted);">KG</span></p>
                        </div>
                    </div>
                @endif

                {{-- 1. Informasi & Lokasi --}}
                <div class="sk-card p-6 sm:p-8">
                    <div class="mb-6">
                        <h3 class="sk-title">{{ $isMitra ? 'Informasi Bisnis & Fasilitas' : 'Informasi Akun' }}</h3>
                        <p class="sk-sub mt-0.5">Detail utama akun dan lokasi fasilitas Anda.</p>
                    </div>

                    @if ($isMitra)
                        <div class="mb-4">
                            <label for="nama_instansi" class="sk-label">Nama Bisnis / Instansi Resmi <span class="req">*</span></label>
                            <input
                                type="text"
                                id="nama_instansi"
                                name="nama_instansi"
                                value="{{ old('nama_instansi', $user->nama_instansi) }}"
                                class="sk-input"
                                required
                            >
                            @error('nama_instansi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="name" class="sk-label">{{ $isMitra ? 'Nama Lengkap PIC' : 'Nama Lengkap' }} <span class="req">*</span></label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                class="sk-input"
                                required
                            >
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="sk-label">Email <span class="req">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5" style="color: var(--sk-muted);">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9 6 9-6"/></svg>
                                </span>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', $user->email) }}"
                                    class="sk-input"
                                    style="padding-left: 40px;"
                                    required
                                >
                            </div>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="no_whatsapp" class="sk-label">Nomor WhatsApp</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5" style="color: var(--sk-muted);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2.3a1 1 0 01.95.68l1.1 3.3a1 1 0 01-.5 1.2l-1.6.8a11 11 0 005.4 5.4l.8-1.6a1 1 0 011.2-.5l3.3 1.1a1 1 0 01.68.95V19a2 2 0 01-2 2A16 16 0 013 5z"/></svg>
                            </span>
                            <input
                                type="text"
                                id="no_whatsapp"
                                name="no_whatsapp"
                                value="{{ old('no_whatsapp', $user->no_whatsapp) }}"
                                placeholder="+62 812 3456 7890"
                                class="sk-input"
                                style="padding-left: 40px;"
                            >
                        </div>
                        <p class="text-[11px] mt-1" style="color: var(--sk-faint);">Dipakai pembeli untuk menghubungi Anda dari halaman detail limbah.</p>
                        @error('no_whatsapp')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6 pt-6 border-t sk-divider">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
                            <div>
                                <label class="sk-label !mb-0">Lokasi Fasilitas</label>
                                <p class="sk-sub">Geser peta sampai pin berada di titik lokasi, atau gunakan GPS.</p>
                            </div>
                            <button
                                type="button"
                                id="btn-gps"
                                class="sk-btn-soft inline-flex items-center justify-center gap-1.5 text-xs px-3.5 py-2 rounded-xl transition"
                            >
                                📍 Gunakan GPS Saya
                            </button>
                        </div>

                        <div class="relative h-[280px] w-full overflow-hidden rounded-2xl border mb-4" style="border-color: var(--sk-border);">
                            <div id="map-profile" class="absolute inset-0 z-0"></div>

                            <div class="pointer-events-none absolute left-1/2 top-1/2 z-[400] -translate-x-1/2 -translate-y-full text-3xl leading-none drop-shadow">📍</div>

                            <div class="absolute top-3 inset-x-3 z-[400] bg-white/95 backdrop-blur px-3.5 py-2 rounded-xl shadow border text-xs flex items-center justify-between" style="border-color: var(--sk-border); color: var(--sk-text);">
                                <span id="preview-address" class="truncate font-medium">
                                    {{ $user->alamat_lengkap ? '📍 ' . $user->alamat_lengkap : 'Geser peta untuk memilih lokasi' }}
                                </span>
                                <span class="sk-mono text-[10px] font-bold px-2 py-0.5 rounded shrink-0 ml-2" style="background: var(--sk-badge-bg); color: var(--sk-badge-tx);">Peta Aktif</span>
                            </div>
                        </div>

                        <label for="input_alamat" class="sk-label">Alamat Lengkap</label>
                        <textarea
                            name="alamat_lengkap"
                            id="input_alamat"
                            rows="2"
                            placeholder="Jl. Pantai Kuta No. 88, Badung, Bali"
                            class="sk-input"
                        >{{ old('alamat_lengkap', $user->alamat_lengkap) }}</textarea>
                        @error('alamat_lengkap')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label for="kelurahan" class="sk-label">Kelurahan / Wilayah</label>
                                <input
                                    type="text"
                                    id="kelurahan"
                                    name="kelurahan"
                                    value="{{ old('kelurahan', $user->kelurahan) }}"
                                    class="sk-input"
                                >
                                @error('kelurahan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="kode_pos" class="sk-label">Kode Pos</label>
                                <input
                                    type="text"
                                    id="kode_pos"
                                    name="kode_pos"
                                    value="{{ old('kode_pos', $user->kode_pos) }}"
                                    class="sk-input"
                                >
                                @error('kode_pos')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <input type="hidden" id="lat" name="latitude" value="{{ $latAwal }}">
                        <input type="hidden" id="lng" name="longitude" value="{{ $lngAwal }}">
                    </div>
                </div>

                {{-- 2. Keamanan --}}
                <div class="sk-card p-6 sm:p-8">
                    <div class="mb-6">
                        <h3 class="sk-title">Keamanan Akun</h3>
                        <p class="sk-sub mt-0.5">Kelola kata sandi dan sesi akun Anda.</p>
                    </div>

                    <div class="space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b sk-divider">
                            <div>
                                <p class="sk-head text-sm font-semibold" style="color: var(--sk-text);">Kata Sandi Akun</p>
                                <p class="sk-sub">Kosongkan jika tidak ingin mengganti. Sandi baru tersimpan saat Anda menekan "Simpan Perubahan".</p>
                            </div>
                            <button type="button" id="btn-open-password" class="sk-btn-soft px-4 py-2 text-xs rounded-xl transition">
                                Ubah Sandi
                            </button>
                        </div>

                        <div class="flex flex-col gap-3 pt-2">
                            <div>
                                <p class="sk-head text-sm font-semibold" style="color: var(--sk-text);">Keluar dari Akun</p>
                                <p class="sk-sub">Akhiri sesi SIRKULA di perangkat ini.</p>
                            </div>
                            <button
                                type="submit"
                                form="logout-form"
                                class="sk-head w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border text-xs font-semibold rounded-xl transition hover:bg-red-50"
                                style="border-color: var(--sk-danger); color: var(--sk-danger);"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 3h3a2 2 0 012 2v14a2 2 0 01-2 2h-3M10 17l5-5-5-5M15 12H3"/></svg>
                                Keluar dari Akun
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Modal sandi: berada DI DALAM form agar field ikut terkirim --}}
        <div id="password-modal" class="{{ $bukaModal ? '' : 'hidden' }} fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl" style="font-family: var(--sk-font-body);">
                <h3 class="sk-title !text-lg mb-1">Ubah Kata Sandi</h3>
                <p class="sk-sub mb-4">Minimal 8 karakter. Tersimpan bersama perubahan profil lainnya.</p>

                <div class="space-y-4">
                    <div>
                        <label for="password" class="sk-label">Kata Sandi Baru</label>
                        <input type="password" id="password" name="password" autocomplete="new-password" placeholder="Minimal 8 karakter" class="sk-input">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="sk-label">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" placeholder="Ulangi kata sandi baru" class="sk-input">
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" id="btn-batal-password" class="sk-btn-soft px-4 py-2 text-xs rounded-xl">Batal</button>
                    <button type="submit" class="sk-btn-primary px-4 py-2 text-xs rounded-xl">Simpan Sandi</button>
                </div>
            </div>
        </div>
    </form>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
</div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal      = document.getElementById('password-modal');
    var pwInput    = document.getElementById('password');
    var pwConfirm  = document.getElementById('password_confirmation');

    document.getElementById('btn-open-password').addEventListener('click', function () {
        modal.classList.remove('hidden');
        pwInput.focus();
    });
    document.getElementById('btn-batal-password').addEventListener('click', function () {
        pwInput.value = '';
        pwConfirm.value = '';
        modal.classList.add('hidden');
    });

    var latInput     = document.getElementById('lat');
    var lngInput     = document.getElementById('lng');
    var previewText  = document.getElementById('preview-address');
    var alamatInput  = document.getElementById('input_alamat');
    var btnGps       = document.getElementById('btn-gps');

    var hasCoords = latInput.value !== '' && lngInput.value !== '';
    var startLat  = hasCoords ? parseFloat(latInput.value) : -8.6705;
    var startLng  = hasCoords ? parseFloat(lngInput.value) : 115.2126;

    var map = L.map('map-profile', { zoomControl: false }).setView([startLat, startLng], hasCoords ? 16 : 12);
    L.control.zoom({ position: 'bottomright' }).addTo(map);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    // Alamat hanya diganti setelah pengguna benar-benar menggeser peta / memakai GPS,
    // sehingga alamat tersimpan tidak tertimpa saat halaman baru dibuka.
    var userMoved = false;
    var timer = null;

    function reverseGeocode(lat, lng) {
        previewText.textContent = 'Menerjemahkan lokasi ke alamat...';
        var url = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&addressdetails=1&zoom=18&lat=' + lat + '&lon=' + lng;

        fetch(url)
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data && data.display_name) {
                    previewText.textContent = '📍 ' + data.display_name;
                    alamatInput.value = data.display_name;
                } else {
                    previewText.textContent = '📍 Koordinat berhasil disematkan';
                }
            })
            .catch(function () {
                previewText.textContent = '📍 Koordinat berhasil disematkan';
            });
    }

    map.on('dragstart zoomstart', function () { userMoved = true; });

    map.on('move', function () {
        if (! userMoved) { return; }
        var c = map.getCenter();
        latInput.value = c.lat.toFixed(7);
        lngInput.value = c.lng.toFixed(7);
    });

    map.on('moveend', function () {
        if (! userMoved) { return; }
        var c = map.getCenter();
        clearTimeout(timer);
        timer = setTimeout(function () { reverseGeocode(c.lat, c.lng); }, 600);
    });

    btnGps.addEventListener('click', function () {
        if (! navigator.geolocation) {
            alert('Geolocation tidak didukung browser Anda.');
            return;
        }
        btnGps.textContent = 'Mencari GPS...';
        navigator.geolocation.getCurrentPosition(
            function (pos) {
                userMoved = true;
                map.flyTo([pos.coords.latitude, pos.coords.longitude], 16, { duration: 1.2 });
                btnGps.textContent = '📍 Gunakan GPS Saya';
            },
            function () {
                alert('Gagal mendeteksi lokasi GPS.');
                btnGps.textContent = '📍 Gunakan GPS Saya';
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    });

    setTimeout(function () { map.invalidateSize(); }, 200);
});
</script>
@endsection