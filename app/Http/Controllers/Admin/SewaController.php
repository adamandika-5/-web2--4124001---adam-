<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{AlatBangunan, BookingAlat, ActivityLog};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SewaController extends Controller
{
    public function index(Request $request)
    {
        $query = AlatBangunan::latest();
        if ($request->filled('status')) {
            if ($request->status === 'tersedia') $query->tersedia();
            if ($request->status === 'disewa')   $query->where('tersedia', 0);
        }

        return view('admin.sewa.index', [
            'alat'    => $query->paginate(15)->withQueryString(),
            'booking' => BookingAlat::with(['user', 'alat'])->where('status', 'aktif')->latest()->take(20)->get(),
            'stats'   => [
                'total'         => AlatBangunan::count(),
                'sedang_disewa' => BookingAlat::where('status', 'aktif')->count(),
                'tersedia'      => AlatBangunan::aktif()->sum('tersedia'),
                'terlambat'     => BookingAlat::where('status', 'aktif')
                                    ->where('tanggal_selesai', '<', today())->count(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'           => 'required|string|max:200',
            'kategori_alat'  => 'nullable|string',
            'tarif_harian'   => 'required|numeric|min:0',
            'tarif_mingguan' => 'nullable|numeric',
            'deposit'        => 'nullable|numeric|min:0',
            'denda_per_hari' => 'nullable|numeric|min:0',
            'jumlah_unit'    => 'required|integer|min:1',
            'kondisi'        => 'in:baik,cukup,perbaikan',
            'deskripsi'      => 'nullable|string',
            'gambar'         => 'nullable|image|max:3072',
        ]);

        $data              = $request->except('gambar');
        $data['slug']      = Str::slug($request->nama) . '-' . uniqid();
        $data['tersedia']  = $request->jumlah_unit;
        $data['aktif']     = true;

        $alat = AlatBangunan::create($data);

        if ($request->hasFile('gambar')) {
            $alat->update(['gambar' => $request->file('gambar')->store('alat', 'public')]);
        }

        ActivityLog::catat('tambah_alat', "Alat '{$alat->nama}' ditambahkan", '🔧', $alat);
        return back()->with('success', 'Alat berhasil ditambahkan.');
    }

    public function edit(AlatBangunan $sewa)
    {
        return view('admin.sewa.form', ['alat' => $sewa]);
    }

    public function update(Request $request, AlatBangunan $sewa)
    {
        $request->validate([
            'nama'         => 'required|string|max:200',
            'tarif_harian' => 'required|numeric|min:0',
            'jumlah_unit'  => 'required|integer|min:1',
        ]);

        $sewa->update($request->except(['gambar', '_token', '_method']));

        if ($request->hasFile('gambar')) {
            if ($sewa->gambar) \Storage::disk('public')->delete($sewa->gambar);
            $sewa->update(['gambar' => $request->file('gambar')->store('alat', 'public')]);
        }

        return back()->with('success', 'Data alat berhasil diperbarui.');
    }

    public function destroy(AlatBangunan $sewa)
    {
        if ($sewa->bookings()->where('status', 'aktif')->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus alat yang sedang disewa.');
        }
        $sewa->delete();
        return back()->with('success', 'Alat berhasil dihapus.');
    }

    public function toggleStatus(AlatBangunan $sewa)
    {
        $sewa->update(['aktif' => !$sewa->aktif]);
        return back()->with('success', 'Status alat diperbarui.');
    }

    public function booking()
    {
        return view('admin.sewa.booking', [
            'bookings' => BookingAlat::with(['user', 'alat'])->latest()->paginate(20),
        ]);
    }

    public function selesaiBooking(BookingAlat $booking)
    {
        $booking->update([
            'status'                  => 'selesai',
            'tanggal_kembali_aktual'  => today(),
        ]);

        $denda = $booking->hitungDenda();
        if ($denda > 0) {
            $terlambat = today()->diffInDays($booking->tanggal_selesai);
            $booking->update([
                'denda'         => $denda,
                'hari_terlambat'=> $terlambat,
                'total_bayar'   => $booking->total_bayar + $denda,
            ]);
        }

        $booking->alat->increment('tersedia');

        ActivityLog::catat(
            'selesai_sewa',
            "Booking {$booking->nomor_booking} diselesaikan" . ($denda > 0 ? ". Denda: Rp " . number_format($denda) : ''),
            '✅',
            $booking
        );

        return back()->with('success', 'Booking diselesaikan.' . ($denda > 0 ? " Denda: Rp " . number_format($denda) : ''));
    }

    public function catatDenda(Request $request, BookingAlat $booking)
    {
        $request->validate(['denda' => 'required|numeric|min:0']);

        $booking->update([
            'denda'         => $request->denda,
            'total_bayar'   => $booking->total_sewa + $booking->deposit + $request->denda,
        ]);

        return back()->with('success', 'Denda berhasil dicatat.');
    }
}