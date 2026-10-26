<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KontrollerAutentikasi extends Controller
{
    public function tampilkanFormMasuk()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('kasir.dashboard');
        }
        return view('autentikasi.masuk');
    }

    public function masuk(Request $request)
    {
        $credentials = $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required'    => 'Email atau Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $loginType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $attemptData = [
            $loginType => $credentials['login'],
            'password' => $credentials['password'],
        ];

        if (Auth::attempt($attemptData, $request->boolean('remember'))) {
            $user = Auth::user();

            if (!$user->isActive()) {
                Auth::logout();
                return back()->withErrors([
                    'login' => 'Akun Anda dinonaktifkan. Silakan hubungi admin.',
                ])->onlyInput('login');
            }

            $request->session()->regenerate();

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang kembali, ' . $user->name . '!');
            }

            return redirect()->intended(route('kasir.dashboard'))->with('success', 'Selamat datang di kasir, ' . $user->name . '!');
        }

        return back()->withErrors([
            'login' => 'Kombinasi email/username dan password tidak sesuai.',
        ])->onlyInput('login');
    }

    public function keluar(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }

    public function tampilkanLupaSandi()
    {
        return view('autentikasi.lupa-sandi');
    }

    public function showLogin()
    {
        return $this->tampilkanFormMasuk();
    }

    public function login(Request $request)
    {
        return $this->masuk($request);
    }

    public function logout(Request $request)
    {
        return $this->keluar($request);
    }

    public function showForgotPassword()
    {
        return $this->tampilkanLupaSandi();
    }
}
