<?php
namespace App\Http\Controllers;
use App\Models\Produk;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = auth()->user()->wishlist()
            ->with(['kategori', 'gambar' => fn($q) => $q->where('is_utama', true)])
            ->get();

        $rekomendasi = Produk::aktif()->stokAda()->unggulan()
            ->whereNotIn('id', $wishlist->pluck('id'))
            ->with(['kategori', 'gambar' => fn($q) => $q->where('is_utama', true)])
            ->take(4)->get();

        return view('pages.wishlist', compact('wishlist', 'rekomendasi'));
    }

    public function toggle(Request $request)
    {
        $request->validate(['produk_id' => 'required|exists:produks,id']);

        $user = auth()->user();
        $produkId = $request->produk_id;

        if ($user->wishlist()->where('produk_id', $produkId)->exists()) {
            $user->wishlist()->detach($produkId);
            $msg = 'Produk dihapus dari wishlist.';
        } else {
            $user->wishlist()->attach($produkId);
            $msg = 'Produk ditambahkan ke wishlist.';
        }

        return back()->with('success', $msg);
    }
}