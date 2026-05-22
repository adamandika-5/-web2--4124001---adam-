<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Pengaturan, ActivityLog};
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        return view('admin.pengaturan');
    }

    public function update(Request $request)
    {
        // Simpan semua pengaturan dari array settings[]
        if ($request->has('settings')) {
            foreach ($request->settings as $kunci => $nilai) {
                // Tentukan grup berdasarkan prefix kunci
                $grup = match (true) {
                    str_starts_with($kunci, 'bank_') || $kunci === 'qris_path'  => 'pembayaran',
                    in_array($kunci, ['telepon', 'whatsapp', 'email', 'alamat', 'kota', 'maps_url', 'jam_ops']) => 'kontak',
                    in_array($kunci, ['batas_berat_ekspedisi', 'min_order_armada', 'gmaps_api_key', 'kota_asal']) => 'pengiriman',
                    in_array($kunci, ['meta_title', 'meta_desc', 'ga_id', 'fb_pixel']) => 'seo',
                    str_starts_with($kunci, 'notif_') => 'notifikasi',
                    default => 'umum',
                };
                Pengaturan::set($kunci, $nilai, $grup);
            }
        }

        // Upload logo
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('pengaturan', 'public');
            Pengaturan::set('logo', $path, 'umum');
        }

        // Upload favicon
        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('pengaturan', 'public');
            Pengaturan::set('favicon', $path, 'umum');
        }

        // Upload QRIS
        if ($request->hasFile('qris_image')) {
            $path = $request->file('qris_image')->store('pengaturan', 'public');
            Pengaturan::set('qris_path', $path, 'pembayaran');
        }

        ActivityLog::catat('update_pengaturan', 'Pengaturan toko diperbarui', '⚙️');
        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
