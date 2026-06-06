<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Produk, Kategori, SubKategori, ProdukGambar, ActivityLog};
use Illuminate\Http\Request;

class ProdukController extends Controller
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
        if ($request->filled('kategori')) $query->where('kategori_id', $request->kategori);
        if ($request->status === 'aktif')     $query->where('aktif', true);
        if ($request->status === 'nonaktif')  $query->where('aktif', false);
        if ($request->status === 'promo')     $query->whereNotNull('harga_promo');
        if ($request->status === 'low_stock') $query->where('stok', '>', 0)->where('stok', '<', 20);
        if ($request->sort === 'stok_asc')    $query->orderBy('stok');
        if ($request->sort === 'terlaris')    $query->orderByDesc('terjual');
        if ($request->sort === 'harga_desc')  $query->orderByDesc('harga');

        return view('admin.produk.index', [
            'produk'    => $query->paginate(20)->withQueryString(),
            'kategoris' => Kategori::aktif()->get(),
        ]);
    }

    public function create()
    {
        return view('admin.produk.form', [
            'kategoris'    => Kategori::aktif()->get(),
            'subKategoris' => SubKategori::where('aktif', true)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'             => 'required|string|max:200',
            'kategori_id'      => 'required|exists:kategoris,id',
            'harga'            => 'required|numeric|min:0',
            'harga_promo'      => 'nullable|numeric|lt:harga',
            'stok'             => 'required|integer|min:0',
            'satuan'           => 'required|string',
            'deskripsi'        => 'required|string',
            'sku'              => 'nullable|string|unique:produks,sku',
            'berat'            => 'nullable|numeric',
            'jenis_pengiriman' => 'required|in:ekspedisi,armada,keduanya',
            'aktif'            => 'boolean',
            'unggulan'         => 'boolean',
            'gambar.*'         => 'nullable|image|max:2048',
        ]);

        $data['aktif']    = $request->boolean('aktif', true);
        $data['unggulan'] = $request->boolean('unggulan');

        $produk = Produk::create($data);

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $i => $file) {
                $path = $file->store('produk', 'public');
                ProdukGambar::create([
                    'produk_id' => $produk->id,
                    'path'      => $path,
                    'is_utama'  => $i === 0,
                    'urutan'    => $i,
                ]);
            }
        }

        ActivityLog::catat('tambah_produk', "Produk '{$produk->nama}' ditambahkan", '📦', $produk);
        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Produk $produk)
    {
        return view('admin.produk.form', [
            'produk'       => $produk->load('gambar'),
            'kategoris'    => Kategori::aktif()->get(),
            'subKategoris' => SubKategori::where('aktif', true)->get(),
        ]);
    }

    public function update(Request $request, Produk $produk)
    {
        $data = $request->validate([
            'nama'             => 'required|string|max:200',
            'kategori_id'      => 'required|exists:kategoris,id',
            'harga'            => 'required|numeric|min:0',
            'harga_promo'      => 'nullable|numeric|lt:harga',
            'stok'             => 'required|integer|min:0',
            'satuan'           => 'required|string',
            'deskripsi'        => 'required|string',
            'berat'            => 'nullable|numeric',
            'jenis_pengiriman' => 'required|in:ekspedisi,armada,keduanya',
            'gambar.*'         => 'nullable|image|max:2048',
        ]);

        $data['aktif']    = $request->boolean('aktif');
        $data['unggulan'] = $request->boolean('unggulan');

        $produk->update($data);

        if ($request->hasFile('gambar')) {
            $startUrutan = $produk->gambar()->max('urutan') + 1;
            foreach ($request->file('gambar') as $i => $file) {
                $path = $file->store('produk', 'public');
                ProdukGambar::create([
                    'produk_id' => $produk->id,
                    'path'      => $path,
                    'is_utama'  => $produk->gambar()->count() === 0 && $i === 0,
                    'urutan'    => $startUrutan + $i,
                ]);
            }
        }

        ActivityLog::catat('edit_produk', "Produk '{$produk->nama}' diperbarui", '✏️', $produk);
        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        ActivityLog::catat('hapus_produk', "Produk '{$produk->nama}' dihapus", '🗑️', $produk);
        $produk->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }

    public function toggleAktif(Produk $produk)
    {
        $produk->update(['aktif' => !$produk->aktif]);
        $status = $produk->aktif ? 'diaktifkan' : 'dinonaktifkan';
        ActivityLog::catat('toggle_produk', "Produk '{$produk->nama}' {$status}", '🔄', $produk);
        return back()->with('success', "Produk berhasil {$status}.");
    }

    public function hapusGambar(ProdukGambar $gambar)
    {
        \Storage::disk('public')->delete($gambar->path);
        // Jika utama, jadikan gambar berikutnya sebagai utama
        if ($gambar->is_utama) {
            $gambar->produk->gambar()
                ->where('id', '!=', $gambar->id)
                ->oldest('urutan')
                ->first()
                ?->update(['is_utama' => true]);
        }
        $gambar->delete();
        return response()->json(['ok' => true]);
    }

    public function export()
    {
        $filename = 'produk-sinar-alam-' . date('Ymd-His') . '.csv';
        $produks  = Produk::with('kategori')->orderBy('nama')->get();

        $callback = function () use ($produks) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8

            fputcsv($handle, ['No', 'SKU', 'Nama Produk', 'Kategori', 'Stok', 'Satuan', 'Harga (Rp)', 'Harga Promo (Rp)', 'Status Stok', 'Aktif']);

            foreach ($produks as $i => $p) {
                $statusStok = $p->stok <= 0 ? 'Habis' : ($p->stok < 20 ? 'Rendah' : 'Aman');
                fputcsv($handle, [
                    $i + 1,
                    $p->sku ?? '-',
                    $p->nama,
                    optional($p->kategori)->nama ?? '-',
                    $p->stok,
                    $p->satuan,
                    number_format($p->harga, 0, ',', '.'),
                    $p->harga_promo ? number_format($p->harga_promo, 0, ',', '.') : '-',
                    $statusStok,
                    $p->aktif ? 'Ya' : 'Tidak',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ]);
    }
}