@extends('layouts.app')

@section('content')
    <div class="bg-[#F6FBF5] min-h-screen pb-24 pt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ========================================== --}}
            {{-- HEADER --}}
            {{-- ========================================== --}}
            <div class="mb-10">
                <div
                    class="flex items-center gap-2 text-[10px] font-mono font-bold uppercase tracking-widest text-gray-400 mb-3">
                    <a href="{{ route('pasar.index') }}" class="hover:text-[#093A25] transition-colors">Beranda</a>
                    <span>/</span>
                    <span class="text-[#093A25]">Daftarkan Permintaan</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#093A25] tracking-tight mb-2">
                    Form Daftarkan Permintaan Material
                </h1>
                <p class="text-sm text-[#414943]">
                    Butuh pasokan limbah rutin? Publikasikan spesifikasi material sirkular yang Anda cari agar terhubung
                    dengan mitra penyedia terbaik.
                </p>
            </div>

            {{-- ========================================== --}}
            {{-- FORM & PRATINJAU AREA --}}
            {{-- ========================================== --}}
            <!-- x-data Alpine.js untuk fitur Live Preview disesuaikan defaultnya -->
            <div class="flex flex-col lg:flex-row gap-8 items-start" x-data="{
                namaMaterial: '',
                kategori: 'Limbah Organik',
                volume: '',
                satuan: 'KG',
                frekuensi: 'Mingguan',
                lokasi: ''
            }">

                {{-- Kolom Kiri: Form Utama --}}
                <div class="w-full lg:w-2/3 bg-white rounded-2xl border border-[#EBEFEA] shadow-sm p-6 md:p-10">
                    <h2 class="text-xl font-bold text-[#093A25] mb-8">Formulir Spesifikasi Kebutuhan</h2>

                    <form action="{{ route('permintaan.store') }}" method="POST" class="space-y-6"> @csrf

                        <!-- Nama Material -->
                        <div>
                            <label class="block text-sm font-bold text-[#093A25] mb-2">Nama Material yang Dicari <span
                                    class="text-red-500">*</span></label>
                            <input type="text" x-model="namaMaterial"
                                placeholder="Contoh: Ampas Kelapa Kering industri briket"
                                class="w-full bg-white border border-[#EBEFEA] rounded-xl py-3 px-4 text-sm text-[#414943] focus:ring-2 focus:ring-[#093A25] focus:border-[#093A25] outline-none transition-all">
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label class="block text-sm font-bold text-[#093A25] mb-2">Kategori Limbah <span
                                    class="text-red-500">*</span></label>
                            <div class="flex flex-wrap gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="kategori" value="Limbah Organik" x-model="kategori"
                                        class="peer sr-only">
                                    <span
                                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border text-sm font-medium transition-all peer-checked:bg-[#F0F5F0] peer-checked:border-[#093A25] peer-checked:text-[#093A25] border-[#EBEFEA] text-gray-500">
                                        <span
                                            class="w-2.5 h-2.5 rounded-full border border-gray-300 peer-checked:bg-[#093A25] peer-checked:border-[#093A25]"></span>
                                        Limbah Organik
                                    </span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="kategori" value="Limbah Anorganik" x-model="kategori"
                                        class="peer sr-only">
                                    <span
                                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border text-sm font-medium transition-all peer-checked:bg-[#F0F5F0] peer-checked:border-[#093A25] peer-checked:text-[#093A25] border-[#EBEFEA] text-gray-500">
                                        <span
                                            class="w-2.5 h-2.5 rounded-full border border-gray-300 peer-checked:bg-[#093A25] peer-checked:border-[#093A25]"></span>
                                        Limbah Anorganik
                                    </span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="kategori" value="Limbah B3" x-model="kategori"
                                        class="peer sr-only">
                                    <span
                                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border text-sm font-medium transition-all peer-checked:bg-[#F0F5F0] peer-checked:border-[#093A25] peer-checked:text-[#093A25] border-[#EBEFEA] text-gray-500">
                                        <span
                                            class="w-2.5 h-2.5 rounded-full border border-gray-300 peer-checked:bg-[#093A25] peer-checked:border-[#093A25]"></span>
                                        Limbah B3
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Volume & Satuan -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-[#093A25] mb-2">Estimasi Volume <span
                                        class="text-red-500">*</span></label>
                                <input type="number" x-model="volume" placeholder="Contoh: 500"
                                    class="w-full bg-white border border-[#EBEFEA] rounded-xl py-3 px-4 text-sm text-[#414943] focus:ring-2 focus:ring-[#093A25] focus:border-[#093A25] outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-[#093A25] mb-2">Satuan <span
                                        class="text-red-500">*</span></label>
                                <select x-model="satuan"
                                    class="w-full bg-white border border-[#EBEFEA] rounded-xl py-3 px-4 text-sm text-[#414943] focus:ring-2 focus:ring-[#093A25] focus:border-[#093A25] outline-none cursor-pointer transition-all">
                                    <option value="KG">KG</option>
                                    <option value="Ton">Ton</option>
                                    <option value="Liter">Liter</option>
                                    <option value="Unit">Unit</option>
                                </select>
                            </div>
                        </div>

                        <!-- Frekuensi -->
                        <div>
                            <label class="block text-sm font-bold text-[#093A25] mb-2">Frekuensi Kebutuhan</label>
                            <select x-model="frekuensi"
                                class="w-full bg-white border border-[#EBEFEA] rounded-xl py-3 px-4 text-sm text-[#414943] focus:ring-2 focus:ring-[#093A25] focus:border-[#093A25] outline-none cursor-pointer transition-all">
                                <option value="Harian">Harian</option>
                                <option value="Mingguan">Mingguan</option>
                                <option value="Bulanan">Bulanan</option>
                                <option value="Satu Kali Saja">Satu Kali Saja</option>
                            </select>
                        </div>

                        <!-- Lokasi -->
                        <div>
                            <label class="block text-sm font-bold text-[#093A25] mb-2">Lokasi Pengambilan Preferensi
                                (Provinsi/Kota) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <svg class="absolute left-4 top-3.5 w-4 h-4 text-gray-400" fill="none"
                                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <input type="text" x-model="lokasi" placeholder="Contoh: Badung, Bali (Khusus Area I)"
                                    class="w-full bg-white border border-[#EBEFEA] rounded-xl py-3 pl-11 pr-4 text-sm text-[#414943] focus:ring-2 focus:ring-[#093A25] focus:border-[#093A25] outline-none transition-all">
                            </div>
                        </div>

                        <!-- Deskripsi Detail -->
                        <div>
                            <label class="block text-sm font-bold text-[#093A25] mb-2">Deskripsi Kebutuhan Detail</label>
                            <textarea rows="4"
                                placeholder="Tuliskan standar kebersihan material, kemasan penampung (karung/drum), atau rute logistik armada angkut yang disediakan..."
                                class="w-full bg-white border border-[#EBEFEA] rounded-xl py-3 px-4 text-sm text-[#414943] focus:ring-2 focus:ring-[#093A25] focus:border-[#093A25] outline-none transition-all resize-none"></textarea>
                        </div>

                        <!-- WhatsApp -->
                        <div>
                            <label class="block text-sm font-bold text-[#093A25] mb-2">Nomor WhatsApp PIC Operasional <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <svg class="absolute left-4 top-3.5 w-4 h-4 text-gray-400" fill="none"
                                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <input type="text" placeholder="0812XXXXXXXX"
                                    class="w-full bg-white border border-[#EBEFEA] rounded-xl py-3 pl-11 pr-4 text-sm text-[#414943] focus:ring-2 focus:ring-[#093A25] focus:border-[#093A25] outline-none transition-all">
                            </div>
                        </div>

                        <div
                            class="pt-6 border-t border-[#EBEFEA] flex flex-col sm:flex-row items-center justify-between gap-4">
                            <span class="text-xs text-gray-500"><span class="text-red-500">*</span> Menandakan kolom wajib
                                diisi</span>
                            <button type="button"
                                class="w-full sm:w-auto flex items-center justify-center gap-2 bg-[#1A4B36] text-white px-8 py-3.5 rounded-xl font-bold text-sm hover:bg-[#093A25] transition-colors shadow-sm">
                                Publikasikan Permintaan &rarr;
                            </button>
                        </div>

                    </form>
                </div>

                {{-- Kolom Kanan: Pratinjau & Tips --}}
                <div class="w-full lg:w-1/3 flex flex-col gap-6 sticky top-24">

                    <!-- Pratinjau Live Card -->
                    <div class="bg-white rounded-2xl border border-[#EBEFEA] shadow-sm p-6">
                        <span
                            class="block font-mono text-[10px] font-bold uppercase tracking-widest text-[#1A4B36] mb-4">Pratinjau
                            Katalog Live</span>

                        <h3 class="text-lg font-bold text-[#093A25] mb-2 truncate"
                            x-text="namaMaterial === '' ? 'Ampas Kelapa Kering...' : namaMaterial">Ampas Kelapa Kering...
                        </h3>
                        <span
                            class="inline-block bg-[#F0F5F0] text-[#093A25] px-2 py-1 rounded font-mono text-[9px] font-bold uppercase tracking-widest mb-4 border border-[#EBEFEA]"
                            x-text="kategori">ORGANIK</span>

                        <div class="border-t border-[#EBEFEA] pt-4 space-y-2 text-sm text-gray-600">
                            <p>Volume: <span x-text="volume === '' ? '500' : volume">500</span> <span
                                    x-text="satuan">KG</span> (<span x-text="frekuensi">Mingguan</span>)</p>
                            <p class="truncate">Lokasi: <span x-text="lokasi === '' ? 'Badung, Bali' : lokasi">Badung,
                                    Bali</span></p>
                        </div>
                    </div>

                    <!-- Tips Mengisi -->
                    <div class="bg-[#F0F5F0] rounded-2xl p-6 border border-[#EBEFEA]">
                        <h4 class="text-sm font-bold text-[#093A25] mb-3">Tips Mengisi Permintaan</h4>
                        <ul class="space-y-3 text-xs text-[#414943] leading-relaxed">
                            <li>&bull; Sebutkan batas kontaminasi yang diizinkan (misalnya: bebas minyak, kadar air rendah).
                            </li>
                            <li>&bull; Jelaskan jika armada Anda sendiri yang menjemput atau membutuhkan pengiriman ke
                                gudang.</li>
                        </ul>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
