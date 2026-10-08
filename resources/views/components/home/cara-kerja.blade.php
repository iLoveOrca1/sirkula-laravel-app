{{-- resources/views/components/sections/cara-kerja.blade.php --}}
@php
    $steps = [
        [
            'number' => '01',
            'title' => 'Temukan Material',
            'description' => 'Recycler, peternak, dan pengempul mencari material yang dibutuhkan berdasarkan klasifikasi jenis, estimasi tonase, dan lokasi operasional terdekat.',
            'icon' => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/><path d="M9 11h4"/>',
        ],
        [
            'number' => '02',
            'title' => 'Hubungi Mitra',
            'description' => 'Buka halaman detail informasi limbah dan hubungi penanggung jawab operasional mitra penyedia secara langsung lewat sambungan WhatsApp resmi.',
            'icon' => '<path d="M21 15a2 2 0 0 1-2 2H8l-5 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/><path d="M8 8h8"/><path d="M8 12h5"/>',
        ],
        [
            'number' => '03',
            'title' => 'Sirkulasikan',
            'description' => 'Sepakati jadwal armada angkut atau nilai tebus secara langsung tanpa komisi perantara. Status ketersediaan diperbarui setelah material terangkut.',
            'icon' => '<path d="M21 12a9 9 0 0 0-15.5-6.2L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 15.5 6.2L21 16"/><path d="M16 16h5v5"/>',
        ],
    ];
@endphp

<section class="bg-background py-16 md:py-24">
    <div class="mx-auto max-w-7xl px-6">

        {{-- Heading --}}
        <div class="mx-auto max-w-4xl text-center">
            <p class="font-mono text-[10px] font-medium uppercase tracking-[0.2em] text-primary-300">
                Mekanisme Tanpa Perantara
            </p>
            <h2 class="mt-3 text-3xl font-regular tracking-tight text-primary-300 md:text-4xl">
                Cara Kerja SIRKULA
            </h2>
            <p class="mt-3 text-sm leading-relaxed text-text md:text-base">
                Tiga langkah sederhana untuk menghubungkan penghasil limbah dengan pelaku daur ulang secara mandiri, transparan, dan terukur.
            </p>
        </div>

        <br>
        {{-- Steps --}}
        <div class="mt-14 grid gap-5 md:grid-cols-3">
            @foreach ($steps as $step)
                <article class="hover:shadow-lg rounded-lg transition-shadow p-6 max-w-sm">
                    <div class="flex items-start justify-between">
                        <span class="font-mono text-sm font-medium text-primary-300">
                            {{ $step['number'] }}
                        </span>
                        <span class="flex size-7 items-center justify-center rounded-full bg-foreground text-primary-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 aria-hidden="true">
                                {!! $step['icon'] !!}
                            </svg>
                        </span>
                    </div>
                    
                    <br>
                    <h3 class="mt-6 text-base font-semibold text-primary-300">
                        {{ $step['title'] }}
                    </h3>
                    <p class="mt-2 text-sm leading-relaxed text-text">
                        {{ $step['description'] }}
                    </p>
                </article>
            @endforeach
        </div>

        <br>
        {{-- Platform independence notice --}}
        <aside class="mt-8 flex items-start gap-4 rounded-xl bg-foreground px-6 py-5">
            <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-background text-primary-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     aria-hidden="true">
                    <path d="M12 3 4 6v6c0 4.5 3.4 8.3 8 9 4.6-.7 8-4.5 8-9V6z"/>
                </svg>
            </span>
            <div>
                <h3 class="text-sm font-semibold text-primary-500">Protokol Independensi Platform</h3>
                <p class="mt-1 text-xs leading-relaxed text-text">
                    SIRKULA bertindak murni sebagai infrastruktur penyedia informasi dan fasilitator koneksi sirkular. Transaksi finansial, penimbangan akhir, izin angkut, dan kesepakatan logistik disepakati secara langsung antara mitra penyedia dan pengolah limbah.
                </p>
            </div>
        </aside>

    </div>
</section>