<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'telepon'  => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::min(8)],
            'agree'    => 'accepted',
        ], [
            'agree.accepted' => 'Anda harus menyetujui syarat dan ketentuan.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'telepon'  => $request->telepon,
            'password' => Hash::make($request->password),
            'role'     => 'user',
            'aktif'    => true,
        ]);

        Auth::login($user);

        return redirect()->route('beranda')
            ->with('success', "Selamat datang, {$user->name}! Akun berhasil dibuat.");
    }
}