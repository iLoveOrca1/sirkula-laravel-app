@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative overflow-hidden bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32 pt-20">
            
            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                <div class="sm:text-center lg:text-left">
                    <span class="inline-block py-1 px-3 rounded-full bg-green-100 text-sirkula-green text-sm font-semibold mb-4 border border-green-200">
                        Platform Ekonomi Sirkular No. 1
                    </span>
                    <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                        <span class="block xl:inline">Ubah Limbah Menjadi</span>
                        <span class="block text-sirkula-green">Nilai Tambah Bisnis</span>
                    </h1>
                    <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        Sirkula menghubungkan penghasil limbah dengan mitra daur ulang yang tepat. Kurangi jejak karbon perusahaan Anda dan mulai langkah keberlanjutan hari ini.
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start gap-3">
                        <div class="rounded-md shadow">
                            <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-sirkula-green hover:bg-sirkula-green-hover md:py-4 md:text-lg transition-all">
                                Mulai Sekarang
                            </a>
                        </div>
                        <div class="mt-3 sm:mt-0">
                            <a href="#" class="w-full flex items-center justify-center px-8 py-3 border-2 border-gray-200 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 md:py-4 md:text-lg transition-all">
                                Pelajari Cara Kerja
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Ilustrasi Kanan (Bisa diganti dengan gambar desain Anda) -->
    <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-gray-50 border-l border-gray-100 flex items-center justify-center">
        <div class="p-12 text-center border-4 border-dashed border-gray-300 rounded-xl m-10 w-full h-3/4 flex flex-col items-center justify-center text-gray-400">
            <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <p>Area Gambar Ilustrasi Figma</p>
        </div>
    </div>
</div>
@endsection