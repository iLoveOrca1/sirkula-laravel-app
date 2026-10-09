<!-- resources/views/pasar/partials/card.blade.php -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300 flex flex-col group">
    
    <!-- Gambar Limbah (Bisa Diklik & Ada Efek Zoom) -->
    <a href="{{ route('pasar.show', $item->id) }}" class="block h-48 w-full bg-gray-100 relative overflow-hidden">
        @if($item->foto_limbah)
            <img src="{{ asset('storage/' . $item->foto_limbah) }}" alt="{{ $item->judul }}" 
                 class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="flex items-center justify-center w-full h-full text-gray-400 text-sm">Tidak ada foto</div>
        @endif
        
        <span class="absolute top-3 right-3 bg-white text-green-700 text-xs px-2.5 py-1 rounded-full border border-green-200 font-semibold shadow-sm z-10">
            Terverifikasi
        </span>
    </a>

    <!-- Bagian Kategori & Judul (Judul Bisa Diklik) -->
    <div class="px-5 pt-4 pb-2 border-b border-gray-50">
        <span class="text-[11px] font-bold text-sirkula-green uppercase tracking-wider">{{ $item->kategori->nama_kategori ?? 'Umum' }}</span>
        <a href="{{ route('pasar.show', $item->id) }}" class="block mt-1">
            <h3 class="text-lg font-bold text-gray-900 truncate hover:text-sirkula-green transition-colors" title="{{ $item->judul }}">
                {{ $item->judul }}
            </h3>
        </a>
    </div>
    
    <!-- Detail Info -->
    <div class="p-5 flex-grow">
        <div class="mb-4">
            <p class="text-[10px] text-gray-500 uppercase tracking-wider font-semibold mb-1">Disediakan Oleh</p>
            <p class="font-medium text-gray-900 text-sm">{{ $item->mitra->name ?? 'Mitra Sirkula' }}</p>
            <p class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($item->tanggal_pencatatan)->diffForHumans() }}</p>
        </div>
        
        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                <p class="text-[10px] text-gray-500 mb-1 uppercase font-semibold">Volume</p>
                <p class="font-bold text-gray-800 text-sm">{{ $item->estimasi_volume }} <span class="font-normal">{{ $item->satuan }}</span></p>
            </div>
            <div class="bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                <p class="text-[10px] text-gray-500 mb-1 uppercase font-semibold">Status</p>
                <p class="font-bold text-green-600 text-sm capitalize">{{ $item->status }}</p>
            </div>
        </div>

        <div>
            <p class="text-[10px] text-gray-500 mb-1 uppercase font-semibold">Catatan Kondisi</p>
            <p class="text-xs text-gray-700 line-clamp-2" title="{{ $item->deskripsi }}">{{ $item->deskripsi }}</p>
        </div>
    </div>

    <!-- Tombol Aksi Menuju Detail -->
    <div class="p-4 border-t border-gray-100 mt-auto bg-gray-50">
        <a href="{{ route('pasar.show', $item->id) }}" class="w-full bg-white border border-sirkula-green text-sirkula-green hover:bg-sirkula-green hover:text-white font-medium py-2 rounded-lg transition-colors flex justify-center items-center gap-2 text-sm shadow-sm">
            Lihat Detail Lengkap
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </a>
    </div>
</div>