<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Supplier, Produk, ProdukSupplier, ActivityLog};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::withTrashed()->latest();

        if ($request->filled('q')) {
            $query->where(fn($x) =>
                $x->where('nama', 'LIKE', "%{$request->q}%")
                  ->orWhere('email', 'LIKE', "%{$request->q}%")
                  ->orWhere('kota', 'LIKE', "%{$request->q}%")
            );
        }

        if ($request->status === 'aktif')     $query->where('aktif', true)->withoutTrashed();
        if ($request->status === 'nonaktif')  $query->where('aktif', false)->withoutTrashed();

        return view('admin.supplier.index', [
            'suppliers' => $query->paginate(20)->withQueryString(),
            'total'     => Supplier::count(),
            'aktif'     => Supplier::where('aktif', true)->count(),
        ]);
    }

    public function create()
    {
        return view('admin.supplier.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:150|unique:suppliers,nama',
            'kontak'  => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:100',
            'alamat'  => 'nullable|string',
            'kota'    => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
        ]);

        $supplier = Supplier::create([
            ...$request->only('nama', 'kontak', 'telepon', 'email', 'alamat', 'kota', 'catatan'),
            'aktif' => true,
        ]);

        ActivityLog::catat('tambah_supplier', "Supplier '{$supplier->nama}' ditambahkan", '🏭', $supplier);
        return redirect()->route('admin.supplier.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load(['barangSupplier.produk']);
        $produks = Produk::orderBy('nama')->get(['id', 'nama', 'harga', 'harga_promo', 'satuan']);
        return view('admin.supplier.form', compact('supplier', 'produks'));
    }

    public function edit(Supplier $supplier)
    {
        $supplier->load(['barangSupplier.produk']);
        $produks = Produk::orderBy('nama')->get(['id', 'nama', 'harga', 'harga_promo', 'satuan']);
        return view('admin.supplier.form', compact('supplier', 'produks'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'nama'    => 'required|string|max:150|unique:suppliers,nama,' . $supplier->id,
            'kontak'  => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:100',
            'alamat'  => 'nullable|string',
            'kota'    => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
            'aktif'   => 'boolean',
        ]);

        $supplier->update([
            ...$request->only('nama', 'kontak', 'telepon', 'email', 'alamat', 'kota', 'catatan'),
            'aktif' => $request->boolean('aktif'),
        ]);

        ActivityLog::catat('edit_supplier', "Supplier '{$supplier->nama}' diperbarui", '✏️', $supplier);
        return redirect()->route('admin.supplier.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->produk()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus supplier yang masih memiliki produk.');
        }
        $supplier->delete();
        return back()->with('success', 'Supplier berhasil dihapus.');
    }

    /* ═══════════════════════════════════════
     |  BARANG SUPPLIER
    ═══════════════════════════════════════ */

    public function storeBarang(Request $request, Supplier $supplier)
    {
        $request->validate([
            'produk_id'         => 'required|exists:produks,id',
            'harga_beli'        => 'required|numeric|min:0',
            'satuan'            => 'required|string|max:30',
            'minimal_pembelian' => 'required|integer|min:1',
            'lead_time_hari'    => 'nullable|integer|min:0',
            'catatan'           => 'nullable|string|max:500',
        ]);

        // Cek duplikat supplier-produk
        $exists = ProdukSupplier::where('supplier_id', $supplier->id)
            ->where('produk_id', $request->produk_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Produk ini sudah ada di daftar barang supplier.');
        }

        ProdukSupplier::create([
            'supplier_id'       => $supplier->id,
            'produk_id'         => $request->produk_id,
            'harga_beli'        => $request->harga_beli,
            'satuan'            => $request->satuan,
            'minimal_pembelian' => $request->minimal_pembelian,
            'lead_time_hari'    => $request->lead_time_hari,
            'catatan'           => $request->catatan,
            'aktif'             => true,
        ]);

        ActivityLog::catat('tambah_barang_supplier', "Barang ditambahkan ke supplier '{$supplier->nama}'", '📦', $supplier);
        return back()->with('success', 'Barang supplier berhasil ditambahkan.');
    }

    public function updateBarang(Request $request, Supplier $supplier, ProdukSupplier $barang)
    {
        $request->validate([
            'harga_beli'        => 'required|numeric|min:0',
            'satuan'            => 'required|string|max:30',
            'minimal_pembelian' => 'required|integer|min:1',
            'lead_time_hari'    => 'nullable|integer|min:0',
            'catatan'           => 'nullable|string|max:500',
        ]);

        $barang->update($request->only('harga_beli', 'satuan', 'minimal_pembelian', 'lead_time_hari', 'catatan'));

        ActivityLog::catat('edit_barang_supplier', "Harga barang supplier '{$supplier->nama}' diperbarui", '✏️', $supplier);
        return back()->with('success', 'Barang supplier berhasil diperbarui.');
    }

    public function destroyBarang(Supplier $supplier, ProdukSupplier $barang)
    {
        $barang->delete();
        return back()->with('success', 'Barang supplier berhasil dihapus.');
    }

    public function toggleBarang(Supplier $supplier, ProdukSupplier $barang)
    {
        $barang->update(['aktif' => !$barang->aktif]);
        $status = $barang->aktif ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Barang supplier berhasil {$status}.");
    }
}
