<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        
        $berita = Berita::when($q, fn($query, $search) =>
                        $query->where('judul', 'LIKE', "%{$search}%")
                              ->orWhere('isi', 'LIKE', "%{$search}%")
                    )
                    ->latest()
                    ->paginate(8);
        
        return view('berita.index', compact('berita'));
    }

    public function create()
    {
        return view('berita.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'    => 'required|string|max:200',
            'isi'      => 'required|string',
            'penulis'  => 'required|string|max:100',
            'kategori' => 'nullable|string',
            'aktif'    => 'nullable|boolean',
        ]);

        $validated['aktif'] = $validated['aktif'] ?? true;

        Berita::create($validated);

        return redirect()->route('berita.index')
                         ->with('success', 'Berita berhasil ditambahkan!');
    }

    public function show($id)
    {
        $berita = Berita::findOrFail($id);
        return view('berita.show', compact('berita'));
    }

    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        return view('berita.edit', compact('berita'));
    }

    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);
        
        $validated = $request->validate([
            'judul'    => 'required|string|max:200',
            'isi'      => 'required|string',
            'penulis'  => 'required|string|max:100',
            'kategori' => 'nullable|string',
            'aktif'    => 'nullable|boolean',
        ]);

        $validated['aktif'] = $validated['aktif'] ?? true;

        $berita->update($validated);

        return redirect()->route('berita.index')
                         ->with('success', 'Berita berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Berita::findOrFail($id)->delete();
        
        return redirect()->route('berita.index')
                         ->with('success', 'Berita berhasil dihapus!');
    }
}