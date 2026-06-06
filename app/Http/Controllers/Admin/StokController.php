<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Produk, Kategori, ActivityLog};
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with('kategori')->latest();

        if ($request->filled('q')) {
            $query->where(fn($x) =>
                $x->where('nama', 'LIKE', "%{$request->q}%")
                  ->orWhere('sku', 'LIKE', "%{$request->q}%")
            );
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->filter === 'low_stock') {
            $query->where('stok', '>', 0)->where('stok', '<', 20);
        } elseif ($request->filter === 'out_of_stock' || $request->filter === 'habis') {
            $query->where('stok', '<=', 0);
        }

        $totalAktif = Produk::aktif()->count();
        $stokAman = Produk::where('stok', '>=', 20)->count();
        $stokRendah = Produk::where('stok', '>', 0)->where('stok', '<', 20)->count();
        $stokHabis = Produk::where('stok', '<=', 0)->count();

        $kategoris = Kategori::aktif()->get();
        $produkKritis = Produk::where('stok', '<', 20)->orderBy('stok')->take(10)->get();

        return view('admin.stok.index', [
            'produks'      => $query->paginate(25)->withQueryString(),
            'totalAktif'   => $totalAktif,
            'stokAman'     => $stokAman,
            'stokRendah'   => $stokRendah,
            'stokHabis'    => $stokHabis,
            'kategoris'    => $kategoris,
            'produkKritis' => $produkKritis,
        ]);
    }

    public function tambah(Request $request, Produk $produk)
    {
        $request->validate([
            'jumlah'   => 'required|integer|min:1',
            'catatan'  => 'nullable|string|max:200',
        ]);

        $stokLama = $produk->stok;
        $produk->increment('stok', $request->jumlah);

        ActivityLog::catat(
            'tambah_stok',
            "Stok '{$produk->nama}' ditambah {$request->jumlah} (dari {$stokLama} → {$produk->fresh()->stok})"
                . ($request->catatan ? ". Catatan: {$request->catatan}" : ''),
            '📦',
            $produk
        );

        return back()->with('success', "Stok {$produk->nama} berhasil ditambah {$request->jumlah} unit.");
    }

    public function kurang(Request $request, Produk $produk)
    {
        $request->validate([
            'jumlah'  => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:200',
        ]);

        if ($produk->stok < $request->jumlah) {
            return back()->with('error', "Stok tidak cukup. Stok saat ini: {$produk->stok} unit.");
        }

        $stokLama = $produk->stok;
        $produk->decrement('stok', $request->jumlah);

        ActivityLog::catat(
            'kurang_stok',
            "Stok '{$produk->nama}' dikurangi {$request->jumlah} (dari {$stokLama} → {$produk->fresh()->stok})",
            '📉',
            $produk
        );

        return back()->with('success', "Stok {$produk->nama} berhasil dikurangi {$request->jumlah} unit.");
    }

    public function laporan(Request $request)
    {
        $produks = Produk::with('kategori')
            ->when($request->filled('kategori'), fn($q) => $q->where('kategori_id', $request->kategori))
            ->orderBy('nama')
            ->get();

        $format = $request->input('format');

        // ── Export Excel (CSV) ──────────────────────────────────────
        if ($format === 'excel') {
            $filename = 'laporan-stok-' . now()->format('Ymd-His') . '.csv';

            $callback = function () use ($produks) {
                $handle = fopen('php://output', 'w');

                // BOM agar Excel bisa baca UTF-8 tanpa mojibake
                fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

                // Header kolom
                fputcsv($handle, ['No', 'SKU', 'Nama Produk', 'Kategori', 'Stok', 'Satuan', 'Harga (Rp)', 'Nilai Stok (Rp)', 'Status']);

                foreach ($produks as $i => $p) {
                    $status = $p->stok <= 0 ? 'Habis' : ($p->stok < 20 ? 'Rendah' : 'Aman');
                    fputcsv($handle, [
                        $i + 1,
                        $p->sku ?? '-',
                        $p->nama,
                        optional($p->kategori)->nama ?? '-',
                        $p->stok,
                        $p->satuan,
                        number_format($p->harga, 0, ',', '.'),
                        number_format($p->stok * $p->harga, 0, ',', '.'),
                        $status,
                    ]);
                }

                // Baris total
                fputcsv($handle, [
                    '', '', '', 'TOTAL',
                    $produks->sum('stok'),
                    '', '',
                    number_format($produks->sum(fn($p) => $p->stok * $p->harga), 0, ',', '.'),
                    '',
                ]);

                fclose($handle);
            };

            return response()->stream($callback, 200, [
                'Content-Type'        => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ]);
        }

        // ── Export PDF (HTML print-ready) ────────────────────────────
        if ($format === 'pdf') {
            return view('admin.stok.laporan-pdf', compact('produks'));
        }

        // ── Tampilan biasa (preview) ─────────────────────────────────
        return view('admin.stok.laporan', compact('produks'));
    }
}
