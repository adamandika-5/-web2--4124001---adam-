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
        try {
            ActivityLog::catat('logout', 'Keluar dari sistem', '🚪');
        } catch (\Throwable $e) {
            // Tetap lanjutkan logout meski pencatatan log gagal
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

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

