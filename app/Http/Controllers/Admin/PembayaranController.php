<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Pembayaran, ActivityLog};
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with(['pesanan.user', 'pesanan.items', 'dikonfirmasiOleh'])->latest();

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('metode')) $query->where('metode', $request->metode);

        return view('admin.pembayaran.index', [
            'pembayarans'        => $query->paginate(20)->withQueryString(),
            'menunggu'           => Pembayaran::where('status', 'menunggu')->count(),
            'dikonfirmasiHariIni'=> Pembayaran::where('status', 'dikonfirmasi')
                                        ->whereDate('dikonfirmasi_at', today())->count(),
            'totalBulanIni'      => Pembayaran::where('status', 'dikonfirmasi')
                                        ->whereMonth('dikonfirmasi_at', now()->month)
                                        ->sum('jumlah'),
        ]);
    }

    public function konfirmasi(Pembayaran $pembayaran)
    {
        $pembayaran->update([
            'status'           => 'dikonfirmasi',
            'dikonfirmasi_at'  => now(),
            'dikonfirmasi_oleh'=> auth()->id(),
        ]);

        // Update status pembayaran pesanan
        $pembayaran->pesanan->update(['status_pembayaran' => 'lunas']);

        ActivityLog::catat(
            'konfirmasi_bayar',
            "Pembayaran pesanan {$pembayaran->pesanan->nomor_pesanan} dikonfirmasi",
            '✅',
            $pembayaran
        );

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function tolak(Request $request, Pembayaran $pembayaran)
    {
        $pembayaran->update([
            'status'        => 'ditolak',
            'catatan_admin' => $request->catatan ?? 'Bukti pembayaran tidak valid.',
        ]);

        ActivityLog::catat(
            'tolak_bayar',
            "Pembayaran pesanan {$pembayaran->pesanan->nomor_pesanan} ditolak",
            '❌',
            $pembayaran
        );

        return back()->with('success', 'Pembayaran berhasil ditolak. Pelanggan perlu mengunggah ulang.');
    }
}