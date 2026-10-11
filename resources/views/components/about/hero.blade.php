@props([
    'badge' => 'Tentang SIRKULA • Gerakan Sirkularitas Terbuka',
    'title' => 'Menutup Celah Informasi Antara Penghasil Limbah dan Pengolah Lokal.',
    'description' => 'Di Indonesia, ribuan ton material organik dan anorganik bernilai berakhir di TPA setiap harinya bukan karena ketiadaan kebutuhan industri pengolah, melainkan karena informasinya terisolasi di balik loading dock. SIRKULA hadir sebagai infrastruktur digital independen yang menghubungkan penghasil fasilitas komersial dengan jejaring pengolah terverifikasi—tanpa rantai birokrasi dan perantara berlapis.',
    'stats' => [
        ['label' => 'Volume Terfasilitasi', 'value' => '128.4 TON', 'desc' => 'Material teralihkan dari TPA'],
        ['label' => 'Jejaring Fasilitas', 'value' => '94 MITRA', 'desc' => 'Hotel, F&B, & Manufaktur aktif'],
        ['label' => 'Model Transaksi', 'value' => '0% KOMISI', 'desc' => 'Akses publik & nirkoleksi perantara'],
    ],
])

<section class="bg-gradient-to-b from-[#F0F5F0] via-[#F6FBF5] to-[#F6FBF5] pt-12 pb-20 border-b border-[#EBEFEA]">
    <div class="mx-auto max-w-7xl px-6 md:px-8">

        {{-- Badge --}}
        <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#C6ECCD] text-[#093A25] rounded-full text-[10px] font-mono font-bold uppercase tracking-wider mb-6 shadow-sm">
            <span class="w-1.5 h-1.5 rounded-full bg-[#093A25]"></span>
            {{ $badge }}
        </div>

        {{-- Judul --}}
        <h1 class="max-w-5xl text-[32px] md:text-5xl font-extrabold leading-[1.15] tracking-tight text-[#093A25] mb-6">
            {{ $title }}
        </h1>

        {{-- Deskripsi --}}
        <p class="max-w-3xl text-sm md:text-base leading-relaxed text-[#414943] mb-12">
            {{ $description }}
        </p>

        {{-- Statistik Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($stats as $stat)
                <div class="rounded-3xl bg-white p-8 shadow-sm border border-[#EBEFEA] transition-transform hover:-translate-y-1 hover:shadow-md">
                    <p class="font-mono text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">
                        {{ $stat['label'] }}
                    </p>
                    <p class="font-mono text-3xl font-extrabold text-[#093A25] mb-2">
                        {{ $stat['value'] }}
                    </p>
                    <p class="text-xs text-[#414943]">
                        {{ $stat['desc'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>