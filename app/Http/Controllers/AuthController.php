<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Logika pindah halaman sesuai ROLE
            $role = Auth::user()->role;
            if ($role === 'admin') return redirect()->intended('/admin/dashboard');
            if ($role === 'siswa') return redirect()->intended('/siswa/dashboard');
            if ($role === 'instruktur') return redirect()->intended('/instruktur/dashboard');
            if ($role === 'management') return redirect()->intended('/management/dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}