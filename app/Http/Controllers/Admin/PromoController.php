<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Promo, Voucher, ActivityLog};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromoController extends Controller
{
    /* ═══════════════════════════════════════
     |  PROMO
    ═══════════════════════════════════════ */

    public function index(Request $request)
    {
        $promos   = Promo::latest()->paginate(15)->withQueryString();
        $vouchers = Voucher::latest()->paginate(15)->withQueryString();

        return view('admin.promo.index', compact('promos', 'vouchers'));
    }

    public function create()
    {
        return view('admin.promo.Form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'        => 'required|string|max:150',
            'tipe'        => 'required|in:persentase,nominal',
            'nilai'       => 'required|numeric|min:0',
            'min_belanja' => 'nullable|numeric|min:0',
            'maks_diskon' => 'nullable|numeric|min:0',
            'mulai_at'    => 'required|date',
            'berakhir_at' => 'required|date|after:mulai_at',
            'aktif'       => 'boolean',
        ]);

        $promo = Promo::create([
            ...$request->only('nama', 'tipe', 'nilai', 'min_belanja', 'maks_diskon', 'deskripsi', 'mulai_at', 'berakhir_at'),
            'slug'  => Str::slug($request->nama) . '-' . uniqid(),
            'aktif' => $request->boolean('aktif', true),
        ]);

        ActivityLog::catat('tambah_promo', "Promo '{$promo->nama}' ditambahkan", '🏷️', $promo);
        return redirect()->route('admin.promo.index')->with('success', 'Promo berhasil ditambahkan.');
    }

    public function show(Promo $promo)
    {
        return view('admin.promo.Form', compact('promo'));
    }

    public function edit(Promo $promo)
    {
        return view('admin.promo.Form', compact('promo'));
    }

    public function update(Request $request, Promo $promo)
    {
        $request->validate([
            'nama'        => 'required|string|max:150',
            'tipe'        => 'required|in:persentase,nominal',
            'nilai'       => 'required|numeric|min:0',
            'min_belanja' => 'nullable|numeric|min:0',
            'maks_diskon' => 'nullable|numeric|min:0',
            'mulai_at'    => 'required|date',
            'berakhir_at' => 'required|date|after:mulai_at',
            'aktif'       => 'boolean',
        ]);

        $promo->update([
            ...$request->only('nama', 'tipe', 'nilai', 'min_belanja', 'maks_diskon', 'deskripsi', 'mulai_at', 'berakhir_at'),
            'aktif' => $request->boolean('aktif'),
        ]);

        ActivityLog::catat('edit_promo', "Promo '{$promo->nama}' diperbarui", '✏️', $promo);
        return redirect()->route('admin.promo.index')->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();
        return back()->with('success', 'Promo berhasil dihapus.');
    }

    /* ═══════════════════════════════════════
     |  VOUCHER
    ═══════════════════════════════════════ */

    public function indexVoucher(Request $request)
    {
        return redirect()->route('admin.promo.index');
    }

    public function storeVoucher(Request $request)
    {
        $request->validate([
            'kode'          => 'required|string|max:50|unique:vouchers,kode',
            'nama'          => 'required|string|max:150',
            'tipe'          => 'required|in:persentase,nominal',
            'nilai'         => 'required|numeric|min:0',
            'min_belanja'   => 'nullable|numeric|min:0',
            'maks_diskon'   => 'nullable|numeric|min:0',
            'kuota'         => 'nullable|integer|min:1',
            'berlaku_mulai' => 'nullable|date',
            'berlaku_sampai'=> 'nullable|date|after_or_equal:berlaku_mulai',
            'aktif'         => 'boolean',
        ]);

        $voucher = Voucher::create([
            ...$request->only('kode', 'nama', 'tipe', 'nilai', 'min_belanja', 'maks_diskon', 'kuota', 'berlaku_mulai', 'berlaku_sampai'),
            'kode'  => strtoupper($request->kode),
            'aktif' => $request->boolean('aktif', true),
        ]);

        ActivityLog::catat('tambah_voucher', "Voucher '{$voucher->kode}' ditambahkan", '🎟️', $voucher);
        return back()->with('success', 'Voucher berhasil ditambahkan.');
    }

    public function destroyVoucher(Voucher $voucher)
    {
        $voucher->delete();
        return back()->with('success', 'Voucher berhasil dihapus.');
    }
}
