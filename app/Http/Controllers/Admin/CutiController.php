<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cuti;

class CutiController extends Controller
{
    // Menampilkan halaman pengajuan cuti personal Admin
    public function index()
    {
        $user = Auth::user();
        
        // FIX BUG: Tarik HANYA riwayat cuti milik admin yang sedang login
        $cutis = Cuti::where('user_id', $user->id)
                     ->orderBy('created_at', 'desc')
                     ->get();
                     
        return view('admin.cuti.index', compact('user', 'cutis'));
    }

    // FUNGSI: Admin Mengajukan Cuti ke Management
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:500'
        ]);

        Cuti::create([
            'user_id' => Auth::id(), 
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan' => $request->alasan,
            'status' => 'Pending' // Nunggu Management/HRD yang ACC
        ]);

        return back()->with('success', 'Pengajuan cuti Anda berhasil dikirim ke Management!');
    }
}