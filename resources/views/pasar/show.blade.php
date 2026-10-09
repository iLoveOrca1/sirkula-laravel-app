@extends('layouts.app')

@section('content')
    @php
        // ------------------------------------------------------------------
        // Data turunan
        // ------------------------------------------------------------------
        $mitra     = $limbah->mitra;
        $katName   = $limbah->kategori->nama_kategori ?? 'Umum';
        $katLower  = strtolower($katName);
        $katFilter = str_contains($katLower, 'anorganik') ? 'anorganik'
                   : (str_contains($katLower, 'organik') ? 'organik' : null);

        $status   = strtolower((string) $limbah->status);
        $final    = $status === 'sudah diambil';
        $diproses = $status === 'sedang diproses';

        if ($status === 'tersedia') {
            $statusBg  = 'bg-[#C6ECCD] text-[#093A25]';
            $statusDot = 'bg-[#093A25]';
        } elseif ($diproses) {
            $statusBg  = 'bg-[#FDECE8] text-[#C4411E]';
            $statusDot = 'bg-[#C4411E]';
        } else {
            $statusBg  = 'bg-[#EBEFEA] text-gray-600';
            $statusDot = 'bg-gray-500';
        }

        $kode = 'LMB-' . ($limbah->created_at ?? now())->format('Y') . '-' . str_pad($limbah->id, 3, '0', STR_PAD_LEFT);

        $mitraNama = $mitra?->nama_instansi ?: ($mitra?->name ?: 'Mitra Sirkula');
        $wilayah   = $mitra?->kelurahan ?: null;

        $angka  = rtrim(rtrim(number_format((float) $limbah->estimasi_volume, 2, ',', '.'), '0'), ',');
        $satuan = match (strtolower((string) $limbah->satuan)) {
            'kg' => 'KG', 'ton' => 'Ton', 'liter' => 'Liter', 'pcs' => 'Unit',
            default => strtoupper((string) $limbah->satuan),
        };

        $diperbarui = $limbah->updated_at?->locale('id')->diffForHumans();
        $tglCatat   = $limbah->tanggal_pencatatan
            ? \Carbon\Carbon::parse($limbah->tanggal_pencatatan)->locale('id')->translatedFormat('d F Y')
            : null;

        // Koordinat & peta
        $lat = $limbah->latitude;
        $lng = $limbah->longitude;
        $punyaKoordinat = $lat !== null && $lng !== null && $lat !== '' && $lng !== '';
        if ($punyaKoordinat) {
            $la = (float) $lat;
            $lo = (float) $lng;
            $bbox = implode('%2C', [
                number_format($lo - 0.005, 6, '.', ''),
                number_format($la - 0.003, 6, '.', ''),
                number_format($lo + 0.005, 6, '.', ''),
                number_format($la + 0.003, 6, '.', ''),
            ]);
            $embedUrl = 'https://www.openstreetmap.org/export/embed.html?bbox=' . $bbox
                . '&layer=mapnik&marker=' . number_format($la, 6, '.', '') . '%2C' . number_format($lo, 6, '.', '');
            $petaUrl  = 'https://www.openstreetmap.org/?mlat=' . $la . '&mlon=' . $lo . '#map=17/' . $la . '/' . $lo;
        }

        // WhatsApp (nomor milik mitra, dinormalisasi ke format internasional)
        $wa = preg_replace('/\D+/', '', (string) ($mitra?->no_whatsapp ?? ''));
        if ($wa !== '') {
            if (str_starts_with($wa, '0')) {
                $wa = '62' . substr($wa, 1);
            } elseif (str_starts_with($wa, '8')) {
                $wa = '62' . $wa;
            }
        }
        $pesanWa = "Halo {$mitraNama}, saya melihat limbah *{$limbah->judul}* ({$kode}) dengan volume {$angka} {$satuan} di SIRKULA. "
                 . "Apakah masih tersedia dan bisa saya ambil?\n" . url()->current();
        $linkWa  = $wa !== '' ? 'https://wa.me/' . $wa . '?text=' . rawurlencode($pesanWa) : null;
        $bisaHubungi = $linkWa && ! $final;

        // Tombol kembali: pertahankan filter & halaman katalog bila datang dari sana
        $prev = url()->previous();
        $kembaliUrl = str_starts_with($prev, route('pasar.index')) ? $prev : route('pasar.index');

        $mailLapor = 'mailto:' . config('mail.from.address')
            . '?subject=' . rawurlencode('Laporan ketidakakuratan data ' . $kode)
            . '&body=' . rawurlencode("Halaman: " . url()->current() . "\n\nJelaskan ketidakakuratan yang Anda temukan:\n");
    @endphp

    <div class="bg-[#F4F8F3] min-h-screen pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">

            <!-- ========================================== -->
            <!-- BREADCRUMB + KEMBALI                        -->
            <!-- ========================================== -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <nav class="flex flex-wrap items-center gap-1.5 text-xs text-gray-600" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}" class="hover:text-[#093A25] hover:underline">Beranda</a>
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('pasar.index') }}" class="hover:text-[#093A25] hover:underline">Jelajahi Limbah</a>
                    <span class="text-gray-400">/</span>
                    @if ($katFilter)
                        <a href="{{ route('pasar.index', ['kategori' => $katFilter]) }}" class="hover:text-[#093A25] hover:underline">{{ $katName }}</a>
                    @else
                        <span>{{ $katName }}</span>
                    @endif
                    <span class="text-gray-400">/</span>
                    <span class="font-semibold text-[#093A25] line-clamp-1">{{ $limbah->judul }}</span>
                </nav>

                <a href="{{ $kembaliUrl }}"
                   class="inline-flex items-center gap-2 self-start sm:self-auto bg-[#EBEFEA] hover:bg-[#E0E7DF] text-[#093A25] text-xs font-semibold px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Jelajahi Limbah
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-[1.45fr_1fr] gap-6 items-start">

                <!-- ====================================== -->
                <!-- KOLOM KIRI                              -->
                <!-- ====================================== -->
                <div class="space-y-6 min-w-0">

                    <!-- Foto -->
                    <div class="bg-white rounded-3xl p-4 shadow-sm border border-[#EBEFEA]">
                        <div class="relative overflow-hidden rounded-2xl bg-gray-100 aspect-[4/3]">
                            @if ($limbah->foto_limbah)
                                <button type="button" id="btn-foto" class="absolute inset-0 w-full h-full cursor-zoom-in" aria-label="Perbesar foto">
                                    <img src="{{ asset('storage/' . $limbah->foto_limbah) }}" alt="{{ $limbah->judul }}"
                                         class="h-full w-full object-cover {{ $final ? 'grayscale-[60%] opacity-90' : '' }}">
                                </button>
                            @else
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-14 h-14 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-sm">Tidak ada foto tersedia</span>
                                </div>
                            @endif

                            <span class="absolute left-3 top-3 inline-flex items-center gap-1.5 rounded-lg bg-[#0B2E20]/90 px-3 py-1.5 font-mono text-[10px] font-bold uppercase tracking-wider text-white shadow-sm pointer-events-none">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h1.5l1.2-2h8.6l1.2 2H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><circle cx="12" cy="13" r="3.5"/></svg>
                                Foto dari Mitra
                            </span>

                            <span class="absolute right-3 bottom-3 rounded-md bg-[#0B2E20]/85 px-2.5 py-1 font-mono text-[10px] font-semibold text-white pointer-events-none">
                                Batch #{{ $kode }}
                            </span>
                        </div>
                    </div>

                    <!-- Tentang limbah -->
                    <section class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-[#EBEFEA]">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <h2 class="text-xl font-bold text-[#093A25]">Tentang Limbah Ini</h2>
                            <span class="shrink-0 rounded-md bg-[#EBEFEA] px-2.5 py-1 font-mono text-[10px] font-semibold text-gray-600">KODE: {{ $kode }}</span>
                        </div>
                        <p class="text-sm md:text-base text-[#414943] leading-relaxed whitespace-pre-line">{{ $limbah->deskripsi }}</p>
                    </section>

                    <!-- Spesifikasi -->
                    <section class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-[#EBEFEA]">
                        <h2 class="flex items-center gap-2.5 text-xl font-bold text-[#093A25] mb-5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 7h10M18 7h2M4 17h2M10 17h10"/><circle cx="16" cy="7" r="2"/><circle cx="8" cy="17" r="2"/></svg>
                            Spesifikasi Material &amp; Teknis
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="rounded-2xl bg-[#EEF3EE] p-4">
                                <p class="font-mono text-[10px] uppercase tracking-widest text-gray-500">Jenis Material</p>
                                <p class="mt-1.5 text-base font-bold text-gray-900">{{ $katName }}</p>
                                <p class="mt-0.5 text-xs text-gray-600 line-clamp-1">{{ $limbah->judul }}</p>
                            </div>
                            <div class="rounded-2xl bg-[#EEF3EE] p-4">
                                <p class="font-mono text-[10px] uppercase tracking-widest text-gray-500">Estimasi Volume</p>
                                <p class="mt-1.5 text-base font-bold text-gray-900">{{ $angka }} {{ $satuan }}</p>
                                <p class="mt-0.5 text-xs text-gray-600">Estimasi dari pencatatan mitra</p>
                            </div>
                            <div class="rounded-2xl bg-[#EEF3EE] p-4">
                                <p class="font-mono text-[10px] uppercase tracking-widest text-gray-500">Tanggal Pencatatan</p>
                                <p class="mt-1.5 text-base font-bold text-gray-900">{{ $tglCatat ?? '-' }}</p>
                                <p class="mt-0.5 text-xs text-gray-600">Dipublikasikan di SIRKULA</p>
                            </div>
                            <div class="rounded-2xl bg-[#EEF3EE] p-4">
                                <p class="font-mono text-[10px] uppercase tracking-widest text-gray-500">Status Ketersediaan</p>
                                <p class="mt-1.5 text-base font-bold text-gray-900">{{ ucwords($limbah->status) }}</p>
                                <p class="mt-0.5 text-xs text-gray-600">Diperbarui {{ $diperbarui ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-between gap-3 rounded-xl bg-[#EEF3EE] px-4 py-3">
                            <p class="flex items-center gap-2.5 text-sm text-gray-800">
                                <svg class="w-5 h-5 shrink-0 text-gray-600" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h11v9H3zM14 10h4l3 3v3h-7M7 19a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM17 19a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/></svg>
                                <span><span class="font-medium">Syarat Pengambilan:</span> disepakati langsung dengan mitra</span>
                            </p>
                            <span class="shrink-0 rounded-full bg-[#C6ECCD] px-2.5 py-0.5 font-mono text-[10px] font-bold text-[#093A25]">Pick-up Langsung</span>
                        </div>
                    </section>

                    <!-- Lokasi -->
                    <section id="lokasi" class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-[#EBEFEA] scroll-mt-24">
                        <h2 class="flex items-center gap-2.5 text-xl font-bold text-[#093A25] mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.686 7-11a7 7 0 10-14 0c0 5.314 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                            Aksesibilitas Lokasi &amp; Muat
                        </h2>

                        @if ($mitra?->alamat_lengkap)
                            <p class="text-sm text-[#414943] leading-relaxed mb-4">
                                <span class="font-semibold text-gray-800">Alamat penyedia:</span> {{ $mitra->alamat_lengkap }}
                            </p>
                        @endif

                        @if ($punyaKoordinat)
                            <div class="overflow-hidden rounded-2xl border border-[#EBEFEA]">
                                <iframe src="{{ $embedUrl }}" title="Peta lokasi penjemputan" loading="lazy"
                                        class="w-full h-64 border-0" referrerpolicy="no-referrer"></iframe>
                            </div>
                            <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-xs">
                                <span class="font-mono text-gray-500">{{ number_format($la, 5, '.', '') }}, {{ number_format($lo, 5, '.', '') }}</span>
                                <a href="{{ $petaUrl }}" target="_blank" rel="noopener" class="font-semibold text-[#093A25] hover:underline">Buka peta lebih besar &nearr;</a>
                            </div>
                        @else
                            <p class="rounded-xl bg-[#EEF3EE] px-4 py-3 text-sm text-gray-600">Titik koordinat penjemputan belum dicantumkan oleh mitra. Tanyakan lokasi pastinya saat menghubungi mitra.</p>
                        @endif
                    </section>
                </div>

                <!-- ====================================== -->
                <!-- KOLOM KANAN                             -->
                <!-- ====================================== -->
                <aside class="space-y-4 min-w-0">
                    <div class="bg-white rounded-3xl p-6 shadow-md border border-[#EBEFEA]">

                        <!-- Pill -->
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-full bg-[#EBEFEA] px-3 py-1.5 font-mono text-[11px] font-bold uppercase tracking-wide text-[#093A25]">{{ $katName }}</span>
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 font-mono text-[11px] font-bold {{ $statusBg }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>{{ ucwords($limbah->status) }}
                                </span>
                            </div>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#C6ECCD] px-3 py-1.5 text-[11px] font-bold text-[#093A25]">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 12.5l2.5 2.5L16 9.5"/></svg>
                                Mitra Terverifikasi
                            </span>
                        </div>

                        <!-- Judul -->
                        <h1 class="mt-5 text-3xl font-extrabold leading-tight text-[#093A25]">{{ $limbah->judul }}</h1>
                        <p class="mt-2 flex flex-wrap items-center gap-x-2 gap-y-1 font-mono text-[11px] text-gray-600">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 2"/></svg>
                                Diperbarui {{ $diperbarui ?? '-' }}
                            </span>
                            @if ($wilayah)
                                <span class="text-gray-400">&bull;</span>
                                <span>{{ $wilayah }}</span>
                            @endif
                        </p>

                        <!-- Volume & biaya -->
                        <div class="mt-5 rounded-2xl bg-[#EEF3EE] p-5">
                            <p class="font-mono text-[10px] uppercase tracking-widest text-gray-500">{{ $final ? 'Volume Siklus' : 'Volume Tersedia' }}</p>
                            <p class="mt-1 text-2xl font-extrabold text-[#093A25]">{{ $angka }} {{ $satuan }}</p>

                            <div class="mt-5">
                                <p class="font-mono text-[10px] uppercase tracking-widest text-gray-500">Estimasi Biaya / Kompensasi</p>
                                <p class="mt-1 text-base font-bold text-gray-900">Sesuai Kesepakatan</p>
                                <p class="mt-1 text-xs italic text-gray-600">(Dinegosiasikan langsung antara Anda dan mitra)</p>
                            </div>
                        </div>

                        <!-- Lokasi ringkas -->
                        <div class="mt-5 flex gap-3">
                            <svg class="w-5 h-5 mt-0.5 shrink-0 text-[#093A25]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.686 7-11a7 7 0 10-14 0c0 5.314 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                            <div class="text-sm">
                                <p class="font-semibold text-gray-900">{{ $wilayah ?? 'Lokasi belum diatur' }}</p>
                                @if ($mitra?->kode_pos)
                                    <p class="text-xs text-gray-600">Kode pos {{ $mitra->kode_pos }}</p>
                                @endif
                                <a href="#lokasi" class="mt-1 inline-block text-xs font-medium text-[#1A6B45] hover:underline">Lihat titik penjemputan di peta &darr;</a>
                            </div>
                        </div>

                        <!-- Kartu mitra -->
                        <div class="mt-5 rounded-2xl bg-[#EEF3EE] p-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#093A25] text-lg font-bold text-white">
                                    {{ strtoupper(mb_substr($mitraNama, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="flex items-center gap-1.5 font-bold text-gray-900">
                                        <span class="truncate">{{ $mitraNama }}</span>
                                        <svg class="w-4 h-4 shrink-0 text-[#1A6B45]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 12.5l2.5 2.5L16 9.5"/></svg>
                                    </p>
                                    <p class="text-xs text-gray-600">
                                        Mitra SIRKULA
                                        @if ($mitra?->created_at)
                                            &bull; Bergabung {{ $mitra->created_at->locale('id')->translatedFormat('F Y') }}
                                        @endif
                                    </p>
                                </div>
                            </div>

                            @if (isset($sirkulasiSukses) || isset($batchAktif))
                                <div class="mt-4 grid grid-cols-2 gap-3 text-xs text-gray-800">
                                    @isset($sirkulasiSukses)
                                        <p class="flex items-center gap-2">
                                            <svg class="w-4 h-4 shrink-0 text-[#1A6B45]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l8 3v6c0 4.5-3.2 8-8 9-4.8-1-8-4.5-8-9V6l8-3zM8.5 12l2.5 2.5L15.5 10"/></svg>
                                            {{ $sirkulasiSukses }}x Sirkulasi Sukses
                                        </p>
                                    @endisset
                                    @isset($batchAktif)
                                        <p class="flex items-center gap-2">
                                            <svg class="w-4 h-4 shrink-0 text-[#1A6B45]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16v3H4zM5 10v9h14v-9M10 14h4"/></svg>
                                            {{ $batchAktif }} Batch Aktif
                                        </p>
                                    @endisset
                                </div>
                            @endif
                        </div>

                        <!-- Peringatan status -->
                        @if ($final)
                            <p class="mt-5 rounded-xl bg-[#EBEFEA] px-4 py-3 text-xs text-gray-700">
                                Batch ini sudah selesai diambil oleh mitra pendaur ulang, sehingga tidak dapat dihubungi lagi.
                            </p>
                        @elseif ($diproses)
                            <p class="mt-5 rounded-xl bg-[#FDECE8] px-4 py-3 text-xs text-[#C4411E]">
                                Batch ini sedang dalam tahap konfirmasi dengan recycler lain. Ketersediaan bisa berubah sewaktu-waktu.
                            </p>
                        @endif

                        <!-- CTA -->
                        @if ($bisaHubungi)
                            <a href="{{ $linkWa }}" target="_blank" rel="noopener"
                               class="mt-5 flex w-full items-center justify-center gap-2.5 rounded-xl bg-[#0B3A27] py-4 text-base font-bold text-white shadow-md transition-colors hover:bg-[#093A25]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16v11H9l-5 4V5zM8 9h8M8 12h5"/></svg>
                                Hubungi Mitra via WhatsApp
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M8 7h9v9"/></svg>
                            </a>
                        @else
                            <span class="mt-5 flex w-full cursor-not-allowed items-center justify-center gap-2.5 rounded-xl bg-gray-200 py-4 text-base font-bold text-gray-500" aria-disabled="true">
                                Hubungi Mitra via WhatsApp
                            </span>
                            @if (! $final)
                                <p class="mt-2 text-center text-xs text-gray-500">Mitra belum mencantumkan nomor WhatsApp.</p>
                            @endif
                        @endif

                        <!-- Catatan penting -->
                        <div class="mt-4 flex gap-2.5 rounded-xl bg-[#EEF3EE] p-4 text-xs leading-relaxed text-gray-700">
                            <svg class="w-4 h-4 mt-0.5 shrink-0 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 11v5M12 8h.01"/></svg>
                            <p><strong>PENTING:</strong> Transaksi, negosiasi harga, dan koordinasi jadwal pengambilan dilakukan langsung antara Anda dan pihak mitra. SIRKULA tidak memproses pembayaran atau logistik.</p>
                        </div>

                        <!-- Aksi sekunder -->
                        <div class="mt-5 flex items-center justify-between text-xs text-gray-700">
                            <button type="button" id="btn-share" class="inline-flex items-center gap-2 hover:text-[#093A25]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="6" cy="12" r="2.5"/><circle cx="18" cy="6" r="2.5"/><circle cx="18" cy="18" r="2.5"/><path stroke-linecap="round" d="M8.2 10.8l7.6-3.6M8.2 13.2l7.6 3.6"/></svg>
                                <span id="share-label">Bagikan Informasi</span>
                            </button>
                            <a href="{{ $mailLapor }}" class="inline-flex items-center gap-2 hover:text-red-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 21V4M5 4h11l-2 4 2 4H5"/></svg>
                                Laporkan Tidak Akurat
                            </a>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 rounded-2xl bg-[#E8EFE8] px-5 py-4 text-sm text-gray-700">
                        <svg class="w-5 h-5 shrink-0 text-[#093A25]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l8 3v6c0 4.5-3.2 8-8 9-4.8-1-8-4.5-8-9V6l8-3z"/></svg>
                        <p>Data diverifikasi secara berkala oleh tim kurasi <strong>SIRKULA</strong>.</p>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <!-- Lightbox foto -->
    @if ($limbah->foto_limbah)
        <dialog id="dlg-foto" class="w-full max-w-4xl rounded-2xl bg-transparent p-0 backdrop:bg-black/70">
            <img src="{{ asset('storage/' . $limbah->foto_limbah) }}" alt="{{ $limbah->judul }}" class="max-h-[85vh] w-full rounded-2xl object-contain bg-black/20">
        </dialog>
    @endif

    <script>
        (function () {
            // Perbesar foto
            const btnFoto = document.getElementById('btn-foto');
            const dlgFoto = document.getElementById('dlg-foto');
            if (btnFoto && dlgFoto) {
                btnFoto.addEventListener('click', function () { dlgFoto.showModal(); });
                dlgFoto.addEventListener('click', function () { dlgFoto.close(); });
            }

            // Bagikan informasi
            const btnShare = document.getElementById('btn-share');
            const label = document.getElementById('share-label');
            if (btnShare) {
                btnShare.addEventListener('click', async function () {
                    const data = {
                        title: @json($limbah->judul . ' | SIRKULA'),
                        text: @json('Lihat ketersediaan limbah ' . $limbah->judul . ' di SIRKULA'),
                        url: window.location.href
                    };

                    try {
                        if (navigator.share) {
                            await navigator.share(data);
                            return;
                        }
                    } catch (e) {
                        if (e && e.name === 'AbortError') return;
                    }

                    try {
                        await navigator.clipboard.writeText(window.location.href);
                        label.textContent = 'Tautan disalin ✓';
                        setTimeout(function () { label.textContent = 'Bagikan Informasi'; }, 2000);
                    } catch (e) {
                        window.prompt('Salin tautan ini:', window.location.href);
                    }
                });
            }
        })();
    </script>
@endsection