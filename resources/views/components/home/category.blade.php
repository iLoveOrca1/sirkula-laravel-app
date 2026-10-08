@php
    $listing = [
        'organik' => [ 'listing' => 42, 'est' => 18.4 ],   
        'anorganik' => ['listing' => 86, 'est' => 109.8 ]   
    ]
@endphp

{{-- resources/views/components/kategori-section.blade.php --}}
<section class="bg-background py-20 font-sans sm:py-28">
    <div class="mx-auto max-w-7xl px-6">

        {{-- Header section --}}
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-[11px] font-medium uppercase tracking-[0.2em] text-primary-300">
                    Klasifikasi Aliran Material
                </p>
                <h2 class="mt-3 text-2xl font-bold tracking-tight text-primary-500 sm:text-3xl">
                    Jelajahi Berdasarkan Jenis
                </h2>
            </div>
            <p class="max-w-lg text-xs leading-relaxed text-text sm:text-sm">
                Pilih jenis material untuk menelusuri ketersediaan terkini,
                frekuensi volume timbulan, dan spesifikasi penanganan.
            </p>
        </div>

        {{-- Kartu jenis limbah --}}
        <div class="mt-12 grid gap-6 md:grid-cols-2">

            {{-- Kartu: Limbah Organik --}}
            <article class="group relative overflow-hidden rounded-2xl border border-foreground/60 bg-background/40 p-6 transition-shadow hover:shadow-lg hover:shadow-primary-500/5 sm:p-8">
                {{-- Glow dekoratif --}}
                <div class="pointer-events-none absolute -top-16 -right-16 h-48 w-48 rounded-full bg-primary-100/70 blur-3xl"></div>

                <div class="relative flex items-start justify-between gap-4">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-primary-100 px-3 py-1 text-[10px] font-semibold uppercase tracking-widest text-primary-500">
                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21c-4.418 0-8-3.582-8-8 0-5 4-9 8-11 4 2 8 6 8 11 0 4.418-3.582 8-8 8Z" />
                        </svg>
                        Biodegradable
                    </span>
                    <span class="rounded-full border border-primary-100 bg-background px-3 py-1 text-[10px] font-medium text-text">
                        <strong class="font-semibold text-primary-500">{{ $listing['organik']['listing'] }}</strong> Listing Aktif
                    </span>
                </div>

                <h3 class="relative mt-5 text-lg font-bold text-primary-500 sm:text-xl">
                    Limbah Organik
                </h3>
                <p class="relative mt-2.5 text-xs leading-relaxed text-text sm:text-sm">
                    Fraksi bahan terurai hayati dari operasional dapur hotel, restoran,
                    agroindustri, dan pasar yang bernilai tinggi untuk pakan maggot BSF,
                    komposting, serta biodiesel.
                </p>

                {{-- Tag material --}}
                <div class="relative mt-5 flex flex-wrap gap-2">
                    @foreach (['Sisa Makanan Hotel', 'Minyak Jelantah (UCO)', 'Ampas Kopi Roastery', 'Limbah Kulit Buah'] as $tag)
                        <span class="rounded-full border border-primary-100/80 bg-surface px-3 py-1.5 text-[11px] text-text">
                            {{ $tag }}
                        </span>
                    @endforeach
                </div>

                <div class="relative mt-8 flex items-center justify-between">
                    {{-- <a href="{{ route('limbah.index', ['kategori' => 'organik']) }}" --}}
                    <a href="/"
                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary-500 transition-colors hover:text-primary-300 sm:text-sm">
                        Lihat Direktori Organik
                        <svg class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                    <span class="text-[10px] tracking-wide text-text/70">
                        Est. <strong class="font-semibold text-text">{{ $listing['organik']['est'] }} Ton</strong>/Bulan
                    </span>
                </div>
            </article>

            {{-- Kartu: Limbah Anorganik --}}
            <article class="group relative overflow-hidden rounded-2xl border border-foreground/60 bg-background/40 p-6 transition-shadow hover:shadow-lg hover:shadow-primary-500/5 sm:p-8">
                {{-- Glow dekoratif --}}
                <div class="pointer-events-none absolute -top-16 -right-16 h-48 w-48 rounded-full bg-primary-100/70 blur-3xl"></div>

                <div class="relative flex items-start justify-between gap-4">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-primary-100 px-3 py-1 text-[10px] font-semibold uppercase tracking-widest text-primary-500">
                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        Recyclable Dry
                    </span>
                    <span class="rounded-full border border-primary-100 bg-background px-3 py-1 text-[10px] font-medium text-text">
                        <strong class="font-semibold text-primary-500">{{ $listing['anorganik']['listing'] }}</strong> Listing Aktif
                    </span>
                </div>

                <h3 class="relative mt-5 text-lg font-bold text-primary-500 sm:text-xl">
                    Limbah Anorganik
                </h3>
                <p class="relative mt-2.5 text-xs leading-relaxed text-text sm:text-sm">
                    Material kering terpilah langsung dari sumber komersial. Siap diolah
                    oleh pabrik peleburan, pengolah plastik sirkular, dan fasilitas daur
                    ulang kertas karton.
                </p>

                {{-- Tag material --}}
                <div class="relative mt-5 flex flex-wrap gap-2">
                    @foreach (['Kardus Corrugated (OCC)', 'Botol PET Bening Pres', 'Pecahan Botol Kaca', 'Palet Kayu Bekas'] as $tag)
                        <span class="rounded-full border border-primary-100/80 bg-surface px-3 py-1.5 text-[11px] text-text">
                            {{ $tag }}
                        </span>
                    @endforeach
                </div>

                <div class="relative mt-8 flex items-center justify-between">
                    {{-- <a href="{{ route('limbah.index', ['kategori' => 'anorganik']) }}" --}}
                    <a href="/"
                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary-500 transition-colors hover:text-primary-300 sm:text-sm">
                        Lihat Direktori Anorganik
                        <svg class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                    <span class="text-[10px] tracking-wide text-text/70">
                        Est. <strong class="font-semibold text-text">{{ $listing['organik']['est'] }} Ton</strong>/Bulan
                    </span>
                </div>
            </article>

        </div>
    </div>
</section>