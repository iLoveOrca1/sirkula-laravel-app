<!-- resources/views/pasar/partials/card.blade.php -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow flex flex-col">
    <!-- Bagian Atas / Kategori -->
    <div class="bg-green-50 px-5 py-4 border-b border-green-100 flex justify-between items-center">
        <span class="font-bold text-sirkula-green">{{ $item->kategori->nama_kategori ?? 'Umum' }}</span>
        <span class="bg-white text-green-700 text-xs px-2.5 py-1 rounded-full border border-green-200 font-semibold shadow-sm">
            Terverifikasi
        </span>
    </div>
    
    <!-- Detail Info -->
    <div class="p-5 flex-grow">
        <div class="mb-4">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Disediakan Oleh</p>
            <p class="font-medium text-gray-900">{{ $item->mitra->name ?? 'Mitra Sirkula' }}</p>
            <p class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($item->tanggal_pencatatan)->diffForHumans() }}</p>
        </div>
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                <p class="text-xs text-gray-500 mb-1">Volume</p>
                <p class="font-bold text-gray-800">{{ $item->volume_dihasilkan }} <span class="text-sm font-normal">{{ $item->satuan }}</span></p>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                <p class="text-xs text-gray-500 mb-1">Status Ketersediaan</p>
                <p class="font-bold text-green-600">Tersedia</p>
            </div>
        </div>

        <div>
            <p class="text-xs text-gray-500 mb-1">Catatan Kondisi</p>
            <p class="text-sm text-gray-700 line-clamp-2">{{ $item->catatan_internal }}</p>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="p-5 border-t border-gray-100 mt-auto">
        <button class="w-full bg-sirkula-green hover:bg-sirkula-green-hover text-white font-medium py-2.5 rounded-lg transition-colors flex justify-center items-center gap-2">
            Hubungi Mitra
        </button>
    </div>
</div>