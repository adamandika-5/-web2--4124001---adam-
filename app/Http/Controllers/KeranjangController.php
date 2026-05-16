<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Cart;

class KeranjangController extends Controller
{
    public function index()
    {
        $items = Cart::getContent()->map(function ($item) {
            return [
                'item'   => $item,
                'produk' => Produk::find($item->id),
            ];
        });

        return view('pages.keranjang', compact('items'));
    }

    public function tambah(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'qty'       => 'required|integer|min:1',
        ]);

        $produk = Produk::findOrFail($request->produk_id);

        if ($produk->stok < $request->qty) {
            return back()->with('error', "Stok {$produk->nama} tidak mencukupi. Tersisa: {$produk->stok} {$produk->satuan}.");
        }

        Cart::add([
            'id'         => $produk->id,
            'name'       => $produk->nama,
            'price'      => $produk->harga_final,
            'quantity'   => $request->qty,
            'attributes' => [
                'satuan' => $produk->satuan,
                'gambar' => $produk->gambar_utama,
                'slug'   => $produk->slug,
            ],
        ]);

        return back()->with('success', "{$produk->nama} berhasil ditambahkan ke keranjang.");
    }

    public function update(Request $request, $id)
    {
        $request->validate(['qty' => 'required|integer|min:1']);

        Cart::update($id, [
            'quantity' => [
                'relative' => false,
                'value'    => $request->qty,
            ],
        ]);

        return back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function hapus($id)
    {
        Cart::remove($id);
        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function kosongkan()
    {
        Cart::clear();
        return back()->with('success', 'Keranjang berhasil dikosongkan.');
    }
}