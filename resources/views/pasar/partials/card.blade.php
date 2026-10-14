@php
    $katName = $item->kategori->nama_kategori ?? 'Umum';
    $status = strtolower((string) $item->status);
    $final = $status === 'sudah diambil';
    $diproses = in_array($status, ['sedang diproses', 'menunggu'], true);

    // Pewarnaan status ketersediaan
    if ($status === 'tersedia') {
        $statusDot = 'bg-[#093A25]';
        $statusBg = 'bg-[#C6ECCD] text-[#093A25]';
    } elseif ($diproses) {
        $statusDot = 'bg-[#C4411E]';
        $statusBg = 'bg-[#FDECE8] text-[#C4411E]';
    } else {
        $statusDot = 'bg-gray-500';
        $statusBg = 'bg-[#EBEFEA] text-gray-600';
    }

    $btnText = $final ? 'Riwayat Sirkulasi &#8635;' : 'Lihat Detail &rarr;';
    $btnClass = $final
        ? 'bg-[#F0F5F0] text-gray-600 hover:bg-[#E4EBE4]'
        : 'bg-[#F0F5F0] text-[#093A25] hover:bg-[#E4EBE4]';

    // Penyedia & lokasi
    $mitraNama = $item->mitra?->nama_instansi ?: ($item->mitra?->name ?: 'Mitra Sirkula');
    $wilayah = $item->mitra?->kelurahan ?: 'Lokasi belum diatur';

    // Kuantitas
    $angka = rtrim(rtrim(number_format((float) $item->estimasi_volume, 2, ',', '.'), '0'), ',');
    $satuan = match (strtolower((string) $item->satuan)) {
        'kg' => 'KG',
        'ton' => 'Ton',
        'liter' => 'Liter',
        'pcs' => 'Unit',
        default => strtoupper((string) $item->satuan),
    };
@endphp

<article
    class="group flex h-full flex-col rounded-[2rem] bg-white border border-[#EBEFEA] p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
    style="font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;">

    <!-- Foto + label -->
    <a href="{{ route('pasar.show', $item->id) }}" class="relative block h-48 overflow-hidden rounded-2xl bg-gray-100">
        @if ($item->foto_limbah)
            <img src="{{ asset('storage/' . $item->foto_limbah) }}" alt="{{ $item->judul }}"
                class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-105 {{ $final ? 'grayscale-[60%] opacity-80' : '' }}">
        @else
            <div class="absolute inset-0 flex items-center justify-center text-sm font-medium text-gray-400">Tanpa Foto
            </div>
        @endif

        <!-- Kategori (kiri atas) -->
        <span
            class="absolute left-3 top-3 rounded-lg bg-white px-2.5 py-1 text-[9px] font-bold uppercase tracking-widest text-[#093A25] shadow-sm"
            style="font-family: 'IBM Plex Mono', ui-monospace, monospace;">
            {{ $katName }}
        </span>

        <!-- Status (kanan atas) -->
        <span
            class="absolute right-3 top-3 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[10px] font-bold tracking-wide shadow-sm {{ $statusBg }}"
            style="font-family: 'IBM Plex Mono', ui-monospace, monospace;">
            <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>{{ ucwords($item->status) }}
        </span>
    </a>

    <!-- Informasi -->
    <div class="flex flex-1 flex-col pt-4">
        <p class="mb-2 flex flex-wrap items-center gap-x-1.5 gap-y-1 text-xs font-normal text-gray-600">
            <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4 21V5a2 2 0 012-2h8a2 2 0 012 2v16M16 9h2a2 2 0 012 2v10M3 21h18M8 7h4M8 11h4M8 15h4" />
            </svg>
            <span class="font-medium">{{ $mitraNama }}</span>
            <span class="text-gray-400">&bull;</span>
            <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 21s7-5.686 7-11a7 7 0 10-14 0c0 5.314 7 11 7 11z" />
                <circle cx="12" cy="10" r="2.5" />
            </svg>
            <span>{{ $wilayah }}</span>
        </p>

        <a href="{{ route('pasar.show', $item->id) }}">
            <h3
                class="mb-4 line-clamp-2 text-xl font-bold leading-tight tracking-tight text-[#093A25] transition-colors group-hover:text-[#1A4B36]">
                {{ $item->judul }}
            </h3>
        </a>

        <!-- Kuantitas -->
        <div class="mb-3 flex items-center justify-between rounded-xl border border-[#EBEFEA] bg-[#F6FBF5] p-3">
            <span class="text-[9px] font-bold uppercase tracking-widest text-gray-500"
                style="font-family: 'IBM Plex Mono', ui-monospace, monospace;">
                {{ $final ? 'Kuantitas Siklus' : 'Kuantitas Tersedia' }}
            </span>
            <span
                class="rounded bg-white px-2.5 py-0.5 text-base font-extrabold tracking-tight text-[#093A25] shadow-sm"
                style="font-family: 'IBM Plex Mono', ui-monospace, monospace;">
                {{ $angka }} {{ $satuan }}
            </span>
        </div>

        <!-- Catatan ringkas (ikon mengikuti status) -->
        <div
            class="mb-5 flex items-center gap-2 text-xs font-normal {{ $diproses ? 'text-[#C4411E]' : 'text-gray-600' }}">
            @if ($diproses)
                <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8"
                    viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9" />
                    <path stroke-linecap="round" d="M8.5 12h7" />
                </svg>
                <span class="line-clamp-1">Dalam tahap konfirmasi recycler</span>
            @elseif ($final)
                <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8"
                    viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12.5l2.5 2.5L16 9.5" />
                </svg>
                <span class="line-clamp-1">Telah disirkulasikan oleh mitra pendaur ulang</span>
            @else
                <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8"
                    viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12.5l2.5 2.5L16 9.5" />
                </svg>
                <span class="line-clamp-1">{{ Str::limit($item->deskripsi, 45) }}</span>
            @endif
        </div>

        <!-- Tombol -->
        <a href="{{ route('pasar.show', $item->id) }}"
            class="mt-auto w-full rounded-xl py-3 text-center text-sm font-semibold tracking-tight transition-colors {!! $btnClass !!}">
            {!! $btnText !!}
        </a>
        <p class="mt-3 text-center text-[10px] font-normal text-gray-400">
            Transaksi dan pengambilan langsung dengan mitra
        </p>
    </div>
</article>
