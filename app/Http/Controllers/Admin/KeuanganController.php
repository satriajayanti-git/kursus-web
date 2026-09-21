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

        $tahun = date('Y', strtotime($bulan));
        $bulan_angka = date('m', strtotime($bulan));

        // Hitung total omset berdasarkan tanggal mutasi (updated_at)
        $total_omset = Pembayaran::where('branch_id', $user->branch_id)
            ->where('status', 'Lunas')
            ->whereYear('updated_at', $tahun)
            ->whereMonth('updated_at', $bulan_angka)
            ->sum('total_tagihan');

        // Data Siswa untuk dropdown Modal Tambah Tagihan
        $siswas = User::where('role', 'siswa')->where('branch_id', $user->branch_id)->get();

        // 🔥 LOGIC PERBAIKAN: Query dari sisi tabel Pembayaran (menghindari error Model User)
        $query = Pembayaran::with(['user.package'])
            ->where('branch_id', $user->branch_id)
            ->whereYear('updated_at', $tahun)
            ->whereMonth('updated_at', $bulan_angka)
            ->whereHas('user', function ($q) {
                $q->where('role', 'siswa');
            });

        if (!empty($status_bayar) && $status_bayar !== 'Semua') {
            $query->where('status', $status_bayar);
        }

        if (!empty($search)) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('username', 'like', '%' . $search . '%')
                  ->orWhere('id_siswa', 'like', '%' . $search . '%');
            });
        }

        $pembayaransData = $query->orderBy('updated_at', 'desc')->get();

        // Mengelompokkan riwayat pembayaran per siswa ke dalam Collection baru
        $siswasMutasi = collect();
        foreach ($pembayaransData->groupBy('user_id') as $userId => $payments) {
            $siswa = $payments->first()->user;
            if ($siswa) {
                // Menanamkan relasi secara paksa agar file index.blade.php bisa membacanya tanpa error
                $siswa->setRelation('pembayarans', $payments);
                $siswasMutasi->push($siswa);
            }
        }

        return view('admin.keuangan.index', compact('siswasMutasi', 'siswas', 'total_omset', 'search', 'bulan', 'status_bayar'));
    }

    public function updateStatusBulk(Request $request)
    {
        $request->validate([
            'tagihan_ids' => 'required|array',
            'status'      => 'required|in:Pending,Lunas,Ditolak',
            'penolakan'   => 'nullable|string',
        ]);

        foreach ($request->tagihan_ids as $id) {
            $pembayaran = Pembayaran::find($id);
            if ($pembayaran) {
                $pembayaran->update([
                    'status'      => $request->status,
                    'penolakan'   => $request->status == 'Ditolak' ? $request->penolakan : null,
                    'approved_by' => Auth::id()
                ]);

                if ($pembayaran->jenis_tagihan === 'Paket Utama') {
                    $siswa = User::find($pembayaran->user_id);
                    if ($siswa) {
                        $siswa->update(['status' => $request->status === 'Lunas' ? 'Aktif' : 'Non-Aktif']);
                    }
                }
            }
        }

        return back()->with('success', 'Verifikasi Pembayaran Gabungan berhasil diproses!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'            => 'required|in:Pending,Lunas,Ditolak',
            'penolakan'         => 'nullable|string',
            'bukti_bayar'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'metode_pembayaran' => 'nullable|string',
            'jenis_bayar'       => 'nullable|in:full,dp',
            'nominal_dp'        => 'nullable|numeric|min:50000'
        ]);

        $pembayaran = Pembayaran::findOrFail($id);

        $data = [
            'status'      => $request->status,
            'approved_by' => Auth::id(), 
        ];

        $data['penolakan'] = $request->status == 'Ditolak' ? $request->penolakan : null;

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
                    ]);
                }
            }
        }
        
        $data['keterangan'] = $keteranganUpdate;
        $pembayaran->update($data);

        if ($pembayaran->jenis_tagihan === 'Paket Utama') {
            $siswa = User::find($pembayaran->user_id);
            if ($siswa) {
                $siswa->update(['status' => $request->status === 'Lunas' ? 'Aktif' : 'Non-Aktif']);
            }
        }

        return back()->with('success', 'Keputusan validasi pembayaran berhasil disimpan!');
    }

    public function storeTambahan(Request $request)
    {
        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'total_tagihan' => 'required|numeric',
            'keterangan'    => 'required|string',
        ]);

        Pembayaran::create([
            'user_id'       => $request->user_id,
            'branch_id'     => Auth::user()->branch_id,
            'total_tagihan' => $request->total_tagihan,
            'jenis_tagihan' => 'Tambahan',
            'keterangan'    => $request->keterangan,
            'status'        => 'Pending',
            'approved_by'   => Auth::id(),
        ]);

        return back()->with('success', 'Tagihan tambahan berhasil dibuat!');
    }
}