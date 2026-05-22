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
        $produk = Produk::with('kategori')
            ->when($request->filled('kategori'), fn($q) => $q->where('kategori_id', $request->kategori))
            ->orderBy('stok')
            ->get();

        return view('admin.stok.laporan', compact('produk'));
    }
}
