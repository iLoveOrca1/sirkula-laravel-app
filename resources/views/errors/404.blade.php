<x-layout>
    <main>
        <x-layouts.wrapper>
            <section class="h-[calc(100dvh-8rem)] flex flex-col items-center justify-center">
                <div class="pointer-events-none absolute inset-0 mx-auto -top-20 h-100 w-300 rounded-full bg-primary-100/25 blur-2xl"></div>

                <h1 class="text-3xl text-primary-300 font-bold ">Halaman tidak ditemukan</h1>
                <a href="{{ url('/') }}">← Kembali ke Beranda</a>
            </section>
        </x-layouts.wrapper>
    </main>
</x-layout>