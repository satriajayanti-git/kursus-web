<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Unit;
use App\Models\LaporanUnit;
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

        // Ambil data armada/unit yang terdaftar di cabang admin
        $units = Unit::where('branch_id', $admin->branch_id)->get();

        // Ambil data laporan kendala unit di cabang admin
        $laporans = LaporanUnit::whereHas('unit', function($q) use ($admin) {
                        $q->where('branch_id', $admin->branch_id);
                    })
                    ->with(['unit', 'instruktur'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                           
        return view('admin.instruktur.index', compact('instructors', 'units', 'laporans'));
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

    // Fungsi untuk menambah laporan kendala unit dari pihak Admin
    public function storeLaporan(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'tingkat_kendala' => 'required|in:Ringan,Berat',
            'deskripsi' => 'required|string|max:500'
        ]);

        // Validation: Pastikan unit milik cabang admin
        $unit = Unit::findOrFail($request->unit_id);
        if ($unit->branch_id != $admin->branch_id) {
            return back()->with('error', 'Akses ditolak! Kendaraan ini bukan bagian dari cabang Anda.');
        }

        LaporanUnit::create([
            'unit_id' => $request->unit_id,
            'instruktur_id' => $admin->id,
            'tingkat_kendala' => $request->tingkat_kendala,
            'deskripsi' => $request->deskripsi,
            'status_laporan' => 'Menunggu'
        ]);

        return back()->with('success', 'Laporan kendala kendaraan berhasil dikirim!');
    }

    // Fungsi update status laporan unit KHUSUS kendala ringan untuk Admin
    public function updateLaporan(Request $request, $id)
    {
        $admin = Auth::user();
        $laporan = LaporanUnit::with('unit')->findOrFail($id);

        // Keamanan: Pastikan unit milik cabang admin
        if ($laporan->unit && $laporan->unit->branch_id != $admin->branch_id) {
            return back()->with('error', 'Akses ditolak! Laporan ini milik cabang lain.');
        }

        // Keamanan Utama: Admin dilarang memproses laporan kendala BERAT (harus via Management)
        if ($laporan->tingkat_kendala === 'Berat') {
            return back()->with('error', 'Akses ditolak! Perbaikan kendala berat hanya dapat ditangani oleh Management Pusat.');
        }

        $request->validate([
            'status_laporan' => 'required|in:Menunggu,Diproses,Selesai'
        ]);

        $laporan->update([
            'status_laporan' => $request->status_laporan
        ]);

        return back()->with('success', 'Status penanganan laporan kendala ringan berhasil diperbarui!');
    }
}