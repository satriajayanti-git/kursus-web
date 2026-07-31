<?php

namespace App\Http\Controllers\Instruktur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cuti;

class CutiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Tarik riwayat cuti khusus instruktur yang login
        $cutis = Cuti::where('user_id', $user->id)
                     ->orderBy('created_at', 'desc')
                     ->get();
                     
        return view('instruktur.cuti.index', compact('user', 'cutis'));
    }

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
            'status' => 'Pending'
        ]);

        return back()->with('success', 'Pengajuan cuti berhasil dikirim ke Management!');
    }
}