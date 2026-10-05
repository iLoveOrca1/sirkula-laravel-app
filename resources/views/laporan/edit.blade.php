@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Edit Laporan Limbah</h2>
            <p class="text-gray-500 mt-2">Perbarui detail laporan limbah Anda jika terdapat kesalahan pencatatan.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
            <form action="{{ route('laporan.update', $limbah->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Kategori Limbah -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Limbah <span
                            class="text-red-500">*</span></label>
                    <select name="kategori_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:ring-sirkula-green focus:border-sirkula-green outline-none">
                        <option value="" disabled>-- Pilih Kategori --</option>
                        @foreach ($kategori as $kat)
                            <!-- Menandai kategori lama sebagai 'selected' -->
                            <option value="{{ $kat->id }}" {{ $limbah->kategori_id == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Pencatatan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pencatatan <span
                            class="text-red-500">*</span></label>
                    <!-- Mengisi input dengan tanggal lama -->
                    <input type="date" name="tanggal_pencatatan" value="{{ $limbah->tanggal_pencatatan }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-sirkula-green focus:border-sirkula-green outline-none">
                </div>

                <!-- Volume dan Satuan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Volume Dihasilkan <span
                                class="text-red-500">*</span></label>
                        <!-- Mengisi input dengan volume lama -->
                        <input type="number" step="0.01" name="volume_dihasilkan" value="{{ $limbah->volume_dihasilkan }}" placeholder="Contoh: 50.5" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-sirkula-green focus:border-sirkula-green outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Satuan <span
                                class="text-red-500">*</span></label>
                        <select name="satuan" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:ring-sirkula-green focus:border-sirkula-green outline-none">
                            <option value="kg" {{ $limbah->satuan == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                            <option value="ton" {{ $limbah->satuan == 'ton' ? 'selected' : '' }}>Ton</option>
                            <option value="liter" {{ $limbah->satuan == 'liter' ? 'selected' : '' }}>Liter</option>
                            <option value="pcs" {{ $limbah->satuan == 'pcs' ? 'selected' : '' }}>Pieces (Pcs)</option>
                        </select>
                    </div>
                </div>

                <!-- Deskripsi Detail / Catatan Internal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Internal / Deskripsi <span
                            class="text-red-500">*</span></label>
                    <!-- Mengisi textarea dengan catatan lama -->
                    <textarea name="catatan_internal" rows="4" required
                        placeholder="Jelaskan kondisi fisik limbah, asal limbah, dan cara penyimpanannya saat ini..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-sirkula-green focus:border-sirkula-green outline-none">{{ $limbah->catatan_internal }}</textarea>
                </div>

                <!-- Tombol Aksi -->
                <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                    <a href="/dashboard"
                        class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-sirkula-green text-white rounded-md hover:bg-sirkula-green-hover font-medium transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection