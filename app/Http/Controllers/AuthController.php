<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Fungsi buat proses masuk
    public function login(Request $request)
    {
        // 1. Validasi inputan biar gak kosong
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Cek di database, ada gak usernya?
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Biar aman dari pembajakan session
            return redirect()->intended('/dashboard'); // Lempar ke dashboard
        }

        // 3. Kalo gagal, balikin ke login pake pesan error
        return back()->withErrors([
            'email' => 'Email atau Password salah, Cik! 🗿',
        ]);
    }

    // Fungsi buat kabur (logout)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}