@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Panel Admin Sirkula</h2>
                <p class="text-gray-500 mt-2">Selamat datang, <span
                        class="font-semibold text-sirkula-green">{{ $user->name }}</span>. Anda memiliki akses penuh untuk
                    memverifikasi laporan.</p>
            </div>
        </div>

        <!-- Pesan Sukses -->
        @if (session('success'))
            <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-md border border-green-200 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <!-- Pesan Error -->
        @if (session('error'))
            <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-md border border-red-200 text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-md border border-red-200 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Daftar Semua Laporan Limbah</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                            <th class="py-3 px-4 font-medium whitespace-nowrap">Instansi / Mitra</th>
                            <th class="py-3 px-4 font-medium whitespace-nowrap">Material & Volume</th>
                            <th class="py-3 px-4 font-medium">Deskripsi</th>
                            <th class="py-3 px-4 font-medium whitespace-nowrap">Status Saat Ini</th>
                            <th class="py-3 px-4 font-medium whitespace-nowrap">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700 divide-y divide-gray-100">
                        @forelse ($semuaLaporan as $item)
                            @php $statusItem = strtolower($item->status ?? ''); @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4 font-medium text-gray-900">
                                    {{ $item->mitra->name ?? 'User Tidak Diketahui' }} <br>
                                    <span class="text-xs text-gray-500 font-normal">
                                        {{ $item->tanggal_pencatatan ? \Carbon\Carbon::parse($item->tanggal_pencatatan)->format('d M Y') : '-' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <span class="font-bold text-gray-800">{{ $item->judul }}</span> <br>
                                    <span class="text-xs text-gray-500">{{ $item->kategori->nama_kategori ?? '-' }}</span>
                                    <br>
                                    <span class="font-medium">{{ $item->estimasi_volume }} {{ $item->satuan }}</span>
                                </td>
                                <td class="py-3 px-4 max-w-xs truncate" title="{{ $item->deskripsi }}">
                                    {{ $item->deskripsi ?? '-' }}</td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    @if ($statusItem === 'menunggu')
                                        <span
                                            class="px-2.5 py-1 text-xs font-semibold bg-yellow-100 text-yellow-700 rounded-full border border-yellow-200">Menunggu</span>
                                    @elseif ($statusItem === 'tersedia')
                                        <span
                                            class="px-2.5 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full border border-green-200">Tersedia</span>
                                    @else
                                        <span
                                            class="px-2.5 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded-full border border-red-200">Ditolak</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    @if ($statusItem === 'menunggu')
                                        <div class="flex gap-2">
                                            <!-- Form Tombol Setujui -->
                                            <form action="{{ route('laporan.updateStatus', $item->id) }}" method="POST"
                                                class="form-verifikasi">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="tersedia">
                                                <button type="submit"
                                                    class="bg-green-600 text-white px-3 py-1 rounded-md text-sm hover:bg-green-700 transition-colors disabled:cursor-not-allowed disabled:opacity-60">
                                                    Setujui
                                                </button>
                                            </form>

                                            <!-- Form Tombol Tolak -->
                                            <form action="{{ route('laporan.updateStatus', $item->id) }}" method="POST"
                                                class="form-verifikasi"
                                                data-confirm="Tolak laporan &quot;{{ $item->judul }}&quot;?">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="ditolak">
                                                <button type="submit"
                                                    class="bg-red-600 text-white px-3 py-1 rounded-md text-sm hover:bg-red-700 transition-colors disabled:cursor-not-allowed disabled:opacity-60">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic text-xs">Sudah diproses</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500 italic">
                                    Belum ada laporan limbah yang masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Konfirmasi sebelum menolak + cegah klik ganda saat form dikirim
        document.querySelectorAll('.form-verifikasi').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                const pesan = form.dataset.confirm;
                if (pesan && !window.confirm(pesan)) {
                    e.preventDefault();
                    return;
                }
                form.querySelectorAll('button[type="submit"]').forEach(function(btn) {
                    btn.disabled = true;
                });
            });
        });
    </script>
@endsection