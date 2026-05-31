<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\{User, ActivityLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()->withErrors(['email' => 'Email atau kata sandi tidak sesuai.'])->withInput();
        }

        $user = Auth::user();

        if (!$user->aktif) {
            Auth::logout();
            return back()->with('error', 'Akun Anda telah dinonaktifkan. Hubungi admin.');
        }

        ActivityLog::catat('login', "Login berhasil dari " . $request->ip(), '🔑');

        $request->session()->regenerate();

        // Redirect berdasarkan role
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('beranda'));
    }

    public function logout(Request $request)
    {
        // Catat log SEBELUM invalidate session agar user_id masih tersedia
        // tapi setelah auth sudah aman diproses
        $userId = auth()->id();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Catat log setelah session clear — non-blocking, tidak menghambat redirect
        try {
            if ($userId) {
                \App\Models\ActivityLog::create([
                    'user_id'    => $userId,
                    'ikon'       => '🚪',
                    'aksi'       => 'logout',
                    'deskripsi'  => 'Keluar dari sistem',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }
        } catch (\Throwable $e) {
            // Tetap lanjutkan meski pencatatan log gagal
        }

        return redirect()->route('login');
    }

    public function redirectToGoogle()
    {
        return \Laravel\Socialite\Facades\Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')->user();

            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name'      => $googleUser->name,
                    'google_id' => $googleUser->id,
                    'password'  => bcrypt(\Illuminate\Support\Str::random(24)),
                    'aktif'     => true,
                ]
            );

            Auth::login($user);
            ActivityLog::catat('login_google', 'Login via Google', '🔵', $user);

            return redirect()->route($user->isAdmin() ? 'admin.dashboard' : 'beranda');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Login Google gagal. Coba lagi.');
        }
    }
}

