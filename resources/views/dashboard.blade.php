@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- Header Dashboard -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8 flex flex-col md:flex-row items-center justify-between">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Dashboard {{ ucfirst($user->role) }}</h2>
                <p class="text-gray-500 mt-2">Selamat datang kembali, <span class="font-semibold text-sirkula-green">{{ $user->name }}</span>!</p>
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
                <div class="bg-white p-6 rounded-xl border border-green-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-green-50 rounded-bl-full -mr-8 -mt-8"></div>
                    <h3 class="font-bold text-gray-800 text-lg">Form Pelaporan Limbah</h3>
                    <p class="text-sm text-gray-500 mt-2 mb-5">Laporkan data limbah terbaru dari instansi Anda agar bisa direview oleh Admin.</p>
                    <a href="{{ route('laporan.create') }}" class="inline-block bg-sirkula-green text-white px-5 py-2.5 rounded-md text-sm font-medium hover:bg-green-700 transition-colors">
                        + Buat Laporan
                    </a>
                </div>

                <!-- Menu 2: Riwayat Laporan (Khusus Mitra) -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <h3 class="font-bold text-gray-800 text-lg">Riwayat Laporan</h3>
                    <p class="text-sm text-gray-500 mt-2 mb-5">Pantau status laporan limbah Anda (Menunggu, Disetujui, atau Ditolak).</p>
                    <a href="#tabel-riwayat" class="inline-block bg-white text-gray-700 border border-gray-300 px-5 py-2.5 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors">
                        Lihat Ke Bawah &darr;
                    </a>
                </div>
            @else
                <!-- Menu 1: Jelajah Limbah (Khusus Pembeli) -->
                <div class="bg-white p-6 rounded-xl border border-blue-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-blue-50 rounded-bl-full -mr-8 -mt-8"></div>
                    <h3 class="font-bold text-gray-800 text-lg">Jelajah Pasar Limbah</h3>
                    <p class="text-sm text-gray-500 mt-2 mb-5">Cari ketersediaan limbah organik dan anorganik dari berbagai mitra kami.</p>
                    <a href="{{ route('pasar.index') }}" class="inline-block bg-blue-600 text-white px-5 py-2.5 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors">
                        Mulai Mencari
                    </a>
                </div>
            @endif

            <!-- Menu Umum (Pengaturan Akun) -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                <h3 class="font-bold text-gray-800 text-lg">Pengaturan Profil</h3>
                <p class="text-sm text-gray-500 mt-2 mb-5">Perbarui data diri, instansi, password, dan lengkapi titik koordinat alamat Anda.</p>
                <a href="#" class="inline-block bg-white text-gray-700 border border-gray-300 px-5 py-2.5 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors">
                    Edit Profil
                </a>
            </div>
        </div>

        <!-- TABEL RIWAYAT LAPORAN (Hanya Tampil Untuk Mitra) -->
        @if ($user->role == 'mitra')
            <div id="tabel-riwayat" class="mt-10 bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Riwayat Laporan Saya</h3>

                <!-- Pesan Sukses -->
                @if(session('success'))
                    <div class="mb-4 bg-green-50 text-green-700 p-3 rounded-md border border-green-200 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                                <th class="py-3 px-4 font-medium whitespace-nowrap">Foto & Material</th>
                                <th class="py-3 px-4 font-medium whitespace-nowrap">Kategori</th>
                                <th class="py-3 px-4 font-medium whitespace-nowrap">Volume</th>
                                <th class="py-3 px-4 font-medium whitespace-nowrap">Tanggal</th>
                                <th class="py-3 px-4 font-medium whitespace-nowrap">Status</th>
                                <th class="py-3 px-4 font-medium whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700 divide-y divide-gray-100">
                            @forelse ($laporan as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-4 flex items-center min-w-[250px]">
                                        <!-- Menampilkan Foto -->
                                        @if($item->foto_limbah)
                                            <img src="{{ asset('storage/' . $item->foto_limbah) }}" alt="Foto" class="h-12 w-12 object-cover rounded-md border border-gray-200 mr-3">
                                        @else
                                            <div class="h-12 w-12 bg-gray-100 rounded-md border border-gray-200 flex items-center justify-center mr-3">
                                                <span class="text-[10px] text-gray-400">No Image</span>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-gray-800 truncate max-w-[150px]" title="{{ $item->judul }}">{{ $item->judul }}</p>
                                        </div>
                                    </td>
                                    
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        {{ $item->kategori->nama_kategori ?? '-' }}
                                    </td>
                                    
                                    <td class="py-3 px-4 whitespace-nowrap font-medium text-gray-800">
                                        {{ $item->estimasi_volume }} <span class="font-normal text-gray-500 text-xs">{{ $item->satuan }}</span>
                                    </td>
                                    
                                    <td class="py-3 px-4 whitespace-nowrap text-gray-500">
                                        {{ \Carbon\Carbon::parse($item->tanggal_pencatatan)->format('d M Y') }}
                                    </td>
                                    
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        @if (strtolower($item->status) == 'tersedia')
                                            <span class="px-2.5 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full border border-green-200">Tersedia</span>
                                        @elseif(strtolower($item->status) == 'sedang diproses')
                                            <span class="px-2.5 py-1 text-xs font-semibold bg-yellow-100 text-yellow-700 rounded-full border border-yellow-200">Diproses</span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-semibold bg-gray-100 text-gray-700 rounded-full border border-gray-200">{{ $item->status }}</span>
                                        @endif
                                    </td>
                                    
                                    <td class="py-3 px-4 whitespace-nowrap flex items-center gap-3">
                                        <a href="{{ route('laporan.edit', $item->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</a>
                                        <form action="{{ route('laporan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data limbah ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center">
                                        <p class="text-gray-500 text-sm mb-2">Belum ada data limbah yang dipublikasikan.</p>
                                        <a href="{{ route('laporan.create') }}" class="text-sirkula-green font-medium text-sm hover:underline">Mulai publikasikan limbah pertama Anda</a>
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