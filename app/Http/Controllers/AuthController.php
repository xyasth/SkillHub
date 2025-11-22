<?php

namespace App\Http\Controllers; // <-- Pastikan namespace ini benar

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller // <-- "Controller" ini merujuk ke file di langkah 1
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'instruktur') {
                return redirect()->route('instruktur.dashboard');
            } else {
                return redirect()->route('peserta.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
