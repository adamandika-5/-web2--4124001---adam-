<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Kategori, SubKategori, ActivityLog};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    public function index()
    {
        return view('admin.kategori.index', [
            'kategoris' => Kategori::withCount(['produk' => fn($q) => $q->where('aktif', true)])
                              ->with('subKategori')
                              ->orderBy('urutan')
                              ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:100|unique:kategoris,nama',
            'ikon'    => 'nullable|string|max:10',
            'warna'   => 'nullable|string|max:20',
            'urutan'  => 'nullable|integer',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori = Kategori::create([
            'nama'      => $request->nama,
            'slug'      => Str::slug($request->nama),
            'ikon'      => $request->ikon,
            'warna'     => $request->warna,
            'deskripsi' => $request->deskripsi,
            'urutan'    => $request->urutan ?? 0,
            'aktif'     => true,
        ]);

        ActivityLog::catat('tambah_kategori', "Kategori '{$kategori->nama}' ditambahkan", '📂', $kategori);
        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama'  => 'required|string|max:100',
            'ikon'  => 'nullable|string|max:10',
            'warna' => 'nullable|string|max:20',
            'aktif' => 'boolean',
        ]);

        $kategori->update([
            'nama'  => $request->nama,
            'ikon'  => $request->ikon,
            'warna' => $request->warna,
            'aktif' => $request->boolean('aktif'),
        ]);

        ActivityLog::catat('edit_kategori', "Kategori '{$kategori->nama}' diperbarui", '✏️', $kategori);
        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->produk()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus kategori yang masih memiliki produk.');
        }
        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    // Sub Kategori
    public function storeSub(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama'        => 'required|string|max:100',
        ]);

        SubKategori::create([
            'kategori_id' => $request->kategori_id,
            'nama'        => $request->nama,
            'slug'        => Str::slug($request->nama) . '-' . uniqid(),
            'aktif'       => true,
        ]);

        return back()->with('success', 'Sub kategori berhasil ditambahkan.');
    }

    public function destroySub(SubKategori $subKategori)
    {
        if ($subKategori->produk()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus sub kategori yang masih memiliki produk.');
        }
        $subKategori->delete();
        return back()->with('success', 'Sub kategori berhasil dihapus.');
    }
}