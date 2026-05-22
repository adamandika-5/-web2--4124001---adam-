<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Supplier, ActivityLog};
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
        $supplier->load('produk');
        return view('admin.supplier.form', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.supplier.form', compact('supplier'));
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
}
