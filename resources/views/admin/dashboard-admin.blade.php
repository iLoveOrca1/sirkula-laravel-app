@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Panel Admin Sirkula</h2>
            <p class="text-gray-500 mt-2">Selamat datang, <span class="font-semibold text-blue-600">{{ $user->name }}</span>. Anda memiliki akses penuh untuk memverifikasi laporan.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Daftar Semua Laporan Limbah</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                        <th class="py-3 px-4 font-medium whitespace-nowrap">Instansi / Mitra</th>
                        <th class="py-3 px-4 font-medium whitespace-nowrap">Kategori & Volume</th>
                        <th class="py-3 px-4 font-medium">Catatan</th>
                        <th class="py-3 px-4 font-medium whitespace-nowrap">Status Saat Ini</th>
                        <th class="py-3 px-4 font-medium whitespace-nowrap">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-100">
                    @forelse ($semuaLaporan as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 font-medium text-gray-900">
                                {{ $item->mitra->name ?? 'User Tidak Diketahui' }} <br>
                                <span class="text-xs text-gray-500 font-normal">{{ \Carbon\Carbon::parse($item->tanggal_pencatatan)->format('d M Y') }}</span>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="font-semibold">{{ $item->kategori->nama_kategori ?? '-' }}</span> <br>
                                {{ $item->volume_dihasilkan }} {{ $item->satuan }}
                            </td>
                            <td class="py-3 px-4 max-w-xs truncate" title="{{ $item->catatan_internal }}">{{ $item->catatan_internal ?? '-' }}</td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if($item->status == 'menunggu')
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-yellow-100 text-yellow-700 rounded-full border border-yellow-200">Menunggu</span>
                                @elseif($item->status == 'disetujui')
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full border border-green-200">Disetujui</span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded-full border border-red-200">Ditolak</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <!-- Hanya tampilkan tombol jika status masih 'menunggu' -->
                                @if($item->status == 'menunggu')
                                    <div class="flex gap-2">
                                        <!-- Tombol Setujui -->
                                        <form action="{{ route('laporan.update_status', $item->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="disetujui">
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-xs font-medium px-3 py-1.5 rounded transition">Setujui</button>
                                        </form>

                                        <!-- Tombol Tolak -->
                                        <form action="{{ route('laporan.update_status', $item->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="ditolak">
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs font-medium px-3 py-1.5 rounded transition">Tolak</button>
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
                                Belum ada laporan limbah yang masuk dari Mitra manapun.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection