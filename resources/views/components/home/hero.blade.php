@php
    $chips = [
        ['title' => '128+ Ton', 'message' => 'Material Tersirkulasi'],
        ['title' => '94 Mitra', 'message' => 'Terverifikasi Aktif'],
        ['title' => '100% Langsung', 'message' => 'Via WhatsApp Mitra'],
    ]
@endphp

{{-- resources/views/components/hero.blade.php --}}
<section class="relative overflow-hidden bg-background font-sans">

    <div class="pointer-events-none absolute inset-0 mx-auto -top-10 h-100 w-300 rounded-full bg-primary-100/28 blur-2xl"></div>

    <div class="relative mx-auto flex max-w-7xl flex-col items-center px-6 pt-24 pb-16 text-center sm:pt-32 sm:pb-24 z-1">

        {{-- Heading --}}
        <h1 class="max-w-7xl text-3xl font-bold leading-tight tracking-tight text-primary-500 sm:text-5xl sm:leading-[1.15]">
            Temukan Limbah yang Masih Bernilai.
        </h1>
        <p class="mt-5 max-w-xl text-sm leading-relaxed text-text sm:text-base">
            Jelajahi informasi limbah organik dan anorganik dari berbagai mitra bisnis dan
            perhotelan, lalu hubungi mereka secara langsung via WhatsApp.
        </p>

        {{-- Search bar --}}
        {{-- <form action="{{ route('') }}" method="GET" --}}
        <form action="/" method="GET"
              class="mt-10 flex w-full max-w-3xl flex-col items-stretch gap-3 rounded-2xl border border-primary-100 bg-foreground/70 p-3 shadow-lg shadow-primary-500/5 backdrop-blur sm:flex-row sm:items-center sm:rounded-full sm:p-2">

            {{-- Input pencarian --}}
            <label class="flex flex-1 items-center gap-2 rounded-full px-4 py-2.5">
                <svg class="h-4 w-4 shrink-0 text-text/60" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m1.6-4.15a5.75 5.75 0 1 1-11.5 0 5.75 5.75 0 0 1 11.5 0Z" />
                </svg>
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Cari limbah: kardus, ampas kopi, minyak jelantah..."
                       class="w-full bg-transparent text-sm text-text placeholder:text-text/50 focus:outline-none">
            </label>

            <span class="hidden h-6 w-px bg-primary-100 sm:block"></span>

            {{-- Kategori --}}
            <label class="flex items-center gap-2 rounded-full px-4 py-2.5">
                <svg class="h-4 w-4 shrink-0 text-text/60" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                </svg>
                <select name="kategori" class="w-full cursor-pointer appearance-none bg-transparent text-sm text-text focus:outline-none sm:w-auto">
                    <option value="">Semua Kategori</option>
                    <option value="organik" @selected(request('kategori') === 'organik')>Organik</option>
                    <option value="anorganik" @selected(request('kategori') === 'anorganik')>Anorganik</option>
                </select>
            </label>

            <span class="hidden h-6 w-px bg-primary-100 sm:block"></span>

            {{-- Wilayah --}}
            <label class="flex items-center gap-2 rounded-full px-4 py-2.5">
                <svg class="h-4 w-4 shrink-0 text-text/60" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
                <select name="wilayah" class="w-full cursor-pointer appearance-none bg-transparent text-sm text-text focus:outline-none sm:w-auto">
                    <option value="">Semua Wilayah</option>
                    @foreach ($wilayah ?? [] as $item)
                        <option value="{{ $item }}" @selected(request('wilayah') === $item)>{{ $item }}</option>
                    @endforeach
                </select>
            </label>

            {{-- Tombol CTA --}}
            <button type="submit"
                    class="group inline-flex items-center justify-center gap-2 rounded-full bg-primary-500 px-6 py-3 text-sm font-semibold text-background transition-colors hover:bg-primary-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-300 focus-visible:ring-offset-2">
                Jelajahi Limbah
                <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12l-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </form>

        {{-- Link untuk pengelola usaha --}}
        {{-- <a href="{{ route('mitra.daftar') }}" --}}
        <a href="/register"
           class="group mt-5 inline-flex items-center gap-1.5 text-xs text-text/80 transition-colors hover:text-primary-500 sm:text-sm">
            Saya pengelola usaha &amp; ingin menyediakan informasi limbah
            <svg class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12l-7.5 7.5M21 12H3" />
            </svg>
        </a>

        {{-- Statistik --}}
        <div class="mt-12 flex flex-wrap items-center justify-center gap-3 sm:gap-4">
            @foreach ($chips as $chip)
                <div class="flex items-center gap-2 rounded-lg bg-surface px-4 py-4 text-xs text-text">
                    <span class="h-2 w-2 rounded-full bg-primary-300"></span>
                    <span><strong class="font-semibold text-primary-500">{{ $chip['title'] }}</strong> {{ $chip['message'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>