<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\{Pembayaran, User, Jadwal};

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->branch_id) {
            return redirect('/admin/dashboard')->with('error', 'Akun Anda belum ditugaskan ke cabang manapun!');
        }

        $bulan = $request->bulan ?? date('Y-m');
        $status_bayar = $request->status_bayar ?? '';
        $search = $request->search;

        $query = Pembayaran::with(['user', 'package'])
            ->where('branch_id', $user->branch_id);

        $tahun = date('Y', strtotime($bulan));
        $bulan_angka = date('m', strtotime($bulan));
        $query->whereYear('created_at', $tahun)->whereMonth('created_at', $bulan_angka);

        if (!empty($status_bayar) && $status_bayar !== 'Semua') {
            $query->where('status', $status_bayar);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($qUser) use ($search) {
                    $qUser->where('nama_lengkap', 'like', '%' . $search . '%')
                        ->orWhere('username', 'like', '%' . $search . '%')
                        ->orWhere('id_siswa', 'like', '%' . $search . '%');
                });
            });
        }

        $pembayarans = $query->orderBy('created_at', 'desc')->get();

        $total_omset = Pembayaran::where('branch_id', $user->branch_id)
            ->where('status', 'Lunas')
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan_angka)
            ->sum('total_tagihan');

        $siswas = User::where('role', 'siswa')->where('branch_id', $user->branch_id)->get();

        return view('admin.keuangan.index', compact('pembayarans', 'siswas', 'total_omset', 'search', 'bulan', 'status_bayar'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Lunas,Ditolak',
            'penolakan' => 'nullable|string',
            'bukti_bayar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'metode_pembayaran' => 'nullable|string',
            'jenis_bayar' => 'nullable|in:full,dp',
            'nominal_dp' => 'nullable|numeric|min:50000'
        ]);

        $pembayaran = Pembayaran::findOrFail($id);

        $data = [
            'status' => $request->status,
            // 🔥 REVISI AUDIT KEUANGAN: Catat ID admin spesifik yang memproses transaksi
            'approved_by' => Auth::id(), 
        ];

        if ($request->status == 'Ditolak') {
            $data['penolakan'] = $request->penolakan;
        } else {
            $data['penolakan'] = null;
        }

        if ($request->hasFile('bukti_bayar')) {
            if ($pembayaran->bukti_bayar && Storage::disk('public')->exists('uploads/bukti/' . $pembayaran->bukti_bayar)) {
                Storage::disk('public')->delete('uploads/bukti/' . $pembayaran->bukti_bayar);
            }

            $file = $request->file('bukti_bayar');
            $namaFile = time() . '_admin_upload_' . str_replace(' ', '_', $file->getClientOriginalName());

            $file->storeAs('uploads/bukti', $namaFile, 'public');

            $data['bukti_bayar'] = $namaFile;
        }

        $keteranganUpdate = $pembayaran->keterangan;
        if ($request->filled('metode_pembayaran')) {
            $keteranganUpdate = preg_replace('/\s*\(Via(?: Bank)?:.*?\)/', '', $keteranganUpdate);
            $keteranganUpdate = $keteranganUpdate . ' (Via: ' . $request->metode_pembayaran . ')';
        }

        // LOGIC SPLIT INVOICE (VIA VERIFIKASI ADMIN)
        if ($request->status == 'Lunas' && $request->jenis_bayar === 'dp' && $pembayaran->jenis_tagihan === 'Paket Utama' && $request->nominal_dp) {
            
            $sudahAdaPelunasan = Pembayaran::where('user_id', $pembayaran->user_id)
                ->where('keterangan', 'Pelunasan Sisa Pembayaran Paket Utama')
                ->exists();

            if (!$sudahAdaPelunasan) {
                $nominalDp = $request->nominal_dp;
                $sisaTagihan = $pembayaran->total_tagihan - $nominalDp;

                if ($sisaTagihan > 0) {
                    $data['total_tagihan'] = $nominalDp; 
                    $keteranganUpdate = $keteranganUpdate . ' [Lunas DP Sebagian]';

                    Pembayaran::create([
                        'user_id'       => $pembayaran->user_id,
                        'branch_id'     => $pembayaran->branch_id,
                        'total_tagihan' => $sisaTagihan,
                        'jenis_tagihan' => 'Tambahan',
                        'keterangan'    => 'Pelunasan Sisa Pembayaran Paket Utama',
                        'status'        => 'Pending',
                        // approved_by dikosongkan dulu karena invoice ini belum dibayar
                    ]);
                }
            }
        }
        $data['keterangan'] = $keteranganUpdate;

        // Eksekusi Update ke Database
        $pembayaran->update($data);

        // LOGIC AKTIVASI SISWA
        if ($pembayaran->jenis_tagihan === 'Paket Utama') {
            $siswa = User::find($pembayaran->user_id);

            if ($siswa) {
                if ($request->status === 'Lunas') {
                    $siswa->update(['status' => 'Aktif']);
                } else {
                    $siswa->update(['status' => 'Non-Aktif']);
                }
            }
        }

        return back()->with('success', 'Keputusan validasi pembayaran berhasil disimpan!');
    }

    public function storeTambahan(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_tagihan' => 'required|numeric',
            'keterangan' => 'required|string',
        ]);

        Pembayaran::create([
            'user_id' => $request->user_id,
            'branch_id' => Auth::user()->branch_id,
            'total_tagihan' => $request->total_tagihan,
            'jenis_tagihan' => 'Tambahan',
            'keterangan' => $request->keterangan,
            'status' => 'Pending',
            'approved_by' => Auth::id(), // Opsional: merekam admin pembuat tagihan
        ]);

        return back()->with('success', 'Tagihan tambahan berhasil dibuat!');
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'tgl_mulai' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_mulai'
        ]);

        $user = Auth::user();

        $data = Pembayaran::with(['user', 'package'])
            ->where('branch_id', $user->branch_id)
            ->where('status', 'Lunas')
            ->whereBetween('updated_at', [$request->tgl_mulai . ' 00:00:00', $request->tgl_akhir . ' 23:59:59'])
            ->orderBy('updated_at', 'asc')
            ->get();

        return view('admin.keuangan.report', compact('data'));
    }
}