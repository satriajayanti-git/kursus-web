<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class InstructorController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        
        // Pengecekan: Jika Admin belum ditugaskan ke cabang
        if (!$admin->branch_id) {
            return redirect('/admin/dashboard')->with('error', 'Akun Anda belum ditugaskan ke cabang manapun!');
        }

        // Ambil data instruktur HANYA yang ditugaskan di cabang admin tersebut
        // Ditambahkan eager loading 'unit_pegangan' agar tidak N+1 Query
        $instructors = User::with('unit_pegangan')
                           ->where('role', 'instruktur')
                           ->where('branch_id', $admin->branch_id)
                           ->get();
                           
        return view('admin.instruktur.index', compact('instructors'));
    }

    // Fungsi update HANYA untuk Reset Password (akses edit profil dicabut)
    public function update(Request $request, $id)
    {
        $instructor = User::findOrFail($id);

        // Keamanan Tambahan: Blokir jika coba edit instruktur cabang lain
        if ($instructor->branch_id != Auth::user()->branch_id) {
            return back()->with('error', 'Akses ditolak! Instruktur ini bertugas di cabang lain.');
        }

        $request->validate([
            'password' => 'required|string|min:6'
        ]);

        $instructor->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password instruktur berhasil direset!');
    }
}