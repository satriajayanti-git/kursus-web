<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; 
use App\Models\{User, Branch, Pembayaran, Setting, Unit}; 
use Carbon\Carbon; 

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $setting = Setting::first();

        // Mengambil filter tahun, default adalah tahun berjalan
        $tahun = $request->get('tahun', date('Y'));

        // Statistik Global All-Time
        $totalCabang = Branch::count();
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalInstruktur = User::where('role', 'instruktur')->count();
        $totalPendapatan = Pembayaran::where('status', 'Lunas')->sum('total_tagihan');

        // LOGIC GRAFIK PENDAPATAN BULANAN GLOBAL (Sesuai Tahun Filter)
        $pendapatanBulanan = Pembayaran::select(
            DB::raw('MONTH(updated_at) as bulan'),
            DB::raw('SUM(total_tagihan) as total')
        )
        ->where('status', 'Lunas')
        ->whereYear('updated_at', $tahun)
        ->groupBy('bulan')
        ->get();

        $chartBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $chartPendapatan = array_fill(0, 12, 0);

        foreach ($pendapatanBulanan as $data) {
            $chartPendapatan[$data->bulan - 1] = (int) $data->total;
        }

        // LOGIC STATISTIK & GRAFIK DETAIL PER CABANG
        $branches = Branch::all();
        $branchStats = [];

        foreach ($branches as $branch) {
            // Data Tahunan (Sesuai Filter)
            $siswaYear = User::where('role', 'siswa')
                ->where('branch_id', $branch->id)
                ->whereYear('created_at', $tahun)
                ->count();

            $revenueYear = Pembayaran::where('branch_id', $branch->id)
                ->where('status', 'Lunas')
                ->whereYear('updated_at', $tahun)
                ->sum('total_tagihan');

            // Data Keseluruhan (All-Time)
            $siswaAllTime = User::where('role', 'siswa')
                ->where('branch_id', $branch->id)
                ->count();

            $revenueAllTime = Pembayaran::where('branch_id', $branch->id)
                ->where('status', 'Lunas')
                ->sum('total_tagihan');

            // 🔥 LOGIC BARU: Data Pendaftaran Bulanan Per Cabang (Sesuai Filter Tahun)
            $siswaBulanan = User::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(id) as total')
            )
            ->where('role', 'siswa')
            ->where('branch_id', $branch->id)
            ->whereYear('created_at', $tahun)
            ->groupBy('bulan')
            ->get();

            $chartSiswaBulanan = array_fill(0, 12, 0);
            foreach ($siswaBulanan as $data) {
                $chartSiswaBulanan[$data->bulan - 1] = (int) $data->total;
            }

            // Data Peminatan Transmisi per Cabang (Sesuai Filter Tahun)
            $transmisiManual = User::where('role', 'siswa')
                ->where('branch_id', $branch->id)
                ->whereYear('created_at', $tahun)
                ->whereHas('package', function($q) {
                    $q->where('transmisi', 'Manual');
                })->count();

            $transmisiMatic = User::where('role', 'siswa')
                ->where('branch_id', $branch->id)
                ->whereYear('created_at', $tahun)
                ->whereHas('package', function($q) {
                    $q->where('transmisi', 'Matic');
                })->count();

            // Memasukkan semua rincian ke dalam array per cabang
            $branchStats[] = [
                'nama'          => $branch->nama_cabang,
                'siswa_year'    => $siswaYear,
                'revenue_year'  => $revenueYear,
                'siswa_all'     => $siswaAllTime,
                'revenue_all'   => $revenueAllTime,
                'siswa_bulanan' => $chartSiswaBulanan, // Array data pendaftar per bulan
                'manual_count'  => $transmisiManual,
                'matic_count'   => $transmisiMatic
            ];
        }

        // LOGIC REMINDER PAJAK & KIR (H-14)
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
            'chartBulan', 'chartPendapatan', 'reminders', 'tahun', 'branchStats'
        ));
    }

    public function showPasswordForm()
    {
        $setting = Setting::first();
        return view('management.password', compact('setting'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:6|confirmed', 
        ], [
            'password_baru.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password_baru.min'       => 'Password baru minimal 6 karakter.'
        ]);

        $user = User::find(Auth::id());

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai!');
        }

        $user->update([
            'password' => Hash::make($request->password_baru)
        ]);

        return back()->with('success', 'Kata sandi berhasil diperbarui! Silakan gunakan kata sandi baru untuk login selanjutnya.');
    }
}