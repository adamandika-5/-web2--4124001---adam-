<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, ActivityLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withTrashed()->latest();

        if ($request->filled('q')) {
            $query->where(fn($x) =>
                $x->where('name', 'LIKE', "%{$request->q}%")
                  ->orWhere('email', 'LIKE', "%{$request->q}%")
            );
        }

        if ($request->filled('role'))   $query->where('role', $request->role);
        if ($request->status === 'aktif')    $query->where('aktif', true)->withoutTrashed();
        if ($request->status === 'nonaktif') $query->where('aktif', false)->withoutTrashed();

        return view('admin.users.index', [
            'users'      => $query->paginate(20)->withQueryString(),
            'totalUser'  => User::where('role', 'user')->count(),
            'totalAdmin' => User::whereIn('role', ['admin', 'staff'])->count(),
            'nonaktif'   => User::where('aktif', false)->count(),
        ]);
    }

    public function create()
    {
        return view('admin.users.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:user,admin,staff',
            'telepon'  => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'telepon'  => $request->telepon,
            'aktif'    => true,
        ]);

        ActivityLog::catat('tambah_user', "User '{$user->email}' (role: {$user->role}) ditambahkan", '👤', $user);
        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'role'    => 'required|in:user,admin,staff',
            'telepon' => 'nullable|string|max:20',
            'aktif'   => 'boolean',
        ]);

        $user->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'role'    => $request->role,
            'telepon' => $request->telepon,
            'aktif'   => $request->boolean('aktif', true),
        ]);

        ActivityLog::catat('edit_user', "User '{$user->email}' diperbarui", '✏️', $user);
        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun yang sedang aktif.');
        }
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    public function toggleAktif(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }
        $user->update(['aktif' => !$user->aktif]);
        $status = $user->aktif ? 'diaktifkan' : 'dinonaktifkan';
        ActivityLog::catat('toggle_user', "User '{$user->email}' {$status}", '🔄', $user);
        return back()->with('success', "User berhasil {$status}.");
    }

    public function resetPassword(User $user)
    {
        $password = 'Password1!';
        $user->update(['password' => Hash::make($password)]);
        ActivityLog::catat('reset_password', "Password user '{$user->email}' direset", '🔑', $user);
        return back()->with('success', "Password user berhasil direset ke default. Sampaikan ke user untuk segera menggantinya.");
    }
}
