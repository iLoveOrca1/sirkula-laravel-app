<header class="relative bg-header">
    <div class="mx-auto flex h-20 max-w-7xl px-6 items-center justify-between gap-6">

        {{-- Kiri: logo + menu --}}
        <div class="flex items-center gap-10">
            <a href="{{ url('/') }}" class="flex items-center gap-2" aria-label="Sirkula, ke beranda">
                <img src="{{ asset('logo.png') }}" alt="" class="h-8 w-8">
                <span class="text-xl font-semibold tracking-wide text-primary-500">SIRKULA</span>
                <span class="mt-[-10px] h-2 w-2 rounded-full bg-accent" aria-hidden="true"></span>
            </a>

            <x-layouts.navbar.links class="hidden md:flex" />
        </div>

        {{-- Kanan: aksi (desktop) --}}
        <div class="hidden items-center gap-6 md:flex">
            <a href="{{ url('/login') }}"
               class="text-sm font-medium text-ink transition-colors hover:text-primary focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary">
                Masuk
            </a>
            <a href="{{ url('/register') }}"
               class="rounded-xl bg-primary-100 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-primary-hover focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                Daftar menjadi Mitra
            </a>
        </div>

        {{-- Menu mobile --}}
        <details class="group md:hidden">
            <summary class="flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-md text-primary hover:bg-primary/5 [&::-webkit-details-marker]:hidden">
                <span class="sr-only">Buka menu</span>
                <svg class="h-6 w-6 group-open:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg class="hidden h-6 w-6 group-open:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
                </svg>
            </summary>

            <div class="absolute inset-x-0 top-full border-b border-primary/10 bg-header px-6 pb-5 pt-2 shadow-lg">
                <x-layouts.navbar.links vertical />

                <div class="mt-4 flex flex-col gap-2 border-t border-primary/10 pt-4">
                    <a href="{{ url('/login') }}"
                       class="rounded-md px-3 py-2 text-sm font-medium text-ink hover:bg-primary/5">
                        Masuk
                    </a>
                    <a href="{{ url('/register') }}"
                       class="rounded-lg bg-primary px-4 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-hover">
                        Daftar menjadi Mitra
                    </a>
                </div>
            </div>
        </details>
    </div>
</header>