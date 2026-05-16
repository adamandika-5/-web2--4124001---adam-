<?php

namespace App\Http\Controllers;

use App\Models\{Produk, Kategori};
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::aktif()
            ->with(['kategori', 'gambar' => fn($q) => $q->where('is_utama', true)]);

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->whereHas('kategori', fn($q) =>
                $q->whereIn('slug', (array) $request->kategori)
            );
        }

        // Filter harga
        if ($request->filled('harga_min')) $query->where('harga', '>=', $request->harga_min);
        if ($request->filled('harga_max')) $query->where('harga', '<=', $request->harga_max);

        // Filter khusus
        if ($request->hasAny('filter')) {
            $filters = (array) $request->filter;
            if (in_array('stok',     $filters)) $query->where('stok', '>', 0);
            if (in_array('promo',    $filters)) $query->whereNotNull('harga_promo');
            if (in_array('unggulan', $filters)) $query->where('unggulan', true);
        }

        // Filter satuan
        if ($request->filled('satuan')) {
            $query->whereIn('satuan', (array) $request->satuan);
        }

        // Pencarian
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($x) =>
                $x->where('nama', 'LIKE', "%$q%")
                  ->orWhere('deskripsi', 'LIKE', "%$q%")
                  ->orWhere('sku', 'LIKE', "%$q%")
            );
        }

        // Sorting
        match ($request->sort ?? 'terlaris') {
            'harga_asc'  => $query->orderBy('harga'),
            'harga_desc' => $query->orderByDesc('harga'),
            'terbaru'    => $query->latest(),
            'nama_asc'   => $query->orderBy('nama'),
            default      => $query->orderByDesc('terjual'),
        };

        return view('pages.katalog', [
            'produk'    => $query->paginate(18)->withQueryString(),
            'kategoris' => Kategori::aktif()
                            ->withCount(['produk' => fn($q) => $q->where('aktif', true)])
                            ->get(),
        ]);
    }
}