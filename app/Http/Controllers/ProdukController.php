<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    // Menampilkan semua produk
    public function index(Request $request)
    {
        $q = $request->query('q');

        $produk = Produk::when($q, function ($query, $search) {
                        return $query->where('nama', 'LIKE', "%{$search}%");
                    })
                    ->latest()
                    ->paginate(6);

        return view('produk.index', compact('produk'));
    }

    // Menampilkan form tambah produk
    public function create()
    {
        return view('produk.create');
    }

    // Menyimpan produk baru
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',

            // Maksimal 10 MB
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ]);

        // Default nama gambar
        $namaGambar = null;

        // Upload gambar
        if ($request->hasFile('gambar')) {

            $file = $request->file('gambar');

            $namaGambar = time() . '.' . $file->getClientOriginalExtension();

            // Pastikan folder ada
            $tujuanUpload = public_path('gambar');

            if (!file_exists($tujuanUpload)) {
                mkdir($tujuanUpload, 0777, true);
            }

            // Simpan gambar
            $file->move($tujuanUpload, $namaGambar);
        }

        // Simpan data
        Produk::create([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'gambar' => $namaGambar,
        ]);

        return redirect()
                ->route('produk.index')
                ->with('success', 'Produk berhasil ditambahkan');
    }

    // Detail produk
    public function show($id)
    {
        $produk = Produk::findOrFail($id);

        return view('produk.show', compact('produk'));
    }

    // Form edit produk
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);

        return view('produk.edit', compact('produk'));
    }

    // Update produk
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        // Validasi
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',

            // Maksimal 10 MB
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ]);

        // Upload gambar baru
        if ($request->hasFile('gambar')) {

            // Hapus gambar lama
            if (
                $produk->gambar &&
                file_exists(public_path('gambar/' . $produk->gambar))
            ) {
                unlink(public_path('gambar/' . $produk->gambar));
            }

            // File baru
            $file = $request->file('gambar');

            $namaGambar = time() . '.' . $file->getClientOriginalExtension();

            // Pastikan folder ada
            $tujuanUpload = public_path('gambar');

            if (!file_exists($tujuanUpload)) {
                mkdir($tujuanUpload, 0777, true);
            }

            // Upload gambar
            $file->move($tujuanUpload, $namaGambar);

            // Simpan nama gambar
            $produk->gambar = $namaGambar;
        }

        // Update data
        $produk->nama = $request->nama;
        $produk->kategori = $request->kategori;
        $produk->harga = $request->harga;
        $produk->stok = $request->stok;
        $produk->deskripsi = $request->deskripsi;

        $produk->save();

        return redirect()
                ->route('produk.index')
                ->with('success', 'Produk berhasil diperbarui');
    }

    // Hapus produk
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        // Hapus gambar
        if (
            $produk->gambar &&
            file_exists(public_path('gambar/' . $produk->gambar))
        ) {
            unlink(public_path('gambar/' . $produk->gambar));
        }

        // Hapus produk
        $produk->delete();

        return redirect()
                ->route('produk.index')
                ->with('success', 'Produk berhasil dihapus');
    }
}