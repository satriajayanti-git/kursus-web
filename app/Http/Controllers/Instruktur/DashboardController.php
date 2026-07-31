<?php

namespace App\Http\Controllers\Instruktur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{Jadwal, Unit, LaporanUnit}; // 🔥 Tambahkan Unit & LaporanUnit
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $branchId = $user->branch_id; 
        $hariIni = Carbon::today()->toDateString();

        // 1. Tarik Data Jadwal
        $jadwals = Jadwal::with(['user.package', 'branch', 'unit'])
            ->where('instructor_id', $user->id)
            ->where('branch_id', $branchId)
            ->where(function($q) use ($hariIni) {
                $q->where('tanggal', '>=', $hariIni)->whereIn('status', ['Pending', 'Disetujui']);
                $q->orWhere(function($subQ) use ($hariIni) {
                    $subQ->where('tanggal', '<', $hariIni)->where('status', 'Disetujui');
                });
            })
            ->orderBy('tanggal', 'asc')
            ->get();

        foreach ($jadwals as $j) {
            $j->pertemuan_ke = Jadwal::where('user_id', $j->user_id)
                ->whereIn('status', ['Disetujui', 'Selesai'])
                ->where('tanggal', '<=', $j->tanggal)
                ->count();
        }

        // 🔥 LOGIC ERP (PELAPORAN UNIT): Kumpulkan mobil apa saja yang berhak dia laporkan
        $unitPegangan = Unit::where('instruktur_id', $user->id)->first();
        $unitDariJadwal = $jadwals->whereNotNull('unit_id')->pluck('unit')->unique('id');
        
        // Gabungkan mobil pegangan dan mobil jadwal (hindari duplikat)
        $unitsAvailable = collect([$unitPegangan])->merge($unitDariJadwal)->filter()->unique('id');

        // Tarik 5 riwayat laporan terakhir milik instruktur ini untuk ditampilkan
        $laporans = LaporanUnit::with('unit')
            ->where('instruktur_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('instruktur.dashboard', compact('user', 'jadwals', 'unitsAvailable', 'laporans'));
    }

    public function simpanEvaluasi(Request $request, $id)
    {
        $request->validate(['catatan_evaluasi' => 'required|string']);
        
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->update([
            'catatan_evaluasi' => $request->catatan_evaluasi,
            'status' => 'Selesai'
        ]);

        return back()->with('success', 'Evaluasi disimpan! Jadwal masuk ke Arsip.');
    }

    // 🔥 METHOD BARU: Menyimpan Laporan Kendala Unit
    public function storeLaporanUnit(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'tingkat_kendala' => 'required|in:Ringan,Berat',
            'deskripsi' => 'required|string|max:500'
        ]);

        LaporanUnit::create([
            'unit_id' => $request->unit_id,
            'instruktur_id' => Auth::id(),
            'tingkat_kendala' => $request->tingkat_kendala,
            'deskripsi' => $request->deskripsi,
            'status_laporan' => 'Menunggu'
        ]);

        $pesan = $request->tingkat_kendala == 'Berat' 
            ? 'Peringatan Darurat terkirim! Management akan segera meninjau unit ini.' 
            : 'Laporan kendala ringan berhasil dikirim sebagai catatan Management.';

        return back()->with('success', $pesan);
    }
}