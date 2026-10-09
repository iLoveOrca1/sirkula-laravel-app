<?php

namespace App\Http\Controllers;

use App\Models\KategoriLimbah;
use App\Models\LaporanLimbah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    /** Kolom yang boleh dijadikan dasar pengurutan (whitelist). */
    private const SORTABLE = ['judul', 'kategori', 'volume', 'status', 'update'];

    /** Tab status di dashboard => nilai status (lowercase) di database. */
    private const STATUS_TABS = [
        'tersedia' => 'tersedia',
        'diproses' => 'sedang diproses',
        'diambil'  => 'sudah diambil',
    ];

    /** Status yang boleh diubah sendiri oleh mitra. */
    private const STATUS_MITRA = ['tersedia', 'sedang diproses', 'sudah diambil'];

    private const PER_PAGE = 6;

    // ==========================================
    // DASHBOARD ADMIN
    // ==========================================
    public function admin()
    {
        $semuaLaporan = LaporanLimbah::with(['kategori', 'mitra'])->latest()->get();

        $user = Auth::user();

        return view('admin.dashboard-admin', compact('semuaLaporan', 'user'));
    }

    // ==========================================
    // DASHBOARD MITRA
    // ==========================================
    public function mitra(Request $request)
    {
        $user = Auth::user();

        [$query, $sort, $dir, $filters] = $this->queryLaporanMitra($request, $user->id);

        $laporan = $query->paginate(self::PER_PAGE)
            ->withQueryString()
            ->fragment('tabel-riwayat');

        $kategoriList = KategoriLimbah::orderBy('nama_kategori')->get();

        // Statistik dihitung dari SEMUA laporan milik mitra (bukan yang terfilter)
        $semua  = LaporanLimbah::where('mitra_id', $user->id)->get();
        $byStat = fn (string $s) => $semua->filter(fn ($i) => strtolower($i->status) === $s);

        $diambil         = $byStat('sudah diambil');
        $diambilBulanIni = $diambil->filter(fn ($i) => $i->updated_at && $i->updated_at->isCurrentMonth());

        $counts = [
            'semua'    => $semua->count(),
            'tersedia' => $byStat('tersedia')->count(),
            'diproses' => $byStat('sedang diproses')->count(),
            'diambil'  => $diambil->count(),
        ];

        $stat = [
            'kategori'   => $semua->pluck('kategori_id')->unique()->count(),
            'batchBulan' => $diambilBulanIni->count(),
            'massaBulan' => $this->formatMassa($diambilBulanIni->sum(fn ($i) => $this->toKg($i))),
        ];

        $dampak = [
            'massa'   => $this->formatMassa($diambil->sum(fn ($i) => $this->toKg($i))),
            'tingkat' => $counts['semua'] > 0 ? (int) round($counts['diambil'] / $counts['semua'] * 100) : 0,
            'total'   => $counts['semua'],
        ];

        return view('dashboard', [
            'user'         => $user,
            'laporan'      => $laporan,
            'sort'         => $sort,
            'dir'          => $dir,
            'statusKey'    => $filters['status'],
            'q'            => $filters['q'],
            'kategoriId'   => $filters['kategori'],
            'kategoriList' => $kategoriList,
            'counts'       => $counts,
            'stat'         => $stat,
            'dampak'       => $dampak,
        ]);
    }

    // ==========================================
    // UBAH STATUS KETERSEDIAAN (oleh mitra)
    // ==========================================
    public function ubahStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => ['required', Rule::in(self::STATUS_MITRA)],
        ]);

        $laporan = LaporanLimbah::where('mitra_id', Auth::id())->findOrFail($id);

        $saatIni = strtolower($laporan->status);

        if ($saatIni === 'sudah diambil') {
            return back()->with('error', 'Batch yang sudah diambil tidak dapat diubah statusnya lagi.');
        }

        if (! in_array($saatIni, self::STATUS_MITRA, true)) {
            return back()->with('error', 'Status baru bisa diubah setelah laporan disetujui admin.');
        }

        $laporan->update(['status' => $request->status]);

        return back()->with(
            'success',
            'Status "' . $laporan->judul . '" diperbarui menjadi ' . ucwords($request->status) . '.'
        );
    }

    // ==========================================
    // EKSPOR REKAP LAPORAN MITRA (CSV)
    // Mengikuti filter, pencarian, dan urutan yang sedang aktif
    // ==========================================
    public function exportMitra(Request $request)
    {
        $user = Auth::user();

        [$query] = $this->queryLaporanMitra($request, $user->id);

        $laporan = $query->get();

        $filename = 'rekap-laporan-limbah-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($laporan) {
            $out = fopen('php://output', 'w');

            // BOM UTF-8 supaya Excel membaca karakter dengan benar
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'No', 'Judul / Material', 'Kategori', 'Estimasi Volume', 'Satuan',
                'Tanggal Pencatatan', 'Status', 'Deskripsi', 'Latitude', 'Longitude',
            ]);

            foreach ($laporan as $i => $item) {
                fputcsv($out, [
                    $i + 1,
                    $item->judul,
                    $item->kategori->nama_kategori ?? '-',
                    $item->estimasi_volume,
                    $item->satuan,
                    $item->tanggal_pencatatan
                        ? Carbon::parse($item->tanggal_pencatatan)->format('d-m-Y')
                        : '-',
                    $item->status,
                    $item->deskripsi,
                    $item->latitude,
                    $item->longitude,
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    // ==========================================
    // HELPER: query laporan milik mitra + filter + pengurutan
    // ==========================================
    private function queryLaporanMitra(Request $request, int $mitraId): array
    {
        $sort = $request->string('sort')->toString();
        $sort = in_array($sort, self::SORTABLE, true) ? $sort : 'update';
        $dir  = $request->string('dir')->toString() === 'asc' ? 'asc' : 'desc';

        $q          = $request->string('q')->trim()->toString();
        $statusKey  = $request->string('status')->toString();
        $statusKey  = array_key_exists($statusKey, self::STATUS_TABS) ? $statusKey : '';
        $kategoriId = $request->string('kategori')->toString();
        $kategoriId = ctype_digit($kategoriId) ? $kategoriId : '';

        $query = LaporanLimbah::with('kategori')->where('mitra_id', $mitraId);

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('judul', 'like', "%{$q}%")
                  ->orWhere('deskripsi', 'like', "%{$q}%");
            });
        }

        if ($statusKey !== '') {
            $query->whereRaw('LOWER(status) = ?', [self::STATUS_TABS[$statusKey]]);
        }

        if ($kategoriId !== '') {
            $query->where('kategori_id', (int) $kategoriId);
        }

        switch ($sort) {
            case 'judul':
                $query->orderBy('judul', $dir);
                break;

            case 'kategori':
                $kategoriTable = (new KategoriLimbah)->getTable();

                $query->orderBy(
                    KategoriLimbah::select('nama_kategori')
                        ->whereColumn($kategoriTable . '.id', 'laporan_limbahs.kategori_id'),
                    $dir
                );
                break;

            case 'volume':
                $query->orderBy('estimasi_volume', $dir);
                break;

            case 'status':
                $query->orderBy('status', $dir);
                break;

            case 'update':
            default:
                $query->orderBy('updated_at', $dir);
                break;
        }

        $query->orderByDesc('id');

        return [
            $query,
            $sort,
            $dir,
            ['q' => $q, 'status' => $statusKey, 'kategori' => $kategoriId],
        ];
    }

    // Konversi volume ke kilogram (hanya satuan kg & ton yang bisa dikonversi)
    private function toKg($item): float
    {
        $v = (float) $item->estimasi_volume;

        return match (strtolower((string) $item->satuan)) {
            'kg'    => $v,
            'ton'   => $v * 1000,
            default => 0.0,
        };
    }

    private function formatMassa(float $kg): string
    {
        if ($kg >= 1000) {
            return rtrim(rtrim(number_format($kg / 1000, 1, ',', '.'), '0'), ',') . ' Ton';
        }

        return number_format($kg, 0, ',', '.') . ' Kg';
    }
}