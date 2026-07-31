<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, Branch};
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    // Tampilkan daftar semua Admin & Instruktur
    public function index()
    {
        $karyawans = User::with('branch')
            ->whereIn('role', ['admin', 'instruktur'])
            ->orderBy('role', 'asc')
            ->get();
            
        $branches = Branch::all();
        return view('management.karyawan.index', compact('karyawans', 'branches'));
    }

    // Simpan Karyawan Baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,instruktur',
            'branch_id' => 'required|exists:branches,id',
            'no_telp' => 'required',
            'kategori_transmisi' => 'nullable|in:Manual,Matic,Manual & Matic' // Khusus instruktur
        ]);

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'branch_id' => $request->branch_id,
            'no_telp' => $request->no_telp,
            'kategori_transmisi' => $request->kategori_transmisi,
        ]);

        return back()->with('success', 'Karyawan baru berhasil didaftarkan ke sistem!');
    }

    // Update Data Karyawan / Mutasi Cabang
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'role' => 'required|in:admin,instruktur',
            'branch_id' => 'required|exists:branches,id',
            'no_telp' => 'required',
        ]);

        $data = $request->only(['nama_lengkap', 'role', 'branch_id', 'no_telp', 'kategori_transmisi']);
        
        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Data karyawan berhasil diperbarui!');
    }

    // Hapus Karyawan
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Akun karyawan telah dihapus dari sistem.');
    }
}