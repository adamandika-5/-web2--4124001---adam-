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
        return view('pdf.laporan', compact('pesanans'));
    }

    public function exportExcel()
    {
        $filename = 'Laporan-Sinar-Alam-' . date('Ymd-His') . '.csv';

        $totalProduk     = Produk::count();
        $totalPesanan    = Pesanan::count();
        $totalPendapatan = Pesanan::where('status', 'selesai')->sum('total');
        $totalUser       = User::where('role', 'user')->count();
        $totalBooking    = BookingAlat::count();

        $callback = function () use ($totalProduk, $totalPesanan, $totalPendapatan, $totalUser, $totalBooking) {
            $handle = fopen('php://output', 'w');

            // BOM UTF-8 agar Excel baca tanpa mojibake
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Judul
            fputcsv($handle, ['LAPORAN RINGKASAN — SINAR ALAM']);
            fputcsv($handle, ['Tanggal Export', now()->format('d/m/Y H:i:s')]);
            fputcsv($handle, []);

            // Header tabel ringkasan
            fputcsv($handle, ['Keterangan', 'Nilai']);
            fputcsv($handle, ['Total Produk Aktif',      $totalProduk]);
            fputcsv($handle, ['Total Pesanan',           $totalPesanan]);
            fputcsv($handle, ['Total Pendapatan (Selesai)', 'Rp ' . number_format($totalPendapatan, 0, ',', '.')]);
            fputcsv($handle, ['Total User/Pelanggan',    $totalUser]);
            fputcsv($handle, ['Total Booking Sewa Alat', $totalBooking]);
            fputcsv($handle, []);

            // Detail pesanan per bulan (6 bulan terakhir)
            fputcsv($handle, ['Pendapatan per Bulan (Status: Selesai)']);
            fputcsv($handle, ['Bulan', 'Jumlah Pesanan', 'Total Pendapatan (Rp)']);
            for ($i = 5; $i >= 0; $i--) {
                $bln = now()->subMonths($i);
                $jumlah = Pesanan::where('status', 'selesai')
                    ->whereYear('created_at', $bln->year)
                    ->whereMonth('created_at', $bln->month)
                    ->count();
                $total = Pesanan::where('status', 'selesai')
                    ->whereYear('created_at', $bln->year)
                    ->whereMonth('created_at', $bln->month)
                    ->sum('total');
                fputcsv($handle, [
                    $bln->format('F Y'),
                    $jumlah,
                    number_format($total, 0, ',', '.'),
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