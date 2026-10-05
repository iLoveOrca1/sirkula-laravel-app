@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- Header Dashboard -->
        <div
            class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8 flex flex-col md:flex-row items-center justify-between">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Dashboard {{ ucfirst($user->role) }}</h2>
                <p class="text-gray-500 mt-2">Selamat datang kembali, <span
                        class="font-semibold text-sirkula-green">{{ $user->name }}</span>!</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="px-4 py-2 bg-gray-100 text-gray-600 rounded-full text-sm font-medium border border-gray-200">
                    Status: <span class="text-green-600">Aktif</span>
                </span>
            </div>
        </div>

        <!-- Grid Menu Berdasarkan Role -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @if ($user->role == 'mitra')
                <!-- Menu 1: Lapor Limbah (Khusus Mitra) -->
                <div
                    class="bg-white p-6 rounded-xl border border-green-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-green-50 rounded-bl-full -mr-8 -mt-8"></div>
                    <h3 class="font-bold text-gray-800 text-lg">Form Pelaporan Limbah</h3>
                    <p class="text-sm text-gray-500 mt-2 mb-5">Laporkan data limbah terbaru dari instansi Anda agar bisa
                        direview oleh Admin.</p>
                    <a href="{{ route('laporan.create') }}"
                        class="inline-block bg-sirkula-green text-white px-5 py-2.5 rounded-md text-sm font-medium hover:bg-sirkula-green-hover transition-colors">
                        + Buat Laporan
                    </a>
                </div>

                <!-- Menu 2: Riwayat Laporan (Khusus Mitra) -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <h3 class="font-bold text-gray-800 text-lg">Riwayat Laporan</h3>
                    <p class="text-sm text-gray-500 mt-2 mb-5">Pantau status laporan limbah Anda (Menunggu, Disetujui, atau
                        Ditolak).</p>
                    <a href="#tabel-riwayat"
                        class="inline-block bg-white text-gray-700 border border-gray-300 px-5 py-2.5 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors">
                        Lihat Ke Bawah ↓
                    </a>
                </div>
            @else
                <!-- Menu 1: Jelajah Limbah (Khusus Pembeli) -->
                <div
                    class="bg-white p-6 rounded-xl border border-blue-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-blue-50 rounded-bl-full -mr-8 -mt-8"></div>
                    <h3 class="font-bold text-gray-800 text-lg">Jelajah Pasar Limbah</h3>
                    <p class="text-sm text-gray-500 mt-2 mb-5">Cari ketersediaan limbah organik dan anorganik dari berbagai
                        mitra kami.</p>
                    <a href="#"
                        class="inline-block bg-blue-600 text-white px-5 py-2.5 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors">
                        Mulai Mencari
                    </a>
                </div>
            @endif

            <!-- Menu Umum (Pengaturan Akun) -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                <h3 class="font-bold text-gray-800 text-lg">Pengaturan Profil</h3>
                <p class="text-sm text-gray-500 mt-2 mb-5">Perbarui data diri, instansi, password, dan lengkapi titik
                    koordinat alamat Anda.</p>
                <a href="#"
                    class="inline-block bg-white text-gray-700 border border-gray-300 px-5 py-2.5 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors">
                    Edit Profil
                </a>
            </div>
        </div>

        <!-- TABEL RIWAYAT LAPORAN (Hanya Tampil Untuk Mitra) -->
        @if ($user->role == 'mitra')
            <div id="tabel-riwayat" class="mt-10 bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Riwayat Laporan Saya</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                                <th class="py-3 px-4 font-medium whitespace-nowrap">Tanggal</th>
                                <th class="py-3 px-4 font-medium whitespace-nowrap">Kategori</th>
                                <th class="py-3 px-4 font-medium whitespace-nowrap">Volume</th>
                                <th class="py-3 px-4 font-medium">Catatan Internal</th>
                                <th class="py-3 px-4 font-medium whitespace-nowrap">Status</th>
                                <th class="py-3 px-4 font-medium whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700 divide-y divide-gray-100">
                            @forelse ($laporan as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($item->tanggal_pencatatan)->format('d M Y') }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $item->volume_dihasilkan }}
                                        {{ $item->satuan }}</td>
                                    <td class="py-3 px-4 max-w-xs truncate" title="{{ $item->catatan_internal }}">
                                        {{ $item->catatan_internal ?? '-' }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        @if ($item->status == 'menunggu')
                                            <span
                                                class="px-2.5 py-1 text-xs font-semibold bg-yellow-100 text-yellow-700 rounded-full border border-yellow-200">Menunggu</span>
                                        @elseif($item->status == 'disetujui')
                                            <span
                                                class="px-2.5 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full border border-green-200">Disetujui</span>
                                        @else
                                            <span
                                                class="px-2.5 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded-full border border-red-200">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap flex items-center gap-3">
                                        <a href="{{ route('laporan.edit', $item->id) }}"
                                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</a>

                                        <form action="{{ route('laporan.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 text-sm font-medium">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-500 italic">
                                        Belum ada data laporan limbah yang diajukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
@endsection
