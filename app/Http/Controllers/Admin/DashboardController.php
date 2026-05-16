<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Pesanan, Produk, User, BookingAlat, ActivityLog};

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni  = now()->startOfMonth();
        $bulanLalu = now()->subMonth()->startOfMonth();
        $akhirLalu = now()->subMonth()->endOfMonth();

        $pendapatanBulanIni  = Pesanan::where('status', 'selesai')->whereBetween('created_at', [$bulanIni, now()])->sum('total');
        $pendapatanBulanLalu = Pesanan::where('status', 'selesai')->whereBetween('created_at', [$bulanLalu, $akhirLalu])->sum('total');
        $pendapatanGrowth    = $pendapatanBulanLalu > 0
            ? round((($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100, 1)
            : 0;

        $pesananBulanIni  = Pesanan::whereBetween('created_at', [$bulanIni, now()])->count();
        $pesananBulanLalu = Pesanan::whereBetween('created_at', [$bulanLalu, $akhirLalu])->count();
        $pesananGrowth    = $pesananBulanLalu > 0
            ? round((($pesananBulanIni - $pesananBulanLalu) / $pesananBulanLalu) * 100, 1)
            : 0;

        // Grafik 7 bulan terakhir
        $grafikBulanan = collect(range(6, 0))->map(function ($i) {
            $bln = now()->subMonths($i);
            return [
                'label' => $bln->format('M'),
                'total' => Pesanan::where('status', 'selesai')
                    ->whereYear('created_at', $bln->year)
                    ->whereMonth('created_at', $bln->month)
                    ->sum('total'),
                'aktif' => $i === 0,
            ];
        });

        return view('admin.dashboard', [
            'pendapatanBulanIni' => $pendapatanBulanIni,
            'pendapatanGrowth'   => $pendapatanGrowth,
            'pesananBulanIni'    => $pesananBulanIni,
            'pesananGrowth'      => $pesananGrowth,
            'pelangganBaru'      => User::where('role', 'user')->whereBetween('created_at', [$bulanIni, now()])->count(),
            'sewaAktif'          => BookingAlat::where('status', 'aktif')->count(),
            'sewaTerlambat'      => BookingAlat::where('status', 'aktif')->where('tanggal_selesai', '<', today())->count(),
            'stokRendah'         => Produk::aktif()->stokRendah()->with('kategori')->take(8)->get(),
            'pesananTerbaru'     => Pesanan::with('user')->withCount('items')->latest()->take(8)->get(),
            'aktivitasTerbaru'   => ActivityLog::with('user')->latest()->take(8)->get(),
            'grafikBulanan'      => $grafikBulanan,
            'grafikMax'          => $grafikBulanan->max('total') ?: 1,
        ]);
    }

    public function laporan()
    {
        return view('admin.laporan');
    }

    public function exportPdf()
    {
        $pesanans = Pesanan::with(['user', 'items'])->where('status', 'selesai')->latest()->get();
        $pdf      = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.laporan', compact('pesanans'));
        return $pdf->download('Laporan-Sinar-Alam-' . date('Ymd') . '.pdf');
    }

    public function exportExcel()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\LaporanExport(),
            'Laporan-Sinar-Alam-' . date('Ymd') . '.xlsx'
        );
    }
}