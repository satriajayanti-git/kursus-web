<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{User, Pembayaran, Setting};
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function cetak(Request $request)
    {
        $request->validate([
            'jenis_laporan' => 'required|in:keuangan,siswa',
            'tgl_awal'      => 'required|date',
            'tgl_akhir'     => 'required|date|after_or_equal:tgl_awal',
        ]);

        $jenis     = $request->jenis_laporan;
        $tgl_awal  = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $setting   = Setting::first();
        
        $admin = Auth::user(); // 🔥 Mendapatkan sesi Admin yang sedang login

        if ($jenis == 'keuangan') {
            // 🔥 Ditambahkan filter 'branch_id' agar admin tidak bisa mengintip cabang lain
            // 🔥 Ditambahkan pemanggilan relasi 'approver' untuk menarik nama admin pemvalidasi
            $data = Pembayaran::with(['user.package', 'approver'])
                ->where('branch_id', $admin->branch_id) 
                ->where('status', 'Lunas')
                ->whereBetween('updated_at', [$tgl_awal . ' 00:00:00', $tgl_akhir . ' 23:59:59'])
                ->orderBy('updated_at', 'asc')
                ->get();
            
            $total = $data->sum('total_tagihan'); 
        } else {
            // 🔥 Ditambahkan filter 'branch_id' agar laporan akurat per cabang
            // 🔥 Ditambahkan pemanggilan relasi 'registrar' untuk menarik nama admin pendaftar
            $data = User::with(['package', 'registrar'])
                ->where('role', 'siswa')
                ->where('branch_id', $admin->branch_id) 
                ->whereBetween('created_at', [$tgl_awal . ' 00:00:00', $tgl_akhir . ' 23:59:59'])
                ->orderBy('created_at', 'asc')
                ->get();
            
            $total = $data->count(); 
        }

        return view('admin.laporan.cetak', compact('data', 'jenis', 'tgl_awal', 'tgl_akhir', 'total', 'setting', 'admin'));
    }
}