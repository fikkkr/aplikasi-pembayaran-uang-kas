<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function index()
    {
        return view('auth.login');
    }

    // Memproses data login yang dikirim user
    public function loginProses(Request $request)
    {
        // Validasi inputan
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Proses Autentikasi bawaan Laravel otomatis mencocokkan email & password
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()->with('loginError', 'Email atau password salah!')->withInput();
    }

    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();

        // Hancurkan session yang berjalan
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Lempar balik ke halaman login
        return redirect()->route('login');
    }
}