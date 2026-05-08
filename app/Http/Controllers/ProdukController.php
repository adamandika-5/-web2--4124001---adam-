<?php
namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    // ─── index() – Tampilkan daftar produk ───
    public function index(Request $request)
    {
        $q = $request->query('q');  // Ambil keyword pencarian

        $produk = Produk::when($q, fn($query, $search) =>
                        $query->where('nama', 'LIKE', "%{$search}%")
                    )
                    ->latest()     // Terbaru dulu
                    ->paginate(6); // 6 produk per halaman

        return view('produk.index', compact('produk'));
    }

    // ─── create() – Tampilkan form tambah ───
    public function create()
    {
        return view('produk.create');
    }

    // ─── store() – Simpan produk baru ───
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:100',
            'harga'    => 'required|numeric|min:0',
            'stok'     => 'required|integer|min:0',
            'kategori' => 'nullable|string',
        ]);

        Produk::create($validated);

        return redirect()->route('produk.index')
                         ->with('success', 'Produk berhasil ditambahkan!');
    }

    // ─── show() – Detail satu produk ───
    public function show($id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk.show', compact('produk'));
    }

    // ─── edit() – Form edit produk ───
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk.edit', compact('produk'));
    }

    // ─── update() – Simpan perubahan ───
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $validated = $request->validate([
            'nama'     => 'required|string|max:100',
            'harga'    => 'required|numeric|min:0',
            'stok'     => 'required|integer|min:0',
            'kategori' => 'nullable|string',
        ]);
        $produk->update($validated);
        return redirect()->route('produk.index')
                         ->with('success', 'Produk berhasil diperbarui!');
    }

    // ─── destroy() – Hapus produk ───
    public function destroy($id)
    {
        Produk::findOrFail($id)->delete();
        return redirect()->route('produk.index')
                         ->with('success', 'Produk berhasil dihapus!');
    }
}