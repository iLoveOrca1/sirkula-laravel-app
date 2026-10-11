<footer class="bg-surface mt-auto">
    <div class="mx-auto max-w-7xl px-6 py-14 lg:py-16">

        {{-- Bagian atas: brand, navigasi, pemberitahuan --}}
        <div class="grid grid-cols-1 gap-10 md:grid-cols-12 md:gap-8">

            {{-- Brand --}}
            <div class="md:col-span-5">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2" aria-label="SIRKULA - Beranda">
                    <span class="relative inline-flex h-8 w-8 justify-center">
                        <img src="{{ asset('logo.png') }}" alt="sirkula-logo">
                    </span>
                    <span class="relative text-xl font-bold tracking-tight text-emerald-950">
                        SIRKULA
                        <span class="absolute-right-2 top-0.5 h-1.5 w-1.5 rounded-full bg-red-500"></span>
                    </span>
                </a>

                <p class="text-sm font-semibold text-emerald-900">
                    Temukan. Hubungkan. Sirkulasikan.
                </p>
                <p class="mt-3 max-w-sm text-xs leading-relaxed text-gray-500">
                    Infrastruktur pertukaran dan koneksi informasi limbah sirkular untuk mitra industri,
                    perhotelan, komersial, dan pengolah lokal.
                </p>
            </div>

            {{-- Eksplorasi --}}
            <nav class="md:col-span-3" aria-label="Eksplorasi">
                <h3 class="text-sm font-semibold text-emerald-950">Eksplorasi</h3>
                <ul class="mt-4 space-y-2.5 text-xs text-gray-500">
                    <li><a href="#" class="transition hover:text-emerald-800">Jelajahi Limbah</a></li>
                    <li><a href="#" class="transition hover:text-emerald-800">Tentang SIRKULA</a></li>
                    <li><a href="#" class="transition hover:text-emerald-800">Untuk Mitra</a></li>
                    <li><a href="{{ route('bantuan') }}"
                            class="hover:text-[#093A25] hover:underline transition-colors">Bantuan &amp; Panduan</a>
                    </li>
                </ul>
            </nav>

            {{-- Pemberitahuan Sistem --}}
            <div class="md:col-span-4">
                <h3 class="text-sm font-semibold text-emerald-950">Pemberitahuan Sistem</h3>
                <div class="mt-4 rounded-lg bg-[#EBEFEA] p-4 ring-1 ring-emerald-900/5 text-[#414943]">
                    <p class="font-mono text-[10px] font-semibold uppercase tracking-wider">
                        Protokol Transaksi
                    </p>
                    <p class="mt-1.5 text-xs italic leading-relaxed">
                        "Transaksi dan pengambilan dilakukan langsung dengan mitra dan pengolah."
                    </p>
                </div>
            </div>
        </div>

        {{-- Bagian bawah: hak cipta & disclaimer --}}
        <div
            class="mt-12 flex flex-col gap-4 border-t border-emerald-900/10 pt-6 text-[11px] text-gray-500
                    md:flex-row md:items-start md:justify-between md:gap-12">
            <p class="shrink-0">
                &copy; {{ date('Y') }} SIRKULA. Hak cipta dilindungi undang-undang.
            </p>
            <p class="max-w-md leading-relaxed md:text-right">
                SIRKULA memfasilitasi penemuan dan koneksi informasi limbah. Transaksi dan kesepakatan
                pengambilan dilakukan langsung antara mitra dan pengolah recycler.
            </p>
        </div>
    </div>
</footer>
