@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Header Petugas -->
    <div class="bg-blue-600 rounded-xl shadow-md p-6 md:p-8 flex flex-col md:flex-row items-center justify-between text-white mb-8">
        <div>
            <h2 class="text-2xl md:text-3xl font-bold flex items-center gap-2">
                <svg class="w-8 h-8 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                Panel Logistik Sirkula
            </h2>
            <p class="text-blue-100 mt-2">Kelola jadwal penjemputan dan distribusi limbah yang telah tersedia.</p>
        </div>
        <div class="mt-4 md:mt-0 text-right">
            <p class="text-sm text-blue-200">Petugas Aktif</p>
            <p class="font-bold text-lg text-white">{{ $user->name }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-md border border-green-200 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Daftar Tugas Penjemputan</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                        <th class="py-3 px-4 font-medium whitespace-nowrap">Mitra Penyedia</th>
                        <th class="py-3 px-4 font-medium whitespace-nowrap">Limbah & Volume</th>
                        <th class="py-3 px-4 font-medium whitespace-nowrap">Status Logistik</th>
                        <th class="py-3 px-4 font-medium whitespace-nowrap text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-100">
                    @forelse ($laporan as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 font-medium text-gray-900">
                                {{ $item->mitra->name ?? 'User Tidak Diketahui' }} <br>
                                <span class="text-xs text-gray-500 font-normal">Hubungi: {{ $item->mitra->email ?? '-' }}</span>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="font-bold text-gray-800">{{ $item->judul }}</span> <br>
                                <span class="font-medium text-blue-600">{{ $item->estimasi_volume }} {{ $item->satuan }}</span>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if(strtolower($item->status) == 'tersedia')
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full border border-green-200">Menunggu Jemputan</span>
                                @elseif(strtolower($item->status) == 'sedang dijemput')
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full border border-blue-200 animate-pulse">🚗 Sedang Dijemput</span>
                                @elseif(strtolower($item->status) == 'selesai')
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-gray-100 text-gray-700 rounded-full border border-gray-200">✅ Selesai</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-center flex justify-center">
                                @if(strtolower($item->status) == 'tersedia')
                                    <form action="{{ route('petugas.update_status', $item->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="Sedang Dijemput">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-sm">
                                            Ambil Tugas
                                        </button>
                                    </form>
                                @elseif(strtolower($item->status) == 'sedang dijemput')
                                    <form action="{{ route('petugas.update_status', $item->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="Selesai">
                                        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-sm">
                                            Selesaikan
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 italic text-xs">Telah didistribusikan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-500 italic">
                                Belum ada limbah yang siap dijemput.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection