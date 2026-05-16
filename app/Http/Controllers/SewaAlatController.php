<?php

namespace App\Http\Controllers;

use App\Models\{AlatBangunan, BookingAlat, ActivityLog};
use Illuminate\Http\Request;
use Carbon\Carbon;

class SewaAlatController extends Controller
{
    public function index(Request $request)
    {
        $query = AlatBangunan::aktif();

        if ($request->filled('q')) {
            $query->where('nama', 'LIKE', "%{$request->q}%");
        }
        if ($request->filled('kategori')) {
            $query->where('kategori_alat', $request->kategori);
        }
        if ($request->get('status') === 'tersedia') {
            $query->tersedia();
        }

        return view('pages.sewa-alat', [
            'alat'       => $query->paginate(12)->withQueryString(),
            'kategoris'  => AlatBangunan::aktif()
                                ->select('kategori_alat')
                                ->distinct()
                                ->pluck('kategori_alat')
                                ->filter(),
            'totalAlat'  => AlatBangunan::aktif()->count(),
        ]);
    }

    public function show(string $slug)
    {
        $alat = AlatBangunan::aktif()->where('slug', $slug)->firstOrFail();
        return view('pages.sewa-detail', compact('alat'));
    }

    public function kalkulasi(Request $request, string $slug)
    {
        $alat    = AlatBangunan::aktif()->where('slug', $slug)->firstOrFail();
        $mulai   = Carbon::parse($request->mulai);
        $selesai = Carbon::parse($request->selesai);
        $durasi  = $mulai->diffInDays($selesai) + 1;
        $total   = $durasi * $alat->tarif_harian;

        return response()->json([
            'durasi'  => $durasi,
            'total'   => $total,
            'deposit' => $alat->deposit,
            'grand'   => $total + $alat->deposit,
        ]);
    }

    public function booking(Request $request, string $slug)
    {
        $request->validate([
            'tanggal_mulai'   => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'alamat'          => 'nullable|string',
        ]);

        $alat = AlatBangunan::aktif()->tersedia()->where('slug', $slug)->firstOrFail();

        $mulai   = Carbon::parse($request->tanggal_mulai);
        $selesai = Carbon::parse($request->tanggal_selesai);
        $durasi  = $mulai->diffInDays($selesai) + 1;
        $total   = $durasi * $alat->tarif_harian;

        $booking = BookingAlat::create([
            'nomor_booking'    => BookingAlat::generateNomor(),
            'user_id'          => auth()->id(),
            'alat_id'          => $alat->id,
            'tanggal_mulai'    => $request->tanggal_mulai,
            'tanggal_selesai'  => $request->tanggal_selesai,
            'durasi_hari'      => $durasi,
            'tarif_per_hari'   => $alat->tarif_harian,
            'total_sewa'       => $total,
            'deposit'          => $alat->deposit,
            'total_bayar'      => $total + $alat->deposit,
            'status'           => 'pending',
            'alamat_penggunaan'=> $request->alamat,
            'catatan'          => $request->catatan,
        ]);

        $alat->decrement('tersedia');

        ActivityLog::catat(
            'booking_alat',
            "Booking {$booking->nomor_booking} untuk alat {$alat->nama}",
            '🔧',
            $booking
        );

        return redirect()->route('sewa.riwayat')
            ->with('success', "Booking {$booking->nomor_booking} berhasil dibuat!");
    }

    public function riwayat(Request $request)
    {
        $query = auth()->user()->bookings()
            ->with('alat')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return view('pages.sewa-riwayat', [
            'bookings' => $query->paginate(10)->withQueryString(),
        ]);
    }
}