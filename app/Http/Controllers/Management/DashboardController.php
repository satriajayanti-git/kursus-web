<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // Tambah Request
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // Tambah Hash untuk validasi password
use App\Models\{User, Branch, Pembayaran, Setting, Unit}; 
use Carbon\Carbon; 

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $setting = Setting::first();

        // Statistik Global (Tetap Original tanpa perubahan)
        $totalCabang = Branch::count();
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalInstruktur = User::where('role', 'instruktur')->count();
        $totalPendapatan = Pembayaran::where('status', 'Lunas')->sum('total_tagihan');

        // LOGIC GRAFIK (Tetap Original tanpa perubahan)
        $pendapatanBulanan = Pembayaran::select(
            DB::raw('MONTH(updated_at) as bulan'),
            DB::raw('SUM(total_tagihan) as total')
        )
        ->where('status', 'Lunas')
        ->whereYear('updated_at', date('Y'))
        ->groupBy('bulan')
        ->get();

        $chartBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $chartPendapatan = array_fill(0, 12, 0);

        foreach ($pendapatanBulanan as $data) {
            $chartPendapatan[$data->bulan - 1] = (int) $data->total;
        }

        // 🔥 LOGIC REMINDER PAJAK & KIR (H-14)
        $warningDate = Carbon::now()->addDays(14);
        
        $pajakAlerts = Unit::whereNotNull('tgl_jatuh_tempo_pajak')
                           ->where('tgl_jatuh_tempo_pajak', '<=', $warningDate)
                           ->get();
                           
        $kirAlerts = Unit::whereNotNull('tgl_jatuh_tempo_kir')
                         ->where('tgl_jatuh_tempo_kir', '<=', $warningDate)
                         ->get();

        $reminders = [
            'pajak' => $pajakAlerts,
            'kir' => $kirAlerts
        ];

        return view('management.dashboard', compact(
            'user', 'setting', 'totalCabang', 'totalSiswa', 'totalInstruktur', 'totalPendapatan',
            'chartBulan', 'chartPendapatan', 'reminders'
        ));
    }

    // 🔥 LOGIC BARU: Tampilkan halaman Ubah Password
    public function showPasswordForm()
    {
        $setting = Setting::first();
        return view('management.password', compact('setting'));
    }

    // 🔥 LOGIC BARU: Proses Ubah Password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:6|confirmed', 
        ], [
            'password_baru.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password_baru.min' => 'Password baru minimal 6 karakter.'
        ]);

        $user = User::find(Auth::id());

        // Cek kecocokan password lama
        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai!');
        }

        // Update ke database
        $user->update([
            'password' => Hash::make($request->password_baru)
        ]);

        return back()->with('success', 'Kata sandi berhasil diperbarui! Silakan gunakan kata sandi baru untuk login selanjutnya.');
    }
}