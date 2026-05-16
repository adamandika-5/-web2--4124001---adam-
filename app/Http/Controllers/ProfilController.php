<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    public function index()
    {
        $alamats = auth()->user()->alamat()->get();
        return view('pages.profil', compact('alamats'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'telepon' => 'nullable|string|max:20',
            'avatar'  => 'nullable|image|max:2048',
        ]);

        $data = $request->only('name', 'email', 'telepon');

        if ($request->hasFile('avatar')) {
            // Hapus avatar lama
            if ($user->avatar) {
                \Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatar', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak sesuai.']);
        }

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Kata sandi berhasil diubah.');
    }

    public function alamat()
    {
        $alamats = auth()->user()->alamat()->get();
        return view('pages.profil', compact('alamats'));
    }

    public function tambahAlamat(Request $request)
    {
        $request->validate([
            'label'          => 'required|string|max:50',
            'penerima'       => 'required|string|max:100',
            'telepon'        => 'required|string|max:20',
            'alamat_lengkap' => 'required|string',
            'kota'           => 'required|string',
            'provinsi'       => 'required|string',
            'kode_pos'       => 'nullable|string|max:10',
        ]);

        $user = auth()->user();

        // Jika is_utama = true, reset utama lain
        if ($request->boolean('is_utama')) {
            $user->alamat()->update(['is_utama' => false]);
        }

        $user->alamat()->create([
            ...$request->only('label', 'penerima', 'telepon', 'alamat_lengkap', 'kelurahan', 'kecamatan', 'kota', 'provinsi', 'kode_pos'),
            'is_utama' => $request->boolean('is_utama') || $user->alamat()->count() === 0,
        ]);

        return back()->with('success', 'Alamat baru berhasil ditambahkan.');
    }

    public function hapusAlamat(int $id)
    {
        $alamat = auth()->user()->alamat()->findOrFail($id);

        if ($alamat->is_utama) {
            return back()->with('error', 'Tidak dapat menghapus alamat utama. Jadikan alamat lain sebagai utama terlebih dahulu.');
        }

        $alamat->delete();
        return back()->with('success', 'Alamat berhasil dihapus.');
    }
}