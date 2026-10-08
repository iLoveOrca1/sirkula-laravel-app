{{-- resources/views/components/sections/katalog-limbah.blade.php --}}
{{--
    Sementara memakai associative array (dummy).
    Nanti cukup kirim variabel dari controller dengan struktur yang sama:
        return view('home', ['wastes' => $wastes, 'totalWastes' => 128]);
    Blok `??=` di bawah hanya aktif jika $wastes belum dikirim dari luar.

    Struktur tiap item:
        title, category ('ORGANIK'|'ANORGANIK'), status ('tersedia'|'diproses'|'diambil'),
        partner, quantity, description, image, whatsapp (format internasional tanpa '+'),
        meta   => [['icon' => 'pin|clock|truck|check', 'text' => '...'], ...],
        footnote (opsional)
--}}
@php
    $totalWastes ??= 128;
    $allWastesUrl ??= '#';

    $wastes ??= [
        [
            'title' => 'Limbah Kardus Corrugated Bersih',
            'category' => 'ANORGANIK',
            'status' => 'tersedia',
            'partner' => 'Hotel Sagara Resort',
            'quantity' => '250 KG',
            'description' => 'Kardus kemasan makanan dan logistik hotel, kondisi kering bebas minyak, sudah dilipat rapi dan diikat per bundel.',
            'image' => asset('img/boxes.png'),
            'whatsapp' => '6281234567801',
            'meta' => [
                ['icon' => 'pin', 'text' => 'Denpasar Selatan, Bali'],
                ['icon' => 'clock', 'text' => 'Timbulan: Mingguan (Setiap Selasa)'],
            ],
        ],
        [
            'title' => 'Limbah Organik Dapur & Sayuran',
            'category' => 'ORGANIK',
            'status' => 'tersedia',
            'partner' => 'Bali Fresh Resort Kitchen',
            'quantity' => '180 KG / Hari',
            'description' => 'Sisa potongan sayur mentah, kulit buah tropis, dan ampas dapur persiapan restoran. Bebas tusuk gigi, plastik, dan tulang.',
            'image' => asset('img/boxes.png'),
            'whatsapp' => '6281234567802',
            'meta' => [
                ['icon' => 'pin', 'text' => 'Jimbaran, Badung, Bali'],
                ['icon' => 'clock', 'text' => 'Pengambilan harian: Pukul 15:00 - 17:00'],
            ],
        ],
        [
            'title' => 'Botol Plastik PET Bening Pres',
            'category' => 'ANORGANIK',
            'status' => 'diproses',
            'partner' => 'CV Alam Karya Sirkular',
            'quantity' => '500 KG',
            'description' => 'Botol mineral bening tanpa tutup, telah dipres hidrolik menjadi 4 bal padat. Kemurnian >96%, siap muat ke truk engkol.',
            'image' => asset('img/boxes.png'),
            'whatsapp' => '6281234567803',
            'meta' => [
                ['icon' => 'pin', 'text' => 'Kuta, Badung, Bali'],
                ['icon' => 'truck', 'text' => 'Akses truk engkol tersedia di lokasi'],
            ],
        ],
        [
            'title' => 'Minyak Jelantah Kualitas Restoran',
            'category' => 'ORGANIK',
            'status' => 'tersedia',
            'partner' => 'Restoran Ubud Rasa Seminyak',
            'quantity' => '120 Liter',
            'description' => 'Used cooking oil hasil deep-fry dapur komersial, telah disaring kasar dari endapan tepung, disimpan dalam jerigen 20 liter.',
            'image' => asset('img/boxes.png'),
            'whatsapp' => '6281234567804',
            'meta' => [
                ['icon' => 'pin', 'text' => 'Ubud, Gianyar, Bali'],
                ['icon' => 'clock', 'text' => 'Timbulan: Mingguan (Setiap Jumat)'],
            ],
        ],
        [
            'title' => 'Ampas Kopi Organik Kering',
            'category' => 'ORGANIK',
            'status' => 'tersedia',
            'partner' => 'Kopi Seniman Roastery',
            'quantity' => '75 KG',
            'description' => 'Ampas ekstraksi espresso dari roastery specialty. Telah diangin-anginkan, kadar air <15%, siap untuk kompos atau media tanam.',
            'image' => asset('img/boxes.png'),
            'whatsapp' => '6281234567805',
            'meta' => [
                ['icon' => 'pin', 'text' => 'Canggu, Badung, Bali'],
                ['icon' => 'clock', 'text' => 'Timbulan: Harian (Pukul 17:00)'],
            ],
        ],
        [
            'title' => 'Palet Kayu Bekas Utuh Standar',
            'category' => 'ANORGANIK',
            'status' => 'diambil',
            'partner' => 'Logistik Nusa Jaya',
            'quantity' => '40 Unit',
            'description' => 'Palet kayu pinus standar 120 × 100 cm kondisi 90% utuh tanpa patah, sisa bongkar muatan kargo ekspor.',
            'image' => asset('img/boxes.png'),
            'whatsapp' => '6281234567806',
            'footnote' => 'Batch baru dijadwalkan masuk bulan depan',
            'meta' => [
                ['icon' => 'pin', 'text' => 'Sanur, Denpasar, Bali'],
                ['icon' => 'check', 'text' => 'Telah diambil oleh Pengrajin Mebel'],
            ],
        ],
    ];

    // Tampilan tiap status (class ditulis utuh agar terdeteksi Tailwind)
    $statuses = [
        'tersedia' => ['label' => 'Tersedia',       'pill' => 'bg-primary-100 text-primary-500', 'dot' => 'bg-primary-300'],
        'diproses' => ['label' => 'Sedang Diproses', 'pill' => 'bg-red-100 text-red-800',         'dot' => 'bg-red-500'],
        'diambil'  => ['label' => 'Sudah Diambil',  'pill' => 'bg-white/90 text-text',           'dot' => 'bg-text/50'],
    ];

    // Ikon inline (gaya Lucide)
    $icons = [
        'pin'      => '<path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'truck'    => '<path d="M14 18V6a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h2"/><path d="M15 9h4l3 3v5a1 1 0 0 1-1 1h-1"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/><path d="M9 18h6"/>',
        'check'    => '<circle cx="12" cy="12" r="9"/><path d="m8.5 12.5 2.5 2.5 4.5-5"/>',
        'verified' => '<circle cx="12" cy="12" r="9"/><path d="m8.5 12.5 2.5 2.5 4.5-5"/>',
        'external' => '<path d="M7 17 17 7"/><path d="M8 7h9v9"/>',
        'arrow'    => '<path d="M5 12h14"/><path d="m13 6 6 6-6 6"/>',
        'lock'     => '<rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/>',
    ];
    $icon = fn (string $name, string $class = 'size-3.5') =>
        '<svg xmlns="http://www.w3.org/2000/svg" class="'.$class.' shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.($icons[$name] ?? '').'</svg>';
