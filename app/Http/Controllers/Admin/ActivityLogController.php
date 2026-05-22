<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{ActivityLog};
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        if ($request->filled('q')) {
            $query->where(fn($x) =>
                $x->where('aksi', 'LIKE', "%{$request->q}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$request->q}%")
            );
        }

        if ($request->filled('aksi')) {
            $query->where('aksi', $request->aksi);
        }

        if ($request->filled('tgl')) {
            $query->whereDate('created_at', $request->tgl);
        }

        return view('admin.activity-log', [
            'logs'      => $query->paginate(30)->withQueryString(),
            'totalLog'  => ActivityLog::count(),
            'hariIni'   => ActivityLog::whereDate('created_at', today())->count(),
        ]);
    }

    public function hapusSemua()
    {
        // Simpan log aksi ini dulu sebelum menghapus semua
        ActivityLog::catat('hapus_activity_log', 'Semua activity log dihapus oleh admin', '🗑️');

        // Hapus semua kecuali yang baru saja dibuat (ID terakhir)
        $last = ActivityLog::latest()->first();
        ActivityLog::where('id', '!=', $last?->id ?? 0)->delete();

        return back()->with('success', 'Semua activity log berhasil dihapus.');
    }
}
