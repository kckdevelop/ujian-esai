<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan form login admin.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.exams.index');
        }

        return view('auth.login');
    }

    /**
     * Proses autentikasi login admin.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ], [
            'email.required'    => 'Email atau username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        // Dukung login via email ataupun username (name)
        $loginField = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        $authAttempt = [
            $loginField => $credentials['email'],
            'password'  => $credentials['password'],
        ];

        if (Auth::attempt($authAttempt, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.exams.index'))
                             ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email/Username atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Logout admin.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
                         ->with('success', 'Anda telah berhasil logout.');
    }
}
