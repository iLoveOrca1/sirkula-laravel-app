<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sirkula | Tambah Informasi Limbah</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Leaflet CSS -->
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""
    >
</head>

<body class="bg-sirkula-light min-h-screen pb-10">

    <!-- Header -->
    <header class="bg-white shadow-sm py-4 mb-8">
        <div class="max-w-4xl mx-auto px-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-sirkula-green">
                SIRKULA
            </h1>

            <a
                href="/dashboard"
                class="text-sm text-gray-500 hover:text-gray-800"
            >
                Batal &amp; Kembali
            </a>
        </div>
    </header>

    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">

            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                Tambah Informasi Limbah
            </h2>

            <!-- Pesan Error Validasi -->
            @if ($errors->any())
                <div class="bg-red-50 text-red-500 p-4 rounded-md mb-6">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Tambah Laporan Limbah -->
            <form
                action="{{ route('laporan.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-6"
            >
                @csrf

                <!-- Judul Limbah -->
                <div>
                    <label
                        for="judul"
                        class="block text-sm font-medium text-gray-700 mb-1"
                    >
                        Judul / Nama Material Limbah *
                    </label>

                    <input
                        type="text"
                        id="judul"
                        name="judul"
                        value="{{ old('judul') }}"
                        required
                        placeholder="Contoh: Kardus Corrugated Bersih"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-sirkula-green focus:border-sirkula-green"
                    >
                </div>

                <!-- Kategori Limbah -->
                <div>
                    <label
                        for="kategori_id"
                        class="block text-sm font-medium text-gray-700 mb-1"
                    >
                        Kategori Limbah *
                    </label>

                    <select
                        id="kategori_id"
                        name="kategori_id"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:ring-sirkula-green focus:border-sirkula-green"
                    >
                        <option
                            value=""
                            disabled
                            {{ old('kategori_id') ? '' : 'selected' }}
                        >
                            -- Pilih Kategori --
                        </option>

                        @foreach ($kategori as $kat)
                            <option
                                value="{{ $kat->id }}"
                                {{ old('kategori_id') == $kat->id ? 'selected' : '' }}
                            >
                                {{ $kat->nama_kategori ?? 'Kategori ' . $kat->id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Volume dan Satuan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label
                            for="estimasi_volume"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Estimasi Volume/Kuantitas *
                        </label>

                        <input
                            type="number"
                            id="estimasi_volume"
                            name="estimasi_volume"
                            step="any"
                            min="0"
                            value="{{ old('estimasi_volume') }}"
                            required
                            placeholder="Contoh: 250"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-sirkula-green focus:border-sirkula-green"
                        >
                    </div>

                    <div>
                        <label
                            for="satuan"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Satuan *
                        </label>

                        <select
                            id="satuan"
                            name="satuan"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:ring-sirkula-green focus:border-sirkula-green"
                        >
                            <option
                                value="kg"
                                {{ old('satuan', 'kg') == 'kg' ? 'selected' : '' }}
                            >
                                Kilogram (KG)
                            </option>

                            <option
                                value="ton"
                                {{ old('satuan') == 'ton' ? 'selected' : '' }}
                            >
                                Ton
                            </option>

                            <option
                                value="liter"
                                {{ old('satuan') == 'liter' ? 'selected' : '' }}
                            >
                                Liter
                            </option>

                            <option
                                value="pcs"
                                {{ old('satuan') == 'pcs' ? 'selected' : '' }}
                            >
                                Unit / Buah
                            </option>
                        </select>
                    </div>

                </div>

                <!-- Pilihan Lokasi -->
                <div class="border border-gray-200 p-4 rounded-md bg-gray-50 space-y-3">

                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Pilih Titik Lokasi Penjemputan Limbah *
                    </label>

                    <div class="flex items-center space-x-3">
                        <input
                            class="form-radio text-green-600 focus:ring-sirkula-green"
                            type="radio"
                            name="jenis_lokasi"
                            id="lokasi_utama"
                            value="utama"
                            {{ old('jenis_lokasi', 'utama') == 'utama' ? 'checked' : '' }}
                        >

                        <label
                            for="lokasi_utama"
                            class="text-sm text-gray-700 cursor-pointer"
                        >
                            Gunakan Alamat Utama Profil (Koordinat Akun Anda)
                        </label>
                    </div>

                    <div class="flex items-center space-x-3">
                        <input
                            class="form-radio text-green-600 focus:ring-sirkula-green"
                            type="radio"
                            name="jenis_lokasi"
                            id="lokasi_lainnya"
                            value="lainnya"
                            {{ old('jenis_lokasi') == 'lainnya' ? 'checked' : '' }}
                        >

                        <label
                            for="lokasi_lainnya"
                            class="text-sm text-gray-700 cursor-pointer"
                        >
                            Gunakan Lokasi Lain / Tentukan di Peta
                        </label>
                    </div>

                    <!-- Peta Lokasi Lain dengan Pin Statis di Tengah -->
                    <div
                        id="form-lokasi-lainnya"
                        class="space-y-4 mt-3 pt-3 border-t border-gray-200"
                        style="{{ old('jenis_lokasi') == 'lainnya' ? '' : 'display: none;' }}"
                    >
                        <p class="text-xs text-gray-500">
                            Geser peta untuk menentukan titik lokasi penjemputan limbah:
                        </p>

                        <div class="relative">
                            <!-- Wadah Peta -->
                            <div
                                id="map"
                                style="height: 320px;"
                                class="w-full rounded-md border border-gray-300 z-10"
                            ></div>

                            <!-- Pin di Tengah Peta (Statis) -->
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 pointer-events-none z-20 flex flex-col items-center">
                                <div class="bg-gray-900 text-white text-[10px] px-2 py-0.5 rounded shadow-md mb-1 whitespace-nowrap">
                                    Lokasi Anda
                                </div>

                                <div class="w-4 h-4 bg-green-600 border-2 border-white rounded-full shadow-lg"></div>
                            </div>
                        </div>

                        <!-- Pratinjau Alamat Lengkap -->
                        <div>
                            <label
                                for="preview_alamat"
                                class="block text-xs font-medium text-gray-600 mb-1"
                            >
                                Alamat Lengkap Terpilih
                            </label>

                            <textarea
                                id="preview_alamat"
                                readonly
                                rows="2"
                                placeholder="Mendeteksi lokasi..."
                                class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-md text-gray-700 font-medium"
                            ></textarea>
                        </div>

                        <!-- Input Hidden untuk Database Controller -->
                        <div class="hidden">
                            <input
                                type="text"
                                name="latitude"
                                id="latitude"
                                value="{{ old('latitude') }}"
                                readonly
                            >
                            <input
                                type="text"
                                name="longitude"
                                id="longitude"
                                value="{{ old('longitude') }}"
                                readonly
                            >
                        </div>
                    </div>
                </div>

                <!-- Deskripsi Limbah -->
                <div>
                    <label
                        for="deskripsi"
                        class="block text-sm font-medium text-gray-700 mb-1"
                    >
                        Deskripsi &amp; Kondisi Material *
                    </label>

                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        rows="4"
                        required
                        placeholder="Jelaskan kondisi limbah secara detail..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-sirkula-green focus:border-sirkula-green"
                    >{{ old('deskripsi') }}</textarea>
                </div>

                <!-- Upload Foto -->
                <div class="border-2 border-dashed border-gray-300 p-6 rounded-md text-center bg-gray-50">

                    <label
                        for="foto_limbah"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Unggah Foto Limbah (Wajib) *
                    </label>

                    <input
                        type="file"
                        id="foto_limbah"
                        name="foto_limbah"
                        accept="image/jpeg,image/png,image/jpg"
                        required
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-sirkula-green hover:file:bg-green-100 mx-auto cursor-pointer"
                    >

                    <p class="text-xs text-gray-400 mt-2">
                        Format: JPG, JPEG, PNG. Maksimal 2MB.
                    </p>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-4 text-right">
                    <button
                        type="submit"
                        class="bg-sirkula-green text-white font-medium px-8 py-3 rounded-md hover:bg-sirkula-green-hover transition-colors"
                    >
                        Publikasikan Limbah
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""
    ></script>

    <script>
        const radioUtama = document.getElementById('lokasi_utama');
        const radioLainnya = document.getElementById('lokasi_lainnya');
        const formLainnya = document.getElementById('form-lokasi-lainnya');

        let map;
        let geocodeTimeout;

        function updateLocationData(center) {
            const lat = center.lat.toFixed(6);
            const lng = center.lng.toFixed(6);

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            const previewAlamat = document.getElementById('preview_alamat');
            previewAlamat.value = `${lat}, ${lng}`;

            clearTimeout(geocodeTimeout);

            geocodeTimeout = setTimeout(() => {
                fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`
                )
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Gagal mengambil alamat.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.display_name) {
                            previewAlamat.value = data.display_name;
                        }
                    })
                    .catch(error => {
                        console.error('Gagal mendapatkan alamat:', error);
                    });
            }, 800);
        }

        function initMap(lat = -8.6705, lng = 115.2126) {
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

            map.on('moveend', function () {
                updateLocationData(map.getCenter());
            });
        }

        radioUtama.addEventListener('change', function () {
            if (this.checked) {
                formLainnya.style.display = 'none';
            }
        });

        radioLainnya.addEventListener('change', function () {
            if (this.checked) {
                formLainnya.style.display = 'block';

                setTimeout(() => {
                    const initLat =
                        parseFloat(document.getElementById('latitude').value) || -8.6705;

                    const initLng =
                        parseFloat(document.getElementById('longitude').value) || 115.2126;

                    initMap(initLat, initLng);
                }, 200);
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            if (radioLainnya.checked) {
                formLainnya.style.display = 'block';

                const initLat =
                    parseFloat(document.getElementById('latitude').value) || -8.6705;

                const initLng =
                    parseFloat(document.getElementById('longitude').value) || 115.2126;

                initMap(initLat, initLng);
            }
        });
    </script>

</body>
</html>