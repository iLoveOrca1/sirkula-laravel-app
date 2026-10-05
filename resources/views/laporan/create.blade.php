@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Buat Laporan Limbah</h2>
            <p class="text-gray-500 mt-2">Isi detail limbah yang ingin Anda laporkan. Admin akan meninjau kelayakannya
                sebelum dipublikasikan ke pasar Sirkula.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
            <form action="{{ route('laporan.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Kategori Limbah -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Limbah <span
                            class="text-red-500">*</span></label>
                    <select name="kategori_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:ring-sirkula-green focus:border-sirkula-green outline-none">
                        <option value="" disabled selected>-- Pilih Kategori --</option>
                        @foreach ($kategori as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Pencatatan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pencatatan <span
                            class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_pencatatan" value="{{ date('Y-m-d') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-sirkula-green focus:border-sirkula-green outline-none">
                </div> 

                <!-- Volume dan Satuan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <!-- PERBAIKAN: Mengubah name="estimasi_volume" menjadi "volume_dihasilkan" -->
                        <label class="block text-sm font-medium text-gray-700 mb-1">Volume Dihasilkan <span
                                class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="volume_dihasilkan" placeholder="Contoh: 50.5" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-sirkula-green focus:border-sirkula-green outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Satuan <span
                                class="text-red-500">*</span></label>
                        <select name="satuan" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:ring-sirkula-green focus:border-sirkula-green outline-none">
                            <option value="kg">Kilogram (kg)</option>
                            <option value="ton">Ton</option>
                            <option value="liter">Liter</option>
                            <option value="pcs">Pieces (Pcs)</option>
                        </select>
                    </div>
                </div>

                <!-- Deskripsi Detail / Catatan Internal -->
                <div>
                    <!-- PERBAIKAN: Mengubah name="deskripsi" menjadi "catatan_internal" -->
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Internal / Deskripsi <span
                            class="text-red-500">*</span></label>
                    <textarea name="catatan_internal" rows="4"
                        placeholder="Jelaskan kondisi fisik limbah, asal limbah, dan cara penyimpanannya saat ini..." required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-sirkula-green focus:border-sirkula-green outline-none"></textarea>
                </div>

                <!-- Tombol Aksi -->
                <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                    <a href="/dashboard"
                        class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-sirkula-green text-white rounded-md hover:bg-sirkula-green-hover font-medium transition-colors">
                        Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection