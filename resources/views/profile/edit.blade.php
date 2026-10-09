
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Pengaturan Profil</h2>
        <p class="text-gray-500 mt-2">
            Perbarui informasi pribadi, alamat operasional, titik lokasi peta, dan kata sandi akun Anda.
        </p>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 font-medium">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('profile.update') }}" method="POST" class="p-6 md:p-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Informasi Dasar -->
                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                        Informasi Dasar
                    </h3>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Lengkap
                        </label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-sirkula-green focus:border-sirkula-green @error('name') border-red-500 @enderror"
                            required
                        >
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Alamat Email
                        </label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-sirkula-green focus:border-sirkula-green @error('email') border-red-500 @enderror"
                            required
                        >
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @if ($user->role === 'mitra')
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Instansi / Perusahaan
                            </label>
                            <input
                                type="text"
                                name="nama_instansi"
                                value="{{ old('nama_instansi', $user->nama_instansi) }}"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-sirkula-green focus:border-sirkula-green @error('nama_instansi') border-red-500 @enderror"
                            >
                            @error('nama_instansi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                </div>

                <!-- Keamanan -->
                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                        Keamanan (Opsional)
                    </h3>
                    <p class="text-xs text-gray-500 mb-4">
                        Biarkan kosong jika Anda tidak ingin mengubah kata sandi saat ini.
                    </p>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kata Sandi Baru
                        </label>
                        <input
                            type="password"
                            name="password"
                            placeholder="Minimal 8 karakter"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-sirkula-green focus:border-sirkula-green @error('password') border-red-500 @enderror"
                        >
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Konfirmasi Kata Sandi Baru
                        </label>
                        <input
                            type="password"
                            name="password_confirmation"
                            placeholder="Ketik ulang kata sandi baru"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-sirkula-green focus:border-sirkula-green"
                        >
                    </div>

                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-100 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-600">
                            {{ strtoupper(substr($user->role, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase">
                                Role Saat Ini
                            </p>
                            <p class="font-bold text-gray-800 capitalize">
                                {{ $user->role }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alamat dan Peta -->
            <div class="mt-10 pt-8 border-t border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">
                            Titik Lokasi &amp; Alamat Operasional
                        </h3>
                        <p class="text-xs text-gray-500">
                            Geser peta untuk menentukan titik koordinat atau gunakan tombol GPS perangkat Anda.
                        </p>
                    </div>

                    <button
                        type="button"
                        id="btn-gps"
                        class="inline-flex items-center justify-center gap-2 bg-[#eaf0ea] hover:bg-[#dfe8df] text-[#0f3d2a] text-xs font-semibold px-4 py-2.5 rounded-xl transition shadow-sm"
                    >
                        📍 Gunakan Lokasi GPS Saya
                    </button>
                </div>

                <div class="relative h-[340px] w-full overflow-hidden rounded-2xl border border-gray-200 shadow-inner mb-6">
                    <div id="map-profile" class="absolute inset-0 z-0"></div>

                    <div class="absolute top-4 inset-x-4 z-[400] bg-white/95 backdrop-blur px-4 py-2.5 rounded-xl shadow-md border border-gray-100 text-xs text-gray-600 flex items-center justify-between">
                        <span id="preview-address" class="truncate font-medium text-gray-800">
                            Memuat wilayah...
                        </span>
                        <span class="text-[10px] bg-green-50 text-green-700 font-mono px-2 py-0.5 rounded font-bold shrink-0 ml-2">
                            Peta Aktif
                        </span>
                    </div>

                    <!-- Pin Lokasi -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-full z-[400] pointer-events-none flex flex-col items-center">
                        <div class="bg-[#0f3d2a] text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-lg whitespace-nowrap mb-1">
                            Lokasi Anda
                        </div>
                        <div class="w-4 h-4 bg-[#0f3d2a] rounded-full border-2 border-white shadow-md animate-bounce"></div>
                        <div class="w-1 h-3 bg-[#0f3d2a] -mt-1 shadow-sm"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kelurahan / Desa / Kecamatan
                        </label>
                        <input
                            type="text"
                            name="kelurahan"
                            id="input_kelurahan"
                            value="{{ old('kelurahan', $user->kelurahan) }}"
                            placeholder="Contoh: Sesetan"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-sirkula-green focus:border-sirkula-green"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kode Pos
                        </label>
                        <input
                            type="text"
                            name="kode_pos"
                            id="input_kodepos"
                            value="{{ old('kode_pos', $user->kode_pos) }}"
                            placeholder="Contoh: 80223"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-sirkula-green focus:border-sirkula-green"
                        >
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Alamat Lengkap
                    </label>
                    <textarea
                        name="alamat_lengkap"
                        id="input_alamat"
                        rows="2"
                        placeholder="Nama jalan, nomor gedung, patokan..."
                        class="w-full px-4 py-2 border rounded-lg focus:ring-sirkula-green focus:border-sirkula-green"
                    >{{ old('alamat_lengkap', $user->alamat_lengkap) }}</textarea>
                </div>

                <!-- Koordinat -->
                <input
                    type="hidden"
                    id="lat"
                    name="latitude"
                    value="{{ old('latitude', $user->latitude ?? -8.6705) }}"
                >
                <input
                    type="hidden"
                    id="lng"
                    name="longitude"
                    value="{{ old('longitude', $user->longitude ?? 115.2126) }}"
                >
            </div>

            <!-- Tombol Simpan -->
            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                <button
                    type="submit"
                    class="bg-sirkula-green hover:bg-green-700 text-white font-bold py-3 px-8 rounded-xl transition shadow-md"
                >
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Leaflet CSS dan JS -->
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const latInput = document.getElementById('lat');
    const lngInput = document.getElementById('lng');
    const previewText = document.getElementById('preview-address');
    const alamatInput = document.getElementById('input_alamat');
    const kelurahanInput = document.getElementById('input_kelurahan');
    const kodePosInput = document.getElementById('input_kodepos');
    const btnGps = document.getElementById('btn-gps');

    const initialLat = parseFloat(latInput.value) || -8.6705;
    const initialLng = parseFloat(lngInput.value) || 115.2126;

    const profileMap = L.map('map-profile', {
        zoomControl: false
    }).setView([initialLat, initialLng], 14);

    L.control.zoom({
        position: 'bottomright'
    }).addTo(profileMap);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(profileMap);

    let timeoutId = null;

    function fetchAddressAndFillForm(lat, lng) {
        previewText.textContent = 'Menerjemahkan lokasi ke alamat...';

        const url = new URL('https://nominatim.openstreetmap.org/reverse');
        url.search = new URLSearchParams({
            format: 'jsonv2',
            lat: lat,
            lon: lng,
            zoom: 18,
            addressdetails: 1
        }).toString();

        fetch(url.toString())
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Gagal mengambil alamat.');
                }

                return response.json();
            })
            .then(function (data) {
                if (data && data.display_name) {
                    previewText.textContent = '📍 ' + data.display_name;
                    alamatInput.value = data.display_name;

                    if (data.address) {
                        const addr = data.address;

                        const wilayah =
                            addr.village ||
                            addr.suburb ||
                            addr.town ||
                            addr.city_district ||
                            addr.city ||
                            addr.county ||
                            '';

                        if (wilayah) {
                            kelurahanInput.value = wilayah;
                        }

                        if (addr.postcode) {
                            kodePosInput.value = addr.postcode;
                        }
                    }
                } else {
                    previewText.textContent =
                        '📍 Titik koordinat berhasil disematkan';
                }
            })
            .catch(function () {
                previewText.textContent =
                    '📍 Titik koordinat berhasil disematkan';
            });
    }

    // Perbarui koordinat ketika peta digeser.
    profileMap.on('move', function () {
        const center = profileMap.getCenter();

        latInput.value = center.lat;
        lngInput.value = center.lng;
    });

    // Ambil alamat setelah pergerakan peta selesai.
    profileMap.on('moveend', function () {
        const center = profileMap.getCenter();

        clearTimeout(timeoutId);

        timeoutId = setTimeout(function () {
            fetchAddressAndFillForm(center.lat, center.lng);
        }, 600);
    });

    // Tombol GPS perangkat.
    btnGps.addEventListener('click', function () {
        if (!navigator.geolocation) {
            alert('Browser Anda tidak mendukung fitur Geolocation GPS.');
            return;
        }

        btnGps.textContent = 'Mencari GPS...';
        btnGps.disabled = true;

        navigator.geolocation.getCurrentPosition(
            function (position) {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;

                profileMap.flyTo([userLat, userLng], 16, {
                    duration: 1.5
                });

                btnGps.textContent = '📍 Gunakan Lokasi GPS Saya';
                btnGps.disabled = false;
            },
            function () {
                alert(
                    'Gagal mendeteksi GPS. Pastikan izin lokasi perangkat Anda aktif.'
                );

                btnGps.textContent = '📍 Gunakan Lokasi GPS Saya';
                btnGps.disabled = false;
            },
            {
                enableHighAccuracy: true,
                timeout: 10000
            }
        );
    });

    // Pastikan ukuran peta dirender dengan benar.
    setTimeout(function () {
        profileMap.invalidateSize();
    }, 200);

    // Tampilkan alamat awal berdasarkan koordinat yang tersimpan.
    fetchAddressAndFillForm(initialLat, initialLng);
});
</script>
@endsection