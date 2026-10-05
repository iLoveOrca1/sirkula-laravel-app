@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Header Pasar -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Pasar Sirkula</h2>
        <p class="text-gray-500 mt-2">Temukan dan beli limbah berkualitas yang telah diverifikasi untuk kebutuhan daur ulang atau produksi Anda.</p>
    </div>

    <!-- Grid Katalog Limbah -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($limbahTersedia as $item)
            
            <!-- MEMANGGIL FILE SLICING KARTU -->
            @include('pasar.partials.card', ['item' => $item])

        @empty
            <div class="col-span-full bg-white p-10 text-center rounded-xl shadow-sm border border-gray-100">
                <div class="text-5xl mb-4">🍃</div>
                <h3 class="text-lg font-bold text-gray-800">Pasar Sedang Kosong</h3>
                <p class="text-gray-500 mt-2">Belum ada limbah yang tersedia atau disetujui saat ini. Silakan kembali lagi nanti.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection