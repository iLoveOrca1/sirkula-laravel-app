{{-- resources/views/components/sections/zona-agregasi.blade.php --}}
@php
    // Data contoh: ganti dengan data dari controller / database.
    $regions = [
        ['name' => 'Denpasar',       'listings' => 38, 'lat' => -8.6705, 'lng' => 115.2126, 'corridor' => 'Koridor Bali Selatan (Denpasar – Kuta – Nusa Dua)', 'radius' => 14.2],
        ['name' => 'Badung & Kuta',  'listings' => 44, 'lat' => -8.7220, 'lng' => 115.1769, 'corridor' => 'Koridor Badung Selatan (Kuta – Jimbaran – Nusa Dua)', 'radius' => 11.8],
        ['name' => 'Gianyar & Ubud', 'listings' => 19, 'lat' => -8.5069, 'lng' => 115.2625, 'corridor' => 'Koridor Bali Tengah (Gianyar – Ubud – Tegallalang)', 'radius' => 16.5],
        ['name' => 'Surabaya',       'listings' => 12, 'lat' => -7.2575, 'lng' => 112.7521, 'corridor' => 'Koridor Surabaya Raya (Surabaya – Sidoarjo – Gresik)', 'radius' => 22.4],
        ['name' => 'Jakarta Timur',  'listings' => 8,  'lat' => -6.2250, 'lng' => 106.9004, 'corridor' => 'Koridor Jabodetabek Timur (Jakarta Timur – Bekasi)', 'radius' => 18.9],
        ['name' => 'Bandung',        'listings' => 7,  'lat' => -6.9175, 'lng' => 107.6191, 'corridor' => 'Koridor Bandung Raya (Bandung – Cimahi – Lembang)', 'radius' => 15.3],
        ['name' => 'Semarang',       'listings' => 5,  'lat' => -6.9667, 'lng' => 110.4167, 'corridor' => 'Koridor Semarang Raya (Semarang – Ungaran – Demak)', 'radius' => 17.1],
        ['name' => 'Yogyakarta',     'listings' => 4,  'lat' => -7.7956, 'lng' => 110.3695, 'corridor' => 'Koridor DIY (Yogyakarta – Sleman – Bantul)', 'radius' => 13.6],
    ];
@endphp

