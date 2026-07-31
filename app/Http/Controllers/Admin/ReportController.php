<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, Pembayaran, Setting};
use Carbon\Carbon;

class ReportController extends Controller
{
    // Menampilkan halaman form laporan
    public function index()
    {
        return view('admin.laporan.index');
    }

    // Memproses data dan menampilkan halaman cetak
    public function cetak(Request $request)
    {
        $request->validate([
            'jenis_laporan' => 'required|in:keuangan,siswa',
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
        ]);

        $jenis = $request->jenis_laporan;
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $setting = Setting::first();

        if ($jenis == 'keuangan') {
            // LAPORAN KEUANGAN: Ambil data pembayaran yang sudah 'Lunas' di rentang tanggal tersebut
            $data = Pembayaran::with(['user.package'])
                ->where('status', 'Lunas')
                ->whereBetween('updated_at', [$tgl_awal . ' 00:00:00', $tgl_akhir . ' 23:59:59'])
                ->orderBy('updated_at', 'asc')
                ->get();
            
            $total = $data->sum('total_tagihan'); // Hitung total pendapatan
        } else {
            // LAPORAN PENDAFTARAN SISWA: Ambil data user role 'siswa' beserta relasi package-nya
            $data = User::with('package')
                ->where('role', 'siswa')
                ->whereBetween('created_at', [$tgl_awal . ' 00:00:00', $tgl_akhir . ' 23:59:59'])
                ->orderBy('created_at', 'asc')
                ->get();
            
            $total = $data->count(); // Hitung total siswa
        }

        return view('admin.laporan.cetak', compact('data', 'jenis', 'tgl_awal', 'tgl_akhir', 'total', 'setting'));
    }
}