<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Cuti, User};

class CutiController extends Controller
{
    public function index()
    {
        // Tarik semua data cuti, urutkan yang "Pending" di atas, lalu berdasarkan tanggal terbaru
        $cutis = Cuti::with(['user.branch'])
                     ->orderByRaw("FIELD(status, 'Pending') DESC")
                     ->orderBy('created_at', 'desc')
                     ->get();
                     
        return view('management.cuti.index', compact('cutis'));
    }

    public function updateStatus(Request $request, $id)
    {
        $cuti = Cuti::findOrFail($id);
        
        $request->validate(['status' => 'required|in:Pending,Disetujui,Ditolak']);

        $cuti->update(['status' => $request->status]);

        // LOGIC PERINGATAN BACKUP ADMIN
        $pesan = 'Status pengajuan cuti berhasil diperbarui!';
        
        if ($request->status == 'Disetujui' && $cuti->user->role == 'admin') {
            $cabang = $cuti->user->branch->nama_cabang ?? 'Pusat';
            $pesan = "Cuti Admin disetujui! JANGAN LUPA: Segera tugaskan Admin pengganti untuk cabang {$cabang} di menu Master Penugasan.";
        }

        return back()->with('success', $pesan);
    }
}