<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Pembayaran, User, Setting, Branch};

class ReportController extends Controller
{
    public function index()
    {
        $branches = Branch::all(); 
        $admins = User::where('role', 'admin')->get();
        
        return view('management.laporan.index', compact('branches', 'admins'));
    }

    public function cetak(Request $request)
    {
        $request->validate([
            'jenis_laporan' => 'required|in:keuangan,siswa',
            'tgl_awal'      => 'required|date',
            'tgl_akhir'     => 'required|date|after_or_equal:tgl_awal',
            'branch_id'     => 'nullable',
            'admin_id'      => 'nullable' 
        ]);

        $jenis       = $request->jenis_laporan;
        $tgl_awal    = $request->tgl_awal;
        $tgl_akhir   = $request->tgl_akhir;
        $branch_id   = $request->branch_id;
        $admin_id    = $request->admin_id;
        
        $setting     = Setting::first();
        $nama_cabang = 'Semua Cabang (Global)';
        $nama_admin  = 'Semua Admin';

        if ($branch_id) $nama_cabang = Branch::find($branch_id)->nama_cabang ?? 'Semua Cabang';
        if ($admin_id) {
            $adminObj = User::find($admin_id);
            $nama_admin = $adminObj->nama_admin ?? $adminObj->nama_lengkap ?? 'Semua Admin';
        }

        $branchAdmins = User::where('role', 'admin')->get()->groupBy('branch_id');

        if ($jenis == 'keuangan') {
            $query = Pembayaran::with(['user.package', 'branch', 'approver'])
                               ->where('status', 'Lunas')
                               ->whereBetween('updated_at', [$tgl_awal . ' 00:00:00', $tgl_akhir . ' 23:59:59']);
            
            if ($branch_id) {
                $query->where('branch_id', $branch_id);
            }
            
            if ($admin_id) {
                $adminFilter = User::find($admin_id);
                
                $query->where(function($q) use ($admin_id, $adminFilter) {
                    $q->where('approved_by', $admin_id);
                    
                    if ($adminFilter && $adminFilter->branch_id) {
                        $q->orWhere(function($subQ) use ($adminFilter) {
                            $subQ->whereNull('approved_by')
                                 ->where('branch_id', $adminFilter->branch_id);
                        });
                    }
                });
            }
            
            $data = $query->orderBy('updated_at', 'desc')->get();
            $total = $data->sum('total_tagihan');

            foreach ($data as $item) {
                if ($item->approver) {
                    $item->pic_name = $item->approver->nama_admin ?? $item->approver->nama_lengkap ?? 'Admin Cabang';
                } else {
                    $adminCabang = $branchAdmins->get($item->branch_id)->first() ?? null;
                    $item->pic_name = $adminCabang ? ($adminCabang->nama_admin ?? $adminCabang->nama_lengkap) : 'Pusat / Admin';
                }
            }

        } else {
            $query = User::with(['package', 'branch'])
                         ->where('role', 'siswa')
                         ->whereBetween('created_at', [$tgl_awal . ' 00:00:00', $tgl_akhir . ' 23:59:59']);
            
            if ($branch_id) $query->where('branch_id', $branch_id);
            
            $data = $query->orderBy('created_at', 'desc')->get();
            $total = $data->count();
        }

        return view('management.laporan.cetak', compact('data', 'jenis', 'tgl_awal', 'tgl_akhir', 'total', 'setting', 'nama_cabang', 'nama_admin'));
    }
}