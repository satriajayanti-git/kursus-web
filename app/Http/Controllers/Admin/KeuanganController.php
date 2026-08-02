<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // 🔥 Mengubah dari File menjadi Storage
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

    // 🔥 LOGIC DIPERBARUI: Menggabungkan Update Status, Upload Bukti, & Metode Bayar
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Lunas,Ditolak',
            'penolakan' => 'nullable|string',
            'bukti_bayar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'metode_pembayaran' => 'nullable|string'
        ]);

        $pembayaran = Pembayaran::findOrFail($id);

        $data = [
            'status' => $request->status,
        ];

        // 1. Eksekusi Penolakan
        if ($request->status == 'Ditolak') {
            $data['penolakan'] = $request->penolakan;
        } else {
            $data['penolakan'] = null;
        }

        // 2. Eksekusi Upload Bukti (Jika Admin melampirkan file)
        if ($request->hasFile('bukti_bayar')) {
            // 🔥 PERBAIKAN: Gunakan Storage facade untuk mengecek dan menghapus bukti lama
            if ($pembayaran->bukti_bayar && Storage::disk('public')->exists('uploads/bukti/' . $pembayaran->bukti_bayar)) {
                Storage::disk('public')->delete('uploads/bukti/' . $pembayaran->bukti_bayar);
            }

            $file = $request->file('bukti_bayar');
            $namaFile = time() . '_admin_upload_' . str_replace(' ', '_', $file->getClientOriginalName());

            // 🔥 PERBAIKAN: Gunakan storeAs dengan parameter 'public' agar masuk ke storage/app/public/uploads/bukti
            $file->storeAs('uploads/bukti', $namaFile, 'public');

            $data['bukti_bayar'] = $namaFile;
        }

        // 3. Eksekusi Injeksi Keterangan Metode Pembayaran (Trik Tanpa Rombak DB)
        if ($request->filled('metode_pembayaran')) {
            $keteranganUpdate = $pembayaran->keterangan;
            // Bersihkan format (Via ...) yang lama agar tidak ada teks duplikat di database
            $keteranganUpdate = preg_replace('/\s*\(Via(?: Bank)?:.*?\)/', '', $keteranganUpdate);

            // Masukkan format metode baru
            $data['keterangan'] = $keteranganUpdate . ' (Via: ' . $request->metode_pembayaran . ')';
        }

        // 4. Simpan Perubahan ke Database
        $pembayaran->update($data);

        // 5. OTOMATISASI SINKRONISASI: Akun siswa otomatis 'Aktif' jika Paket Utama disetujui Lunas
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

        return back()->with('success', 'Keputusan validasi dan bukti pembayaran berhasil disimpan!');
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