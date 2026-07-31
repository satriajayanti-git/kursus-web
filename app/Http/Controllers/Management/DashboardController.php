<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\{User, Branch, Pembayaran, Setting, Unit}; // Tambah Unit
use Carbon\Carbon; // Tambah Carbon untuk hitung H-14

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
        // Kita cuma tarik data yang jatuh temponya <= 14 hari lagi
        $warningDate = Carbon::now()->addDays(14);
        
        $pajakAlerts = Unit::whereNotNull('tgl_jatuh_tempo_pajak')
                           ->where('tgl_jatuh_tempo_pajak', '<=', $warningDate)
                           ->get();
                           
        $kirAlerts = Unit::whereNotNull('tgl_jatuh_tempo_kir')
                         ->where('tgl_jatuh_tempo_kir', '<=', $warningDate)
                         ->get();

        // Kita kirim data alert ke view dashboard
        $reminders = [
            'pajak' => $pajakAlerts,
            'kir' => $kirAlerts
        ];

        return view('management.dashboard', compact(
            'user', 'setting', 'totalCabang', 'totalSiswa', 'totalInstruktur', 'totalPendapatan',
            'chartBulan', 'chartPendapatan', 'reminders'
        ));
    }
}