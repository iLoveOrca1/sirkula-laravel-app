@extends('layouts.app')

@section('content')
    <div class="bg-[#F6FBF5] min-h-screen pb-24 pt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ========================================== --}}
            {{-- HEADER DASHBOARD --}}
            {{-- ========================================== --}}
            <div class="mb-10">
                <div
                    class="flex items-center gap-2 text-[10px] font-mono font-bold uppercase tracking-widest text-gray-400 mb-3">
                    <a href="{{ route('home') }}" class="hover:text-[#093A25] transition-colors">Beranda</a>
                    <span>/</span>
                    <span class="text-[#093A25]">Permintaan Limbah</span>
                </div>
                <h1 class="text-3xl md:text-[34px] font-extrabold text-[#093A25] tracking-tight mb-3">
                    Permintaan Material dari Pengguna Lain
                </h1>
                <p class="text-sm text-[#414943] max-w-3xl">
                    Berikut adalah daftar permintaan material daur ulang dari recycler, peternak, dan industri sirkular.
                    Hubungi narahubung secara mandiri lewat WhatsApp.
                </p>
            </div>

            {{-- ========================================== --}}
            {{-- SEARCH & FILTER BAR --}}
            {{-- ========================================== --}}
            <form action="{{ route('permintaan.dashboard') }}" method="GET"
                class="bg-white rounded-[1.5rem] p-4 shadow-sm border border-[#EBEFEA] mb-8 flex flex-col md:flex-row items-center gap-4">

                <!-- Search -->
                <div class="relative flex-1 w-full">
                    <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari permintaan material..."
                        class="w-full bg-[#F6FBF5] border-none rounded-xl py-3 pl-11 pr-4 text-sm text-[#414943] focus:ring-2 focus:ring-[#093A25] outline-none transition-all">
                </div>

                <!-- Filter Kategori -->
                <!-- Filter Kategori (Disesuaikan dengan 3 Kategori Database) -->
                <div class="w-full md:w-56 shrink-0 relative">
                    <select name="kategori" onchange="this.form.submit()"
                        class="w-full bg-[#F6FBF5] border-none rounded-xl py-3 px-4 text-sm text-[#414943] font-medium appearance-none cursor-pointer focus:ring-2 focus:ring-[#093A25] outline-none">
                        <option value="">Semua Kategori</option>
                        <option value="Limbah Organik" @selected(request('kategori') == 'Limbah Organik')>Limbah Organik</option>
                        <option value="Limbah Anorganik" @selected(request('kategori') == 'Limbah Anorganik')>Limbah Anorganik</option>
                        <option value="Limbah B3" @selected(request('kategori') == 'Limbah B3')>Limbah B3</option>
                    </select>
                    <svg class="absolute right-4 top-4 w-4 h-4 text-gray-500 pointer-events-none" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                <!-- Submit Button (Hidden, triggered by select change or enter) -->
                <button type="submit" class="hidden">Filter</button>
            </form>

            {{-- ========================================== --}}
            {{-- GRID DATA PERMINTAAN (DATA DINAMIS) --}}
            {{-- ========================================== --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">

                @forelse ($permintaans as $item)
                    @php
                        // Pastikan format nomor telepon sesuai untuk link WhatsApp (misal 08 diganti 628)
                        $waNumber = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $item->whatsapp));
                        $waText = urlencode(
                            "Halo, saya melihat permintaan material {$item->nama_material} Anda di platform SIRKULA. Saya ingin berdiskusi lebih lanjut.",
                        );
                    @endphp
                    <div
                        class="bg-white rounded-[1.5rem] p-6 shadow-sm border border-[#EBEFEA] flex flex-col hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-center mb-4">
                            <span
                                class="inline-block bg-[#F0F5F0] text-[#093A25] px-2.5 py-1 rounded font-mono text-[9px] font-bold uppercase tracking-widest border border-[#EBEFEA]">
                                {{ $item->kategori }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $item->created_at->diffForHumans() }}</span>
                        </div>

                        <h3 class="text-lg font-bold text-[#093A25] mb-2 leading-snug">{{ $item->nama_material }}</h3>

                        <p class="text-[11px] font-mono text-gray-500 flex items-center gap-1.5 mb-6">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            {{ $item->mitra->name ?? 'Mitra Sirkula' }}
                        </p>

                        <div class="border-t border-[#EBEFEA] pt-4 mb-6 flex justify-between items-end">
                            <div>
                                <p class="text-[9px] font-mono uppercase tracking-widest text-gray-400 mb-1">Volume &amp;
                                    Frekuensi</p>
                                <p class="text-sm font-mono font-bold text-[#093A25]">
                                    {{ number_format($item->volume, 0, ',', '.') }} {{ $item->satuan }} /
                                    {{ $item->frekuensi }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-mono uppercase tracking-widest text-gray-400 mb-1">Lokasi
                                    Preferensi</p>
                                <p class="text-xs font-bold text-[#414943]">{{ $item->lokasi }}</p>
                            </div>
                        </div>

                        <a href="https://wa.me/{{ $waNumber }}?text={{ $waText }}" target="_blank"
                            class="w-full bg-[#204E3A] hover:bg-[#1A4B36] text-white font-bold text-sm py-3.5 rounded-xl text-center flex items-center justify-center gap-2 transition-colors mt-auto shadow-sm">
                            Hubungi via WhatsApp &rarr;
                        </a>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-[#EBEFEA] shadow-sm">
                        <div class="text-4xl mb-4 opacity-50">📂</div>
                        <h3 class="text-lg font-bold text-[#093A25] mb-2">Belum ada permintaan material</h3>
                        <p class="text-sm text-gray-500">Saat ini belum ada pengguna lain yang mempublikasikan kebutuhan
                            material. Silakan cek kembali nanti.</p>
                    </div>
                @endforelse

            </div>

            {{-- ========================================== --}}
            {{-- PAGINATION LARAVEL (DATA DINAMIS) --}}
            {{-- ========================================== --}}
            @if ($permintaans->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $permintaans->links('vendor.pagination.tailwind') }}
                </div>
            @endif

        </div>
    </div>
@endsection
