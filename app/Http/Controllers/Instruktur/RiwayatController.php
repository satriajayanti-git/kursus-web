<?php

namespace App\Http\Controllers\Instruktur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $instruktur = Auth::user();
        $search = $request->search;
        
        // Tarik data jadwal yang sudah 'Selesai' beserta relasi Siswa, Paket, dan Instrukturnya
        $query = Jadwal::with(['user.package', 'instructor'])
                        ->where('branch_id', $instruktur->branch_id)
                        ->where('status', 'Selesai');

        // FITUR SEARCH: Filter berdasarkan Nama Lengkap atau ID Siswa
        if (!empty($search)) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('id_siswa', 'like', '%' . $search . '%');
            });
        }

        $jadwals = $query->get();

        // KELOMPOKKAN & URUTKAN: Group by user_id, lalu urutkan grup agar siswa 
        // dengan sesi latihan paling baru berada di urutan paling atas.
        $groupedSiswa = $jadwals->groupBy('user_id')->sortByDesc(function ($sesiSiswa) {
            return $sesiSiswa->max('tanggal'); 
        });

        return view('instruktur.riwayat.index', compact('groupedSiswa', 'search'));
    }
}