@endphp

<section class="bg-background py-12 md:py-20">
    <div class="mx-auto max-w-7xl px-6">

        {{-- Header --}}
        <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="flex items-center gap-2 font-mono text-[10px] font-medium uppercase tracking-[0.2em] text-primary-300">
                    <span class="size-1.5 rounded-full bg-primary-300" aria-hidden="true"></span>
                    Katalog Pembaruan Harian
                </p>
                <h2 class="mt-2 text-2xl font-semibold tracking-tight text-primary-500 md:text-3xl">
                    Ketersediaan Limbah Terbaru
                </h2>
                <p class="mt-2 text-sm text-text">
                    Temukan material terpilih yang siap diambil langsung dari mitra SIRKULA.
                </p>
            </div>

            <a href="{{ $allWastesUrl }}"
               class="inline-flex w-fit items-center gap-2 rounded-xl bg-foreground px-4 py-2.5 text-sm font-medium text-primary-500 transition hover:bg-primary-100 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-500">
                Lihat Semua Limbah ({{ $totalWastes }})
                {!! $icon('arrow', 'size-3.5') !!}
            </a>
        </div>

        {{-- Grid kartu --}}
        <div class="mt-8 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($wastes as $waste)
                @php
                    $status = $statuses[$waste['status']] ?? $statuses['tersedia'];
                    $isTaken = $waste['status'] === 'diambil';
                    $waUrl = 'https://wa.me/'.$waste['whatsapp'].'?text='.rawurlencode('Halo, saya tertarik dengan limbah "'.$waste['title'].'" di SIRKULA.');
                @endphp

                <article class="{{ $waste['status'] == 'diambil' ? 'opacity-70' : 'opacity-100' }} min-h-8xl flex flex-col overflow-hidden rounded-2xl bg-white transition-shadow hover:shadow-lg hover:shadow-primary-500/5">

                    {{-- Gambar + badge --}}
                    <div class="relative h-42 bg-foreground">
                        <img src="{{ $waste['image'] }}" alt="{{ $waste['title'] }}" loading="lazy"
                             class="size-full object-cover {{ $isTaken ? 'grayscale-[40%]' : '' }}">

                        <span class="absolute left-3 top-3 rounded bg-white/90 px-2 py-1 font-mono text-[9px] font-medium uppercase tracking-wider text-primary-500">
                            {{ $waste['category'] }}
                        </span>

                        <span class="absolute right-3 top-3 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 font-mono text-[10px] font-medium {{ $status['pill'] }}">
                            <span class="size-1.5 rounded-full {{ $status['dot'] }}" aria-hidden="true"></span>
                            {{ $status['label'] }}
                        </span>
                    </div>

                    {{-- Isi --}}
                    <div class="flex flex-1 flex-col p-4">
                        <div class="flex items-center justify-between gap-3">
                            <p class="flex min-w-0 items-center gap-1.5 font-mono text-[10px] text-text">
                                {!! $icon('verified', 'size-3') !!}
                                <span class="truncate">{{ $waste['partner'] }}</span>
                            </p>
                            <span class="shrink-0 rounded-md bg-foreground px-2 py-1 font-mono text-[11px] font-bold {{ $isTaken ? 'text-text/70' : 'text-primary-500' }}">
                                {{ $waste['quantity'] }}
                            </span>
                        </div>

                        <h3 class="mt-3 text-base font-semibold {{ $isTaken ? 'text-primary-300' : 'text-primary-500' }}">
                            {{ $waste['title'] }}
                        </h3>
                        <p class="mt-1.5 line-clamp-2 text-xs leading-relaxed text-text">
                            {{ $waste['description'] }}
                        </p>

                        <ul class="mt-4 space-y-2 text-xs text-text">
                            @foreach ($waste['meta'] as $item)
                                <li class="flex items-center gap-2">
                                    {!! $icon($item['icon'], 'size-3.5 text-text/70') !!}
                                    <span>{{ $item['text'] }}</span>
                                </li>
                            @endforeach
                        </ul>

                        {{-- CTA --}}
                        <div class="mt-auto pt-5">
                            @if ($isTaken)
                                <button type="button" disabled
                                        class="flex w-full cursor-not-allowed items-center justify-center gap-2 rounded-lg bg-foreground/70 px-4 py-2.5 text-sm font-semibold text-text/50">
                                    Batch Selesai
                                    {!! $icon('lock', 'size-3.5') !!}
                                </button>
                            @else
                                <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
                                   class="flex w-full items-center justify-center gap-1.5 rounded-lg bg-foreground px-4 py-2.5 text-sm font-semibold text-primary-500 transition hover:bg-primary-100 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-500">
                                    Hubungi Mitra
                                    {!! $icon('external', 'size-3.5') !!}
                                    <span class="sr-only">(buka WhatsApp di tab baru)</span>
                                </a>
                            @endif

                            <p class="mt-3 text-center text-[10px] text-text/70">
                                {{ $waste['footnote'] ?? 'Transaksi dan pengambilan dilakukan langsung dengan mitra' }}
                            </p>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

    </div>
</section>