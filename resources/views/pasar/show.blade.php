@extends('layouts.app')

@section('content')
<div class="bg-sirkula-light min-h-screen py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Tombol Kembali -->
        <div class="mb-6">
            <a href="{{ route('pasar.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-sirkula-green transition">
                <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Pasar
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row">
            
            <!-- Bagian Kiri: Foto Besar -->
            <div class="w-full md:w-1/2 bg-gray-50 flex items-center justify-center min-h-[300px] relative border-r border-gray-100">
                @if($limbah->foto_limbah)
                    <img src="{{ asset('storage/' . $limbah->foto_limbah) }}" alt="{{ $limbah->judul }}" class="w-full h-full object-cover">
                @else
                    <div class="text-gray-400 flex flex-col items-center">
                        <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>Tidak ada foto tersedia</span>
                    </div>
                @endif
                <div class="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1.5 rounded-lg border border-gray-200 shadow-sm text-xs font-bold text-sirkula-green uppercase tracking-wider">
                    {{ $limbah->kategori->nama_kategori ?? 'Umum' }}
                </div>
            </div>

            <!-- Bagian Kanan: Informasi Lengkap -->
            <div class="w-full md:w-1/2 p-8 md:p-10 flex flex-col">
                <div class="mb-6 border-b border-gray-100 pb-6">
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $limbah->judul }}</h1>
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Dipublikasikan {{ \Carbon\Carbon::parse($limbah->tanggal_pencatatan)->format('d M Y') }}
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Total Volume</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $limbah->estimasi_volume }} <span class="text-base font-medium text-gray-500">{{ $limbah->satuan }}</span></p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Status Ketersediaan</p>
                        <p class="text-lg font-bold {{ strtolower($limbah->status) == 'tersedia' ? 'text-green-600' : 'text-gray-600' }} capitalize">{{ $limbah->status }}</p>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="text-sm font-bold text-gray-900 mb-2">Deskripsi & Kondisi Material</h3>
                    <p class="text-gray-600 leading-relaxed text-sm whitespace-pre-line">{{ $limbah->deskripsi }}</p>
                </div>

                <div class="mt-auto">
                    <div class="flex items-center mb-5 bg-blue-50 p-4 rounded-xl border border-blue-100">
                        <div class="h-10 w-10 rounded-full bg-blue-200 text-blue-700 flex items-center justify-center font-bold text-lg mr-3">
                            {{ substr($limbah->mitra->name ?? 'M', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-xs text-blue-600 font-semibold uppercase tracking-wider mb-0.5">Disediakan Oleh</p>
                            <p class="font-bold text-gray-900 text-sm">{{ $limbah->mitra->name ?? 'Mitra Sirkula' }}</p>
                        </div>
                    </div>

                    @php
                        $pesanWa = "Halo {$limbah->mitra->name}, saya melihat limbah *{$limbah->judul}* dengan volume {$limbah->estimasi_volume} {$limbah->satuan} di Sirkula. Apakah masih tersedia dan bisa saya ambil?";
                        $linkWa = "https://wa.me/6281234567890?text=" . urlencode($pesanWa);
                    @endphp

                    <a href="{{ $linkWa }}" target="_blank" class="w-full bg-sirkula-green hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition-all shadow-md flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824z"/></svg>
                        Hubungi Mitra via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection