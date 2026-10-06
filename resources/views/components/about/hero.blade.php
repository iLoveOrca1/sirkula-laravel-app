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

<section {{ $attributes->class('bg-background') }}>
    <div class="mx-auto h-fit max-w-7xl px-6 pb-14 pt-16 md:pb-20 md:pt-24">

        {{-- Pil --}}
        <span class="inline-flex items-center gap-1.5 rounded-full bg-primary-100 px-3 py-1
                    font-mono text-[10px] font-medium uppercase tracking-wider text-primary-500">
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-700"></span>
            {{ $badge }}
        </span>

        {{-- Judul --}}
        <h1 class="mt-5 max-w-5xl text-3xl font-bold leading-tight tracking-tight text-primary-500
                sm:text-4xl md:text-5xl md:leading-[1.1]">
            {{ $title }}
        </h1>

        {{-- Deskripsi --}}
        <p class="mt-5 max-w-3xl text-sm leading-relaxed text-gray-600 md:text-[15px]">
            {{ $description }}
        </p>

        {{-- Statistik --}}
        <dl class="mt-10 grid grid-cols-1 gap-4 md:grid-cols-3">
            @foreach ($stats as $stat)
                <div class="rounded-lg bg-white px-5 py-4 shadow-sm/5">
                    <dt class="font-mono text-[10px] font-medium uppercase tracking-wider text-primary-300">
                        {{ $stat['label'] }}
                    </dt>
                    <dd class="mt-2 font-mono text-xl font-bold text-primary-500">
                        {{ $stat['value'] }}
                    </dd>
                    <dd class="mt-1 text-xs text-gray-500">
                        {{ $stat['desc'] }}
                    </dd>
                </div>
            @endforeach
        </dl>
    </div>
</section>