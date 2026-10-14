@extends('layouts.app')

@section("content")
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <div class="max-w-2xl w-full text-center relative z-10">
            <!-- Error Code -->
            <div class="mb-8">
                <h1 class="font-mono text-[120px] sm:text-[160px] font-semibold text-sirkula leading-none tracking-tighter">
                    404
                </h1>
                <div class="w-24 h-1 bg-sirkula mx-auto rounded-full mt-4"></div>
            </div>

            <!-- Message -->
            <div class="space-y-4 mb-10">
                <h2 class="text-3xl sm:text-4xl font-semibold text-sirkula tracking-tight">
                    Halaman Tidak Ditemukan
                </h2>
                <p class="text-gray-600 max-w-md mx-auto text-lg leading-relaxed">
                    Sepertinya material yang Anda cari sudah didaur ulang atau tidak pernah ada di sistem kami.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ url('/') }}"
                class="inline-flex items-center gap-2 px-8 py-3 bg-[#093A25] text-white rounded-lg font-medium border-2 border-transparent transition-all hover:bg-transparent hover:text-[#093A25] hover:border-[#093A25]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Kembali ke Beranda
                </a>

                <a href="{{ url()->previous() }}"
                class="inline-flex items-center gap-2 px-8 py-3 border-2 border-primary-500 text-primary-500 rounded-lg font-medium transition-all hover:bg-[#093A25] hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Halaman Sebelumnya
                </a>
            </div>
        </div>

        <!-- Corner Accent -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-primary-100/20 to-transparent rounded-bl-full pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-gradient-to-tr from-primary-100/20 to-transparent rounded-tr-full pointer-events-none"></div>
    </div>
@endsection
