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
        $bulanIni = date('m');
        $tahunIni = date('Y');

        $chartLabels = [];
        $chartData = [];

        // LOGIC PENCEGAHAN: Cek apakah Admin sudah punya cabang penugasan
        if ($branchId) {
            // 1. Siswa Aktif (Hanya yang statusnya 'Aktif')
            $siswaAktif = User::where('role', 'siswa')
                ->where('branch_id', $branchId)
                ->where('status', 'Aktif')
                ->count();

            // 2. Siswa Selesai Latihan
            $siswaSelesai = User::where('role', 'siswa')
                ->where('branch_id', $branchId)
                ->where('status', 'Selesai Latihan')
                ->count();

            // 3. Siswa Keseluruhan (Aktif + Selesai Latihan, mengabaikan Non-Aktif)
            $siswaKeseluruhan = $siswaAktif + $siswaSelesai;

            // 4. Siswa Bulan Ini (Berdasarkan tanggal pendaftaran bulan ini)
            $siswaBulanIni = User::where('role', 'siswa')
                ->where('branch_id', $branchId)
                ->whereMonth('created_at', $bulanIni)
                ->whereYear('created_at', $tahunIni)
                ->count();

            // 5. Pendaftar Baru Hari Ini (Hanya tagihan 'Pending' pada hari ini saja)
            $pendaftaranBaruHariIni = Pembayaran::where('status', 'Pending')
                ->where('branch_id', $branchId)
                ->whereDate('created_at', Carbon::today())
                ->count();

            $instrukturTersedia = User::where('role', 'instruktur')->where('branch_id', $branchId)->count();

            // LOGIC GRAFIK: Mengambil tren pendaftaran 6 bulan terakhir
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::today()->startOfMonth()->subMonths($i);
                $chartLabels[] = $date->locale('id')->translatedFormat('M Y');
                $chartData[] = User::where('role', 'siswa')
                    ->where('branch_id', $branchId)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
            }

        } else {
            // Jika belum punya cabang (Data Lama / Belum Di-assign Management), set 0 semua
            $siswaAktif = 0;
            $siswaKeseluruhan = 0;
            $siswaBulanIni = 0;
            $pendaftaranBaruHariIni = 0;
            $instrukturTersedia = 0;
        }

        return view('admin.dashboard', compact(
            'user', 'setting', 'siswaAktif', 'siswaKeseluruhan', 'siswaBulanIni', 
            'pendaftaranBaruHariIni', 'instrukturTersedia', 'chartLabels', 'chartData'
        ));
    }
}