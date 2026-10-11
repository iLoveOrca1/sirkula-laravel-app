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

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <p class="text-xs font-semibold tracking-wider text-gray-400 uppercase mb-1">
                {{ $isMitra ? 'PORTAL MITRA' : 'PORTAL AKUN' }} / <span class="text-gray-700">PROFIL AKUN</span>
            </p>
            <h2 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $isMitra ? 'Profil Akun Mitra' : 'Profil Akun' }}</h2>
            <p class="text-sm text-gray-500 mt-1">
                Perbarui rincian operasional, kontak penanggung jawab, dan keamanan akun Anda.
            </p>
        </div>

        <div>
            <button
                form="profile-form"
                type="submit"
                class="inline-flex items-center justify-center gap-2 bg-[#0F3D2A] hover:bg-[#144d36] text-white text-sm font-semibold px-6 py-3 rounded-xl transition shadow-sm"
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
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
                    <div class="mx-auto mb-4 w-28 h-28 rounded-full bg-[#0F3D2A] text-white flex items-center justify-center text-4xl font-bold border-4 border-gray-50 shadow-inner">
                        {{ $inisial }}
                    </div>

                    <h3 class="text-lg font-bold text-gray-900">{{ $namaTampil }}</h3>
                    <p class="text-xs font-mono text-gray-400 mt-0.5">
                        {{ $isMitra ? 'ID MITRA' : 'ID PENGGUNA' }}: {{ $isMitra ? 'MTS-' : 'USR-' }}{{ str_pad((string) $user->id, 4, '0', STR_PAD_LEFT) }}
                    </p>

                    <div class="mt-4 inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-full border border-emerald-100">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        AKUN AKTIF
                    </div>

                    <hr class="my-6 border-gray-100">

                    <div class="text-left space-y-4 text-xs">
                        <div>
                            <p class="text-gray-400 uppercase tracking-wider font-medium">Peran</p>
                            <p class="font-semibold text-gray-800 mt-0.5">{{ ucfirst($user->role) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 uppercase tracking-wider font-medium">Wilayah</p>
                            <p class="font-semibold text-gray-800 mt-0.5">{{ $user->kelurahan ?: 'Belum diisi' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 uppercase tracking-wider font-medium">Tanggal Gabung</p>
                            <p class="font-semibold text-gray-800 mt-0.5">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KANAN --}}
            <div class="lg:col-span-8 space-y-6">

                @if ($isMitra)
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                            <p class="text-xs text-gray-500 font-medium">Total Laporan Limbah</p>
                            <p class="text-2xl font-black text-gray-900 mt-1">{{ $totalLaporan }} <span class="text-sm font-normal text-gray-500">Laporan</span></p>
                        </div>
                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                            <p class="text-xs text-gray-500 font-medium">Sudah Diambil</p>
                            <p class="text-2xl font-black text-gray-900 mt-1">{{ $totalSelesai }} <span class="text-sm font-normal text-gray-500">Batch</span></p>
                        </div>
                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                            <p class="text-xs text-gray-500 font-medium">Limbah Tersirkulasi</p>
                            <p class="text-2xl font-black text-gray-900 mt-1">{{ number_format($totalKg, 0, ',', '.') }} <span class="text-sm font-normal text-gray-500">KG</span></p>
                        </div>
                    </div>
                @endif

                {{-- 1. Informasi & Lokasi --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <div class="mb-6">
                        <h3 class="text-base font-bold text-gray-900">{{ $isMitra ? 'Informasi Bisnis & Fasilitas' : 'Informasi Akun' }}</h3>
                        <p class="text-xs text-gray-500">Detail utama akun dan lokasi fasilitas Anda.</p>
                    </div>

                    @if ($isMitra)
                        <div class="mb-4">
                            <label for="nama_instansi" class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Bisnis / Instansi Resmi *</label>
                            <input
                                type="text"
                                id="nama_instansi"
                                name="nama_instansi"
                                value="{{ old('nama_instansi', $user->nama_instansi) }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#0F3D2A] focus:border-[#0F3D2A]"
                                required
                            >
                            @error('nama_instansi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="name" class="block text-xs font-bold text-gray-700 uppercase mb-1">{{ $isMitra ? 'Nama Lengkap PIC *' : 'Nama Lengkap *' }}</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#0F3D2A] focus:border-[#0F3D2A]"
                                required
                            >
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-bold text-gray-700 uppercase mb-1">Email *</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#0F3D2A] focus:border-[#0F3D2A]"
                                required
                            >
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="no_whatsapp" class="block text-xs font-bold text-gray-700 uppercase mb-1">Nomor WhatsApp</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-emerald-600">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                            </span>
                            <input
                                type="text"
                                id="no_whatsapp"
                                name="no_whatsapp"
                                value="{{ old('no_whatsapp', $user->no_whatsapp) }}"
                                placeholder="0812 3456 7890"
                                class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#0F3D2A] focus:border-[#0F3D2A]"
                            >
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1">Dipakai pembeli untuk menghubungi Anda dari halaman detail limbah.</p>
                        @error('no_whatsapp')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase">Lokasi Fasilitas</label>
                                <p class="text-xs text-gray-400">Geser peta sampai pin berada di titik lokasi, atau gunakan GPS.</p>
                            </div>
                            <button
                                type="button"
                                id="btn-gps"
                                class="inline-flex items-center justify-center gap-1.5 bg-emerald-50 hover:bg-emerald-100 text-[#0F3D2A] text-xs font-semibold px-3.5 py-2 rounded-xl transition"
                            >
                                📍 Gunakan GPS Saya
                            </button>
                        </div>

                        <div class="relative h-[280px] w-full overflow-hidden rounded-2xl border border-gray-200 mb-4">
                            <div id="map-profile" class="absolute inset-0 z-0"></div>

                            <div class="pointer-events-none absolute left-1/2 top-1/2 z-[400] -translate-x-1/2 -translate-y-full text-3xl leading-none drop-shadow">📍</div>

                            <div class="absolute top-3 inset-x-3 z-[400] bg-white/95 backdrop-blur px-3.5 py-2 rounded-xl shadow border border-gray-100 text-xs text-gray-700 flex items-center justify-between">
                                <span id="preview-address" class="truncate font-medium">
                                    {{ $user->alamat_lengkap ? '📍 ' . $user->alamat_lengkap : 'Geser peta untuk memilih lokasi' }}
                                </span>
                                <span class="text-[10px] bg-emerald-100 text-emerald-800 font-mono px-2 py-0.5 rounded font-bold shrink-0 ml-2">Peta Aktif</span>
                            </div>
                        </div>

                        <label for="input_alamat" class="block text-xs font-bold text-gray-700 uppercase mb-1">Alamat Lengkap</label>
                        <textarea
                            name="alamat_lengkap"
                            id="input_alamat"
                            rows="2"
                            placeholder="Jl. Pantai Kuta No. 88, Badung, Bali"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#0F3D2A] focus:border-[#0F3D2A]"
                        >{{ old('alamat_lengkap', $user->alamat_lengkap) }}</textarea>
                        @error('alamat_lengkap')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label for="kelurahan" class="block text-xs font-bold text-gray-700 uppercase mb-1">Kelurahan / Wilayah</label>
                                <input
                                    type="text"
                                    id="kelurahan"
                                    name="kelurahan"
                                    value="{{ old('kelurahan', $user->kelurahan) }}"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#0F3D2A] focus:border-[#0F3D2A]"
                                >
                                @error('kelurahan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="kode_pos" class="block text-xs font-bold text-gray-700 uppercase mb-1">Kode Pos</label>
                                <input
                                    type="text"
                                    id="kode_pos"
                                    name="kode_pos"
                                    value="{{ old('kode_pos', $user->kode_pos) }}"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#0F3D2A] focus:border-[#0F3D2A]"
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
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <div class="mb-6">
                        <h3 class="text-base font-bold text-gray-900">Keamanan Akun</h3>
                        <p class="text-xs text-gray-500">Kelola kata sandi dan sesi akun Anda.</p>
                    </div>

                    <div class="space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-gray-100">
                            <div>
                                <p class="text-sm font-bold text-gray-800">Kata Sandi Akun</p>
                                <p class="text-xs text-gray-500">Kosongkan jika tidak ingin mengganti. Sandi baru tersimpan saat Anda menekan "Simpan Perubahan".</p>
                            </div>
                            <button type="button" id="btn-open-password" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-semibold rounded-xl transition">
                                Ubah Sandi
                            </button>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
                            <div>
                                <p class="text-sm font-bold text-red-600">Keluar dari Akun</p>
                                <p class="text-xs text-gray-500">Akhiri sesi SIRKULA di perangkat ini.</p>
                            </div>
                            <button
                                type="submit"
                                form="logout-form"
                                class="inline-flex items-center justify-center px-4 py-2 border border-red-200 text-red-600 hover:bg-red-50 text-xs font-semibold rounded-xl transition"
                            >
                                Keluar dari Akun
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Modal sandi: berada DI DALAM form agar field ikut terkirim --}}
        <div id="password-modal" class="{{ $bukaModal ? '' : 'hidden' }} fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl">
                <h3 class="text-lg font-bold text-gray-900 mb-1">Ubah Kata Sandi</h3>
                <p class="text-xs text-gray-500 mb-4">Minimal 8 karakter. Tersimpan bersama perubahan profil lainnya.</p>

                <div class="space-y-4">
                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-700 uppercase mb-1">Kata Sandi Baru</label>
                        <input type="password" id="password" name="password" autocomplete="new-password" placeholder="Minimal 8 karakter" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#0F3D2A]">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-gray-700 uppercase mb-1">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" placeholder="Ulangi kata sandi baru" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#0F3D2A]">
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" id="btn-batal-password" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs font-semibold rounded-xl">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-[#0F3D2A] text-white text-xs font-semibold rounded-xl">Simpan Sandi</button>
                </div>
            </div>
        </div>
    </form>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
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