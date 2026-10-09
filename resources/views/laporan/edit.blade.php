@extends('layouts.app')

@section('content')
    <!-- Leaflet CSS -->
    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    @endpush

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Edit Laporan Limbah</h2>
            <p class="text-gray-500 mt-2">Perbarui detail laporan limbah Anda, termasuk foto, kondisi material, dan titik lokasi.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
            <!-- Form Edit Laporan -->
            <form action="{{ route('laporan.update', $laporan->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Judul / Nama Material -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul / Nama Material Limbah <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul', $laporan->judul) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-sirkula-green focus:border-sirkula-green outline-none">
                </div>

                <!-- Kategori Limbah & Tanggal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Limbah <span
                                class="text-red-500">*</span></label>
                        <select name="kategori_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:ring-sirkula-green focus:border-sirkula-green outline-none">
                            <option value="" disabled>-- Pilih Kategori --</option>
                            @foreach ($kategori as $kat)
                                <option value="{{ $kat->id }}"
                                    {{ old('kategori_id', $laporan->kategori_id) == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pencatatan</label>
                        <input type="date" name="tanggal_pencatatan"
                            value="{{ old('tanggal_pencatatan', \Carbon\Carbon::parse($laporan->tanggal_pencatatan)->format('Y-m-d')) }}"
                            readonly
                            class="w-full px-4 py-2 border border-gray-200 bg-gray-50 text-gray-500 rounded-md outline-none cursor-not-allowed"
                            title="Tanggal pencatatan dibuat otomatis oleh sistem">
                    </div>
                </div>

                <!-- Volume dan Satuan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estimasi Volume <span
                                class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="estimasi_volume"
                            value="{{ old('estimasi_volume', $laporan->estimasi_volume) }}" placeholder="Contoh: 50.5"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-sirkula-green focus:border-sirkula-green outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Satuan <span
                                class="text-red-500">*</span></label>
                        <select name="satuan" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:ring-sirkula-green focus:border-sirkula-green outline-none">
                            <option value="kg" {{ strtolower(old('satuan', $laporan->satuan)) == 'kg' ? 'selected' : '' }}>Kilogram (KG)</option>
                            <option value="ton" {{ strtolower(old('satuan', $laporan->satuan)) == 'ton' ? 'selected' : '' }}>Ton</option>
                            <option value="liter" {{ strtolower(old('satuan', $laporan->satuan)) == 'liter' ? 'selected' : '' }}>Liter</option>
                            <option value="unit" {{ strtolower(old('satuan', $laporan->satuan)) == 'unit' ? 'selected' : '' }}>Unit / Buah</option>
                        </select>
                    </div>
                </div>

                <!-- OPSI PILIHAN LOKASI / KOORDINAT -->
                <div class="border border-gray-200 p-4 rounded-md bg-gray-50 space-y-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Titik Lokasi Penjemputan Limbah *</label>
                    
                    <div class="flex items-center space-x-3">
                        <input class="form-radio text-green-600 focus:ring-sirkula-green" type="radio" name="jenis_lokasi" id="lokasi_utama" value="utama" {{ old('jenis_lokasi', 'utama') == 'utama' ? 'checked' : '' }}>
                        <label for="lokasi_utama" class="text-sm text-gray-700 cursor-pointer">
                            Gunakan Alamat Utama Profil (Koordinat Akun Anda)
                        </label>
                    </div>

                    <div class="flex items-center space-x-3">
                        <input class="form-radio text-green-600 focus:ring-sirkula-green" type="radio" name="jenis_lokasi" id="lokasi_lainnya" value="lainnya" {{ old('jenis_lokasi') == 'lainnya' ? 'checked' : '' }}>
                        <label for="lokasi_lainnya" class="text-sm text-gray-700 cursor-pointer">
                            Gunakan Lokasi Lain / Tentukan di Peta
                        </label>
                    </div>

                    <!-- Peta Interaktif & Input Koordinat (Muncul jika 'lainnya' dipilih) -->
                    <div id="form-lokasi-lainnya" class="space-y-4 mt-3 pt-3 border-t border-gray-200" style="{{ old('jenis_lokasi') == 'lainnya' ? '' : 'display: none;' }}">
                        <p class="text-xs text-gray-500">Klik pada peta atau geser pin untuk mengubah titik lokasi penjemputan:</p>
                        
                        <!-- Wadah Peta -->
                        <div id="map" style="height: 300px;" class="w-full rounded-md border border-gray-300 z-10"></div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="latitude" class="block text-xs font-medium text-gray-600 mb-1">Latitude (Garis Lintang) *</label>
                                <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $laporan->latitude) }}" readonly placeholder="Latitude otomatis"
                                    class="w-full px-3 py-1.5 text-sm bg-gray-100 border border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label for="longitude" class="block text-xs font-medium text-gray-600 mb-1">Longitude (Garis Bujur) *</label>
                                <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $laporan->longitude) }}" readonly placeholder="Longitude otomatis"
                                    class="w-full px-3 py-1.5 text-sm bg-gray-100 border border-gray-300 rounded-md">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi Detail -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Internal / Deskripsi <span
                            class="text-red-500">*</span></label>
                    <textarea name="deskripsi" rows="4" required
                        placeholder="Jelaskan kondisi fisik limbah, asal limbah, dan cara penyimpanannya saat ini..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-sirkula-green focus:border-sirkula-green outline-none">{{ old('deskripsi', $laporan->deskripsi) }}</textarea>
                </div>

                <!-- Area Edit Foto -->
                <div class="border-2 border-dashed border-gray-300 p-6 rounded-md text-center bg-gray-50 flex flex-col items-center">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Foto Limbah Saat Ini</label>

                    @if ($laporan->foto_limbah)
                        <img src="{{ asset('storage/' . $laporan->foto_limbah) }}" alt="Foto Limbah"
                            class="h-32 w-32 object-cover rounded-md mb-4 border border-gray-200 shadow-sm">
                    @else
                        <div class="h-32 w-32 bg-white rounded-md mb-4 flex items-center justify-center text-sm text-gray-400 border border-gray-200 shadow-sm">
                            Tidak ada foto</div>
                    @endif

                    <label class="block text-sm font-medium text-gray-700 mb-2 mt-2">Unggah Foto Baru (Opsional)</label>
                    <input type="file" name="foto_limbah" accept="image/jpeg, image/png, image/jpg"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-sirkula-green hover:file:bg-green-100 mx-auto cursor-pointer focus:outline-none">
                    <p class="text-xs text-gray-400 mt-2">Biarkan kosong jika tidak ingin mengubah foto. Format: JPG, PNG maksimal 2MB.</p>
                </div>

                <!-- Tombol Aksi -->
                <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                    <a href="{{ route('dashboard') }}"
                        class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-sirkula-green text-white rounded-md hover:bg-green-700 font-medium transition-colors shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        const radioUtama = document.getElementById('lokasi_utama');
        const radioLainnya = document.getElementById('lokasi_lainnya');
        const formLainnya = document.getElementById('form-lokasi-lainnya');
        let map, marker;

        function initMap(lat, lng) {
            if (map) {
                map.invalidateSize();
                return;
            }

            map = L.map('map').setView([lat, lng], 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            marker = L.marker([lat, lng], { draggable: true }).addTo(map);

            marker.on('dragend', function (e) {
                let position = marker.getLatLng();
                document.getElementById('latitude').value = position.lat.toFixed(6);
                document.getElementById('longitude').value = position.lng.toFixed(6);
            });

            map.on('click', function (e) {
                marker.setLatLng(e.latlng);
                document.getElementById('latitude').value = e.latlng.lat.toFixed(6);
                document.getElementById('longitude').value = e.latlng.lng.toFixed(6);
            });
        }

        radioUtama.addEventListener('change', function() {
            if (this.checked) {
                formLainnya.style.display = 'none';
            }
        });

        radioLainnya.addEventListener('change', function() {
            if (this.checked) {
                formLainnya.style.display = 'block';
                setTimeout(() => {
                    let currentLat = parseFloat(document.getElementById('latitude').value) || -8.6705;
                    let currentLng = parseFloat(document.getElementById('longitude').value) || 115.2126;
                    initMap(currentLat, currentLng);
                }, 200);
            }
        });

        // Jika opsi 'lainnya' terpilih sejak awal (misal karena error validasi atau data lama)
        if (radioLainnya.checked) {
            formLainnya.style.display = 'block';
            window.addEventListener('DOMContentLoaded', () => {
                let currentLat = parseFloat(document.getElementById('latitude').value) || -8.6705;
                let currentLng = parseFloat(document.getElementById('longitude').value) || 115.2126;
                initMap(currentLat, currentLng);
            });
        }
    </script>
@endsection