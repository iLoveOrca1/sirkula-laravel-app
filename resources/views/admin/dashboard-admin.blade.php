@extends('layouts.app')

@section('content')
    {{-- Font SIRKULA: Plus Jakarta Sans & IBM Plex Mono --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        .font-sirkula {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .font-sirkula-mono {
            font-family: 'IBM Plex Mono', monospace;
        }
    </style>

    <div class="font-sirkula bg-[#F6F9F5] min-h-screen py-10">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER ADMIN --}}
            <div class="bg-white rounded-2xl shadow-sm border border-[#EBEFEA]
                        p-6 md:p-8 mb-8 flex flex-col sm:flex-row
                        sm:items-center sm:justify-between gap-4">

                <div>
                    <span class="font-sirkula-mono inline-flex items-center gap-2
                                 px-3 py-1 mb-3 rounded-full
                                 bg-[#E4F0E6] text-[#093A25]
                                 text-[10px] font-semibold uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#093A25]"></span>
                        Panel Administrasi
                    </span>

                    <h2 class="text-2xl md:text-3xl font-extrabold
                               tracking-tight text-[#093A25]">
                        Panel Admin SIRKULA
                    </h2>

                    <p class="text-sm md:text-base text-[#414943] mt-2 leading-relaxed">
                        Selamat datang,
                        <span class="font-bold text-[#1A4B36]">
                            {{ $user->name }}
                        </span>.
                        Anda memiliki akses untuk memverifikasi laporan limbah.
                    </p>
                </div>

                <div class="shrink-0 bg-[#F0F5F0] rounded-xl px-5 py-4">
                    <p class="font-sirkula-mono text-[10px] uppercase
                              tracking-widest text-gray-500 font-semibold">
                        Total Laporan
                    </p>

                    <p class="font-sirkula-mono text-2xl font-bold text-[#093A25] mt-1">
                        {{ number_format($semuaLaporan->count(), 0, ',', '.') }}
                    </p>

                    <p class="text-xs text-[#68736B] mt-1">
                        Data laporan ditampilkan
                    </p>
                </div>
            </div>

            {{-- PESAN SUKSES --}}
            @if (session('success'))
                <div role="status"
                     class="mb-6 bg-green-50 text-green-800 p-4 rounded-xl
                            border border-green-200 text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            {{-- PESAN ERROR --}}
            @if (session('error'))
                <div role="alert"
                     class="mb-6 bg-red-50 text-red-700 p-4 rounded-xl
                            border border-red-200 text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            {{-- VALIDASI FORM --}}
            @if ($errors->any())
                <div role="alert"
                     class="mb-6 bg-red-50 text-red-700 p-4 rounded-xl
                            border border-red-200 text-sm">

                    <p class="font-bold mb-2">Terjadi kesalahan:</p>

                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- TABEL LAPORAN --}}
            <div class="bg-white rounded-2xl shadow-sm
                        border border-[#EBEFEA] overflow-hidden">

                <div class="p-6 md:p-8 border-b border-[#EBEFEA]
                            flex flex-col sm:flex-row
                            sm:items-center sm:justify-between gap-3">

                    <div>
                        <h3 class="text-lg md:text-xl font-bold text-[#093A25]">
                            Daftar Semua Laporan Limbah
                        </h3>

                        <p class="text-sm text-[#68736B] mt-1">
                            Periksa detail material dan verifikasi laporan yang masuk.
                        </p>
                    </div>

                    <span class="font-sirkula-mono text-xs font-medium
                                 text-[#1A4B36] bg-[#E4F0E6]
                                 px-3 py-2 rounded-lg whitespace-nowrap">
                        {{ $semuaLaporan->where('status', 'menunggu')->count() }}
                        Menunggu
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">

                        <thead>
                            <tr class="bg-[#F6F9F5] text-[#68736B]
                                       text-[11px] uppercase tracking-wider
                                       font-sirkula-mono border-b border-[#EBEFEA]">

                                <th scope="col" class="py-4 px-5 font-semibold whitespace-nowrap">
                                    Instansi / Mitra
                                </th>

                                <th scope="col" class="py-4 px-5 font-semibold whitespace-nowrap">
                                    Material & Volume
                                </th>

                                <th scope="col" class="py-4 px-5 font-semibold min-w-[200px]">
                                    Deskripsi
                                </th>

                                <th scope="col" class="py-4 px-5 font-semibold whitespace-nowrap">
                                    Status Saat Ini
                                </th>

                                <th scope="col" class="py-4 px-5 font-semibold whitespace-nowrap">
                                    Aksi Verifikasi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="text-sm text-[#414943] divide-y divide-[#EBEFEA]">

                            @forelse ($semuaLaporan as $item)
                                @php
                                    $statusItem = strtolower($item->status ?? '');

                                    $statusClass = match ($statusItem) {
                                        'menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'tersedia' => 'bg-green-50 text-green-700 border-green-200',
                                        'ditolak' => 'bg-red-50 text-red-700 border-red-200',
                                        default => 'bg-gray-100 text-gray-600 border-gray-200',
                                    };

                                    $statusTeks = match ($statusItem) {
                                        'menunggu' => 'Menunggu',
                                        'tersedia' => 'Tersedia',
                                        'ditolak' => 'Ditolak',
                                        default => ucfirst($statusItem ?: 'Tidak diketahui'),
                                    };
                                @endphp

                                <tr class="hover:bg-[#F8FAF7] transition-colors">

                                    {{-- MITRA --}}
                                    <td class="py-4 px-5 align-top">
                                        <p class="font-semibold text-[#17231B] whitespace-nowrap">
                                            {{ $item->mitra->name ?? 'User Tidak Diketahui' }}
                                        </p>

                                        <p class="font-sirkula-mono text-[10px]
                                                  text-[#818A82] mt-2">
                                            {{ $item->tanggal_pencatatan
                                                ? \Carbon\Carbon::parse($item->tanggal_pencatatan)->format('d M Y')
                                                : '-' }}
                                        </p>
                                    </td>

                                    {{-- MATERIAL --}}
                                    <td class="py-4 px-5 align-top">
                                        <p class="font-semibold text-[#17231B]">
                                            {{ $item->judul ?? '-' }}
                                        </p>

                                        <p class="text-xs text-[#68736B] mt-1">
                                            {{ $item->kategori->nama_kategori ?? '-' }}
                                        </p>

                                        <p class="font-sirkula-mono text-xs font-semibold
                                                  text-[#1A4B36] mt-3 whitespace-nowrap">
                                            {{ $item->estimasi_volume ?? 0 }}
                                            {{ $item->satuan ?? '' }}
                                        </p>
                                    </td>

                                    {{-- DESKRIPSI --}}
                                    <td class="py-4 px-5 align-top">
                                        <p class="max-w-xs text-xs leading-relaxed
                                                  text-[#68736B] line-clamp-3"
                                           title="{{ $item->deskripsi }}">
                                            {{ $item->deskripsi ?: '-' }}
                                        </p>
                                    </td>

                                    {{-- STATUS --}}
                                    <td class="py-4 px-5 align-top whitespace-nowrap">
                                        <span class="inline-flex items-center gap-2
                                                     px-3 py-1.5 rounded-full
                                                     border text-xs font-semibold
                                                     {{ $statusClass }}">

                                            <span class="w-1.5 h-1.5 rounded-full
                                                {{ $statusItem === 'menunggu'
                                                    ? 'bg-amber-500'
                                                    : ($statusItem === 'tersedia'
                                                        ? 'bg-green-600'
                                                        : ($statusItem === 'ditolak'
                                                            ? 'bg-red-500'
                                                            : 'bg-gray-400')) }}">
                                            </span>

                                            {{ $statusTeks }}
                                        </span>
                                    </td>

                                    {{-- AKSI VERIFIKASI --}}
                                    <td class="py-4 px-5 align-top whitespace-nowrap">

                                        @if ($statusItem === 'menunggu')
                                            <div class="flex items-center gap-2">

                                                {{-- SETUJUI --}}
                                                <form
                                                    action="{{ route('laporan.updateStatus', $item->id) }}"
                                                    method="POST"
                                                    class="form-verifikasi">

                                                    @csrf
                                                    @method('PUT')

                                                    <input type="hidden"
                                                           name="status"
                                                           value="tersedia">

                                                    <button type="submit"
                                                        class="bg-[#1A4B36] hover:bg-[#093A25]
                                                               text-white px-3 py-2 rounded-lg
                                                               text-xs font-semibold
                                                               transition-colors
                                                               disabled:opacity-60
                                                               disabled:cursor-not-allowed">

                                                        Setujui
                                                    </button>
                                                </form>

                                                {{-- TOLAK --}}
                                                <form
                                                    action="{{ route('laporan.updateStatus', $item->id) }}"
                                                    method="POST"
                                                    class="form-verifikasi"
                                                    data-confirm="Tolak laporan '{{ $item->judul }}'?">

                                                    @csrf
                                                    @method('PUT')

                                                    <input type="hidden"
                                                           name="status"
                                                           value="ditolak">

                                                    <button type="submit"
                                                        class="bg-white hover:bg-red-50
                                                               text-red-600 px-3 py-2
                                                               rounded-lg text-xs font-semibold
                                                               border border-red-200
                                                               transition-colors
                                                               disabled:opacity-60
                                                               disabled:cursor-not-allowed">

                                                        Tolak
                                                    </button>
                                                </form>

                                            </div>
                                        @else
                                            <span class="text-xs text-[#818A82] italic">
                                                Sudah diproses
                                            </span>
                                        @endif

                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="py-16 px-5 text-center">

                                        <div class="text-3xl mb-3">🍃</div>

                                        <p class="font-semibold text-[#093A25]">
                                            Belum ada laporan limbah
                                        </p>

                                        <p class="text-sm text-[#818A82] mt-1">
                                            Laporan yang masuk akan ditampilkan di sini.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                {{-- FOOTER TABEL --}}
                <div class="px-5 py-4 md:px-8 bg-[#FAFBF9]
                            border-t border-[#EBEFEA]">

                    <p class="text-xs text-[#68736B]">
                        Menampilkan
                        <span class="font-sirkula-mono font-semibold text-[#093A25]">
                            {{ $semuaLaporan->count() }}
                        </span>
                        laporan pada data yang tersedia.
                    </p>

                </div>
            </div>
        </div>
    </div>

    <script>
        // Konfirmasi sebelum menolak laporan dan cegah klik ganda.
        document.querySelectorAll('.form-verifikasi').forEach(function(form) {
            form.addEventListener('submit', function(event) {
                const pesan = form.dataset.confirm;

                if (pesan && !window.confirm(pesan)) {
                    event.preventDefault();
                    return;
                }

                const tombol = form.querySelector('button[type="submit"]');

                if (tombol) {
                    tombol.disabled = true;
                    tombol.textContent = 'Memproses...';
                }
            });
        });
    </script>
@endsection