<section id="zona-agregasi" class="bg-background py-10">
    <div class="mx-auto max-w-7xl px-6">
        <div class="rounded-3xl bg-surface p-6 md:p-10">

            {{-- Header --}}
            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                <div class="max-w-xl">
                    <p class="font-mono text-[10px] font-medium uppercase tracking-[0.2em] text-primary-300">
                        Zona Agregasi Wilayah
                    </p>
                    <h2 class="mt-2 text-2xl font-semibold tracking-tight text-primary-500 md:text-3xl">
                        Temukan Limbah di Sekitar Anda
                    </h2>
                    <p class="mt-2 text-sm leading-relaxed text-text">
                        Efisiensikan rute logistik pengangkutan dengan menelusuri sumber limbah berdasarkan klaster kota dan simpul pengumpulan terdekat.
                    </p>
                </div>

                <div class="flex flex-col items-start gap-2 md:items-end">
                    <p class="flex items-center gap-2 font-mono text-xs font-semibold text-primary-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <path d="m3 11 19-9-9 19-2-8z"/>
                        </svg>
                        {{ count($regions) }} Hub Aktif Terpetakan
                    </p>

                    <button type="button" id="zona-locate-btn"
                            class="inline-flex items-center gap-2 rounded-full bg-primary-500 px-4 py-2 text-xs font-medium text-white transition hover:bg-primary-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-500 disabled:cursor-wait disabled:opacity-60">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M12 2v3M12 19v3M2 12h3M19 12h3"/>
                            <circle cx="12" cy="12" r="8"/>
                        </svg>
                        <span id="zona-locate-label">Gunakan lokasi saya</span>
                    </button>
                </div>
            </div>

            {{-- Status geolokasi --}}
            <p id="zona-status" role="status" aria-live="polite" class="mt-4 hidden text-xs text-text"></p>

            {{-- Chips wilayah --}}
            <div id="zona-chips" class="mt-5 flex flex-wrap gap-2.5">
                @foreach ($regions as $i => $region)
                    <button type="button"
                            data-index="{{ $i }}"
                            aria-pressed="{{ $i === 0 ? 'true' : 'false' }}"
                            class="flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-medium text-primary-500 transition hover:bg-background aria-pressed:ring-2 aria-pressed:ring-primary-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-500">
                        {{ $region['name'] }}
                        <span class="rounded-md bg-foreground px-1.5 py-0.5 font-mono text-[10px] font-medium text-text">
                            {{ $region['listings'] }} listing
                        </span>
                    </button>
                @endforeach
            </div>

            {{-- Peta --}}
            <div class="relative mt-5 min-h-84 overflow-hidden rounded-2xl bg-foreground md:h-52">
                <iframe id="zona-map"
                        title="Peta hub wilayah terpilih"
                        class="absolute inset-0 size-full border-0"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                </iframe>

                {{-- Overlay info: tidak menghalangi interaksi peta di bagian atas --}}
                <div class="pointer-events-none absolute inset-x-0 bottom-0 flex items-end justify-between gap-4 bg-gradient-to-t from-primary-500/80 via-primary-500/40 to-transparent px-5 pb-4 pt-12">
                    <div>
                        <p class="font-mono text-[10px] font-medium uppercase tracking-[0.15em] text-primary-100">
                            Simpul Sirkulasi Aktif
                        </p>
                        <p id="zona-corridor" class="mt-0.5 text-sm font-medium text-white"></p>
                    </div>
                    <p class="hidden shrink-0 items-center gap-1.5 rounded-md bg-white/20 px-2.5 py-1.5 text-[11px] text-white backdrop-blur-sm sm:flex">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <path d="M21.3 8.7 8.7 21.3a1 1 0 0 1-1.4 0l-4.6-4.6a1 1 0 0 1 0-1.4L15.3 2.7a1 1 0 0 1 1.4 0l4.6 4.6a1 1 0 0 1 0 1.4z"/>
                            <path d="m7.5 10.5 2 2M10.5 7.5l2 2M13.5 4.5l2 2M4.5 13.5l2 2"/>
                        </svg>
                        Radius logistik rerata <span id="zona-radius"></span> km
                    </p>
                </div>
            </div>

        </div>
    </div>

    <script>
        (() => {
            const regions = @json($regions);

            const chipsEl = document.getElementById('zona-chips');
            const mapEl = document.getElementById('zona-map');
            const corridorEl = document.getElementById('zona-corridor');
            const radiusEl = document.getElementById('zona-radius');
            const statusEl = document.getElementById('zona-status');
            const btn = document.getElementById('zona-locate-btn');
            const btnLabel = document.getElementById('zona-locate-label');
            const btnDefaultLabel = btnLabel.textContent;

            // ---------- Peta & pemilihan wilayah ----------
            function selectRegion(index) {
                const r = regions[index];
                if (!r) return;

                chipsEl.querySelectorAll('button').forEach((chip) => {
                    chip.setAttribute('aria-pressed', chip.dataset.index === String(index) ? 'true' : 'false');
                });

                const dLat = 0.06, dLng = 0.09;
                const bbox = [r.lng - dLng, r.lat - dLat, r.lng + dLng, r.lat + dLat].join(',');
                mapEl.src = `https://www.openstreetmap.org/export/embed.html?bbox=${bbox}&layer=mapnik&marker=${r.lat},${r.lng}`;

                corridorEl.textContent = r.corridor;
                radiusEl.textContent = r.radius.toString().replace('.', ',');
            }

            chipsEl.addEventListener('click', (e) => {
                const chip = e.target.closest('button[data-index]');
                if (!chip) return;
                setStatus('');
                selectRegion(Number(chip.dataset.index));
            });

            // ---------- Geolocation ----------
            function haversineKm(lat1, lng1, lat2, lng2) {
                const toRad = (d) => (d * Math.PI) / 180;
                const R = 6371;
                const dLat = toRad(lat2 - lat1);
                const dLng = toRad(lng2 - lng1);
                const a = Math.sin(dLat / 2) ** 2 +
                    Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.sin(dLng / 2) ** 2;
                return 2 * R * Math.asin(Math.sqrt(a));
            }

            function setStatus(message, isError = false) {
                statusEl.textContent = message;
                statusEl.classList.toggle('hidden', !message);
                statusEl.classList.toggle('text-red-700', isError);
                statusEl.classList.toggle('text-text', !isError);
            }

            function setLoading(loading) {
                btn.disabled = loading;
                btnLabel.textContent = loading ? 'Mencari lokasi…' : btnDefaultLabel;
            }

            function onSuccess(position) {
                const { latitude, longitude } = position.coords;

                let nearest = 0;
                let nearestDistance = Infinity;
                regions.forEach((r, i) => {
                    const d = haversineKm(latitude, longitude, r.lat, r.lng);
                    if (d < nearestDistance) {
                        nearestDistance = d;
                        nearest = i;
                    }
                });

                selectRegion(nearest);
                setLoading(false);
                setStatus(
                    `Hub terdekat dari lokasi Anda: ${regions[nearest].name} (± ${nearestDistance.toFixed(1).replace('.', ',')} km).`
                );
            }

            function onError(error) {
                setLoading(false);
                const messages = {
                    1: 'Izin lokasi ditolak. Aktifkan izin lokasi di pengaturan browser, atau pilih kota secara manual.',
                    2: 'Lokasi Anda tidak dapat ditentukan saat ini. Coba lagi atau pilih kota secara manual.',
                    3: 'Pencarian lokasi memakan waktu terlalu lama. Coba lagi atau pilih kota secara manual.',
                };
                setStatus(messages[error.code] ?? 'Terjadi kesalahan saat mengambil lokasi.', true);
            }

            btn.addEventListener('click', () => {
                if (!('geolocation' in navigator)) {
                    setStatus('Browser Anda tidak mendukung geolokasi. Pilih kota secara manual.', true);
                    return;
                }
                setStatus('');
                setLoading(true);
                navigator.geolocation.getCurrentPosition(onSuccess, onError, {
                    enableHighAccuracy: false,
                    timeout: 10000,
                    maximumAge: 300000,
                });
            });

            // Tampilan awal: wilayah pertama
            selectRegion(0);
        })();
    </script>
</section>