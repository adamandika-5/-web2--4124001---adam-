<?php

namespace App\Http\Controllers;

use App\Models\{Kategori, Produk, Promo, Pesanan};

class BerandaController extends Controller
{
    public function index()
    {
        return view('pages.beranda', [
            'kategoris'      => Kategori::aktif()
                                    ->withCount(['produk' => fn($q) => $q->where('aktif', true)])
                                    ->get(),

            'produkUnggulan' => Produk::aktif()->unggulan()->stokAda()
                                    ->with([
                                        'kategori',
                                        'gambar' => fn($q) => $q->where('is_utama', true)
                                    ])
                                    ->take(8)
                                    ->get(),

            'produkTerbaru'  => Produk::aktif()->stokAda()
                                    ->with([
                                        'kategori',
                                        'gambar' => fn($q) => $q->where('is_utama', true)
                                    ])
                                    ->latest()
                                    ->take(4)
                                    ->get(),

            'promos'         => Promo::aktif()->take(3)->get(),

            'totalProduk'    => Produk::aktif()->count(),

            'totalPesanan'   => Pesanan::where('status', 'selesai')->count(),
        ]);
    }

    public function promo()
    {
        return view('pages.promo', [
            'promos' => Promo::aktif()->paginate(12),
        ]);
    }

    public function promoShow(string $slug)
    {
        $promo = Promo::where('slug', $slug)->firstOrFail();

        return view('pages.promo-detail', compact('promo'));
    }
}