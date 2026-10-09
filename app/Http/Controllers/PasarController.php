<?php

namespace App\Http\Controllers;

use App\Models\LaporanLimbah;
use Illuminate\Http\Request;
use App\Models\User;

class PasarController extends Controller
{
    /**
     * Menampilkan daftar limbah yang tersedia di pasar/jelajah limbah.
     */
    public function index(Request $request)
    {
        // Hanya status publik yang tampil di katalog
        // (menunggu verifikasi / ditolak admin TIDAK ditampilkan).
        $publik = ['tersedia', 'sedang diproses', 'sudah diambil'];

        $base = LaporanLimbah::query()
            ->whereRaw('LOWER(status) IN (?, ?, ?)', $publik);

        // Angka ringkasan (tidak terpengaruh filter)
        $totalBatch = (clone $base)->count();
        $totalMitra = (clone $base)->distinct()->count('mitra_id');

        // Daftar wilayah untuk dropdown (diambil dari kelurahan mitra yang punya batch publik)
        $wilayahList = User::whereIn('id', (clone $base)->select('mitra_id'))
            ->whereNotNull('kelurahan')
            ->where('kelurahan', '!=', '')
            ->distinct()
            ->orderBy('kelurahan')
            ->pluck('kelurahan');

        // ------------------------------------------------------------------
        // Query katalog + filter
        // ------------------------------------------------------------------
        $query = (clone $base)->with(['mitra', 'kategori']);

        // Pencarian: judul limbah atau nama penyedia
        $search = $request->string('search')->trim()->toString();
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhereHas('mitra', function ($m) use ($search) {
                        $m->where('name', 'like', "%{$search}%")
                            ->orWhere('nama_instansi', 'like', "%{$search}%");
                    });
            });
        }

        // Kategori (organik tidak boleh ikut mencocokkan "anorganik")
        $kategori = $request->string('kategori')->toString();
        if ($kategori === 'anorganik') {
            $query->whereHas('kategori', fn($k) => $k->where('nama_kategori', 'like', '%anorganik%'));
        } elseif ($kategori === 'organik') {
            $query->whereHas('kategori', fn($k) => $k
                ->where('nama_kategori', 'like', '%organik%')
                ->where('nama_kategori', 'not like', '%anorganik%'));
        }

        // Wilayah
        $wilayah = $request->string('wilayah')->trim()->toString();
        if ($wilayah !== '') {
            $query->whereHas('mitra', fn($m) => $m->where('kelurahan', $wilayah));
        }

        // Status
        $statusMap = [
            'tersedia' => 'tersedia',
            'diproses' => 'sedang diproses',
            'diambil'  => 'sudah diambil',
        ];
        $status = $request->string('status')->toString();
        if (isset($statusMap[$status])) {
            $query->whereRaw('LOWER(status) = ?', [$statusMap[$status]]);
        }

        // Volume minimum (KG / Liter; satuan ton dikonversi ke kg, satuan unit diabaikan)
        $volume = (int) $request->input('volume');
        if ($volume > 0) {
            $query->where(function ($q) use ($volume) {
                $q->where(fn($w) => $w->whereIn('satuan', ['kg', 'liter'])
                    ->where('estimasi_volume', '>=', $volume))
                    ->orWhere(fn($w) => $w->where('satuan', 'ton')
                        ->whereRaw('estimasi_volume * 1000 >= ?', [$volume]));
            });
        }

        // Pengurutan
        switch ($request->string('sort')->toString()) {
            case 'terlama':
                $query->oldest();
                break;

            case 'volume_besar':
                $query->orderByRaw("CASE WHEN satuan = 'ton' THEN estimasi_volume * 1000 ELSE estimasi_volume END DESC");
                break;

            case 'terbaru':
            default:
                $query->latest();
                break;
        }

        $query->orderByDesc('id');

        $limbahTersedia = $query->paginate(6)->withQueryString();

        return view('pasar.index', compact(
            'limbahTersedia',
            'totalBatch',
            'totalMitra',
            'wilayahList'
        ));
    }

    /**
     * Menampilkan detail informasi limbah tertentu.
     */
    public function show($id)
    {
        // Hanya batch dengan status publik yang boleh dibuka dari katalog
        // (menunggu verifikasi / ditolak admin -> 404).
        $limbah = LaporanLimbah::with(['mitra', 'kategori'])
            ->whereRaw('LOWER(status) IN (?, ?, ?)', ['tersedia', 'sedang diproses', 'sudah diambil'])
            ->findOrFail($id);

        // Statistik singkat mitra untuk kartu penyedia
        $milikMitra = LaporanLimbah::where('mitra_id', $limbah->mitra_id);

        $sirkulasiSukses = (clone $milikMitra)
            ->whereRaw('LOWER(status) = ?', ['sudah diambil'])
            ->count();

        $batchAktif = (clone $milikMitra)
            ->whereRaw('LOWER(status) = ?', ['tersedia'])
            ->count();

        return view('pasar.show', compact('limbah', 'sirkulasiSukses', 'batchAktif'));
    }
}
