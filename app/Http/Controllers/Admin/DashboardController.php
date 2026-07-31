<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\{User, Jadwal, Pembayaran, Setting};
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $branchId = $user->branch_id; 
        
        $setting = Setting::first();
        $hariIni = Carbon::today()->toDateString();

        // LOGIC PENCEGAHAN: Cek apakah Admin sudah punya cabang penugasan
        if ($branchId) {
            // Jika ada cabang, hitung data spesifik untuk cabang tersebut
            $siswaAktif = User::where('role', 'siswa')->where('branch_id', $branchId)->count();
            $jadwalHariIni = Jadwal::where('tanggal', $hariIni)->where('branch_id', $branchId)->count();
            $pendaftaranBaru = Pembayaran::where('status', 'Pending')->where('branch_id', $branchId)->count();
            $instrukturTersedia = User::where('role', 'instruktur')->where('branch_id', $branchId)->count();
        } else {
            // Jika belum punya cabang (Data Lama / Belum Di-assign Management), set 0 semua
            $siswaAktif = 0;
            $jadwalHariIni = 0;
            $pendaftaranBaru = 0;
            $instrukturTersedia = 0;
        }

        return view('admin.dashboard', compact(
            'user', 'setting', 'siswaAktif', 'jadwalHariIni', 'pendaftaranBaru', 'instrukturTersedia'
        ));
    }
}