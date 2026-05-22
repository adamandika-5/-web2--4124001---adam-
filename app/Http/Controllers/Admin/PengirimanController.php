<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Pesanan, OngkirZona, ActivityLog};
use Illuminate\Http\Request;

class PengirimanController extends Controller
{
    /**
     * Daftar pesanan yang perlu penanganan pengiriman
     */
    public function index(Request $request)
    {
        $query = Pesanan::with(['user', 'items'])
            ->whereIn('status', ['dikonfirmasi', 'diproses', 'dikirim'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $query->where(fn($x) =>
                $x->where('nomor_pesanan', 'LIKE', "%{$request->q}%")
                  ->orWhere('penerima', 'LIKE', "%{$request->q}%")
                  ->orWhere('kota_tujuan', 'LIKE', "%{$request->q}%")
            );
        }

        return view('admin.pengiriman.index', [
            'pesanans' => $query->paginate(20)->withQueryString(),
            'siapKirim'=> Pesanan::where('status', 'diproses')->count(),
            'dikirim'  => Pesanan::where('status', 'dikirim')->count(),
        ]);
    }

    /**
     * Detail pesanan pengiriman
     */
    public function show(Pesanan $pengiriman)
    {
        $pengiriman->load(['user', 'items.produk', 'pembayaran']);
        return view('admin.pengiriman.show', ['pesanan' => $pengiriman]);
    }

    /**
     * Update status pengiriman (set dikirim / selesai)
     */
    public function update(Request $request, Pesanan $pengiriman)
    {
        $request->validate([
            'status'        => 'required|in:diproses,dikirim,selesai',
            'catatan_admin' => 'nullable|string',
        ]);

        $updateData = ['status' => $request->status];

        if (\Schema::hasColumn('pesanans', 'catatan_admin')) {
            $updateData['catatan_admin'] = $request->catatan_admin;
        }

        if (\Schema::hasColumn('pesanans', 'dikirim_at')) {
            $updateData['dikirim_at'] = $request->status === 'dikirim' ? now() : $pengiriman->dikirim_at;
        }

        if (\Schema::hasColumn('pesanans', 'selesai_at')) {
            $updateData['selesai_at'] = $request->status === 'selesai' ? now() : $pengiriman->selesai_at;
        }

        $pengiriman->update($updateData);

        ActivityLog::catat(
            'update_pengiriman',
            "Status pengiriman pesanan {$pengiriman->nomor_pesanan} → {$request->status}",
            '🚚',
            $pengiriman
        );

        return back()->with('success', 'Status pengiriman berhasil diperbarui.');
    }

    /**
     * Halaman manajemen zona ongkir armada
     */
    public function ongkir(Request $request)
    {
        return view('admin.pengiriman.ongkir', [
            'zonas' => OngkirZona::orderBy('provinsi')->orderBy('kota')->paginate(30),
        ]);
    }

    /**
     * Simpan/update zona ongkir
     */
    public function simpanOngkir(Request $request)
    {
        $request->validate([
            'kota'              => 'required|string|max:100',
            'kabupaten'         => 'nullable|string|max:100',
            'provinsi'          => 'required|string|max:100',
            'jarak_km'          => 'required|numeric|min:0',
            'zona'              => 'required|string|max:50',
            'tarif_pickup'      => 'required|numeric|min:0',
            'tarif_engkel'      => 'nullable|numeric|min:0',
            'tarif_truk'        => 'nullable|numeric|min:0',
            'tersedia_armada'   => 'boolean',
        ]);

        OngkirZona::updateOrCreate(
            ['kota' => $request->kota, 'provinsi' => $request->provinsi],
            [
                ...$request->only('kota', 'kabupaten', 'provinsi', 'jarak_km', 'zona', 'tarif_pickup', 'tarif_engkel', 'tarif_truk'),
                'tersedia_armada' => $request->boolean('tersedia_armada', true),
            ]
        );

        return back()->with('success', 'Zona ongkir berhasil disimpan.');
    }
}
