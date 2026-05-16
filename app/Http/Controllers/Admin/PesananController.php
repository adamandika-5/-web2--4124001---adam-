<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Pesanan, ActivityLog};
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::with('user')->withCount('items')->latest();

        if ($request->filled('q')) {
            $query->where(fn($x) =>
                $x->where('nomor_pesanan', 'LIKE', "%{$request->q}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'LIKE', "%{$request->q}%"))
            );
        }
        if ($request->filled('status'))     $query->where('status', $request->status);
        if ($request->filled('pengiriman')) $query->where('jenis_pengiriman', $request->pengiriman);
        if ($request->filled('bayar'))      $query->where('status_pembayaran', $request->bayar);
        if ($request->filled('tgl'))        $query->whereDate('created_at', $request->tgl);

        return view('admin.pesanan.index', [
            'pesanans'        => $query->paginate(20)->withQueryString(),
            'total'           => Pesanan::count(),
            'totalPending'    => Pesanan::where('status', 'pending')->count(),
            'totalDiproses'   => Pesanan::where('status', 'diproses')->count(),
            'totalDikirim'    => Pesanan::where('status', 'dikirim')->count(),
            'totalSelesai'    => Pesanan::where('status', 'selesai')->count(),
        ]);
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load([
            'user',
            'items.produk.gambar',
            'pembayaran.dikonfirmasiOleh',
            'voucher',
        ]);
        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function updateStatus(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'status'        => 'required|in:pending,dikonfirmasi,diproses,dikirim,selesai,batal',
            'catatan_admin' => 'nullable|string',
        ]);

        $statusLama = $pesanan->status;

        $pesanan->update([
            'status'        => $request->status,
            'catatan_admin' => $request->catatan_admin,
            'dikirim_at'    => $request->status === 'dikirim' ? now() : $pesanan->dikirim_at,
            'selesai_at'    => $request->status === 'selesai' ? now() : $pesanan->selesai_at,
        ]);

        // Kembalikan stok jika dibatalkan
        if ($request->status === 'batal' && $statusLama !== 'batal') {
            foreach ($pesanan->items as $item) {
                $item->produk?->increment('stok', $item->qty);
                $item->produk?->decrement('terjual', $item->qty);
            }
        }

        ActivityLog::catat(
            'update_status_pesanan',
            "Status pesanan {$pesanan->nomor_pesanan} diubah dari {$statusLama} ke {$request->status}",
            '📦',
            $pesanan
        );

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function export()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\PesananExport(),
            'pesanan-sinar-alam-' . date('Ymd') . '.xlsx'
        );
    }
}