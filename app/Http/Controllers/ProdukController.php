<?php

namespace App\Http\Controllers;

use App\Models\Produk;

class ProdukController extends Controller
{
    public function show(string $slug)
    {
        $produk = Produk::aktif()
            ->where('slug', $slug)
            ->with(['kategori', 'subKategori', 'gambar', 'supplier'])
            ->firstOrFail();

        $rekomendasi = Produk::aktif()
            ->where('kategori_id', $produk->kategori_id)
            ->where('id', '!=', $produk->id)
            ->stokAda()
            ->with(['kategori', 'gambar' => fn($q) => $q->where('is_utama', true)])
            ->take(4)->get();

        return view('pages.produk-detail', compact('produk', 'rekomendasi'));
    }
}
