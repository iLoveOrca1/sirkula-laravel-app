{{-- resources/views/components/sections/banner-kemitraan.blade.php --}}
{{--
    Variabel opsional (dengan default):
    $createUrl → tujuan tombol "Tambah Informasi Limbah"
    $learnUrl  → tujuan tombol "Pelajari Lebih Lanjut" (dibuka di tab baru)
    Contoh: @include('components.sections.banner-kemitraan', ['createUrl' => route('listings.create')])
--}}
<section class="bg-background py-10 md:py-16">
    <div class="mx-auto max-w-7xl px-6">
        <div class="relative isolate overflow-hidden min-h-3xl rounded-3xl bg-primary-300 px-6 py-10 shadow-xl shadow-primary-500/25 md:px-12 md:py-14">

            {{-- Dekorasi latar (tidak dibaca screen reader) --}}
            <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
                {{-- Cincin besar di kanan bawah --}}
                <div class="absolute -bottom-32 -right-20 size-80 rounded-full border-[30px] border-primary-500/45 md:-right-10 md:size-[22rem]"></div>
                {{-- Cahaya lembut di kanan atas --}}
                <div class="absolute right-24 top-8 size-20 rounded-full bg-primary-100/20 blur-md md:right-28 md:size-24"></div>
            </div>

            <div class="max-w-xl">
                {{-- Badge --}}
                <p class="inline-flex items-center gap-2 rounded-full bg-primary-500 px-3 py-1.5 font-mono text-[10px] font-medium uppercase tracking-[0.15em] text-primary-100">
                    <span class="size-1.5 rounded-full bg-primary-100" aria-hidden="true"></span>
                    Kemitraan Fasilitas &amp; Usaha
                </p>

                {{-- Judul --}}
                <h2 class="mt-5 text-2xl font-semibold leading-tight tracking-tight text-white md:text-[1.7rem]">
                    Punya limbah yang masih bisa dimanfaatkan?
                </h2>

                {{-- Deskripsi --}}
                <p class="mt-4 text-sm leading-7 text-primary-100/70 md:text-base md:leading-8">
                    Bagikan informasi limbah terpilih bisnis Anda di SIRKULA agar lebih cepat terserap oleh pengolah kompos, peternak maggot, dan industri manufaktur sirkular lokal. Kurangi biaya TPA, ciptakan dampak nyata.
                </p>

                {{-- Tombol --}}
                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="{{ $createUrl ?? '#' }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-primary-500 transition hover:bg-background focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 8v8M8 12h8"/>
                        </svg>
                        Tambah Informasi Limbah
                    </a>

                    <a href="{{ $learnUrl ?? '#' }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 rounded-xl bg-primary-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-primary-500/80 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                        Pelajari Lebih Lanjut
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                            <path d="m15 3 6 0 0 6"/>
                            <path d="M10 14 21 3"/>
                        </svg>
                        <span class="sr-only">(buka di tab baru)</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>