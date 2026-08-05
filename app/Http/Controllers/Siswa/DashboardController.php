<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{Pembayaran, Jadwal, Setting, User};
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::with(['package', 'branch'])->find(Auth::id());
        $setting = Setting::first();
        
        $tagihanUtama = Pembayaran::where('user_id', $user->id)
                                    ->where('jenis_tagihan', 'Paket Utama')->first();
        
        $tagihanTambahan = Pembayaran::where('user_id', $user->id)
                                    ->where('jenis_tagihan', 'Tambahan')
                                    ->orderBy('created_at', 'desc')->get();
        
        $mySchedules = Jadwal::with('instructor')->where('user_id', $user->id)->orderBy('tanggal', 'desc')->get();
        $riwayatPembayaran = Pembayaran::where('user_id', $user->id)->orderBy('updated_at', 'desc')->get();
        
        $totalSesi = $user->package->pertemuan ?? 0;

        // 🔥 LOGIC BARU: Promo Manual 15x dapat tambahan 1 sesi
        if (strtolower($user->package->transmisi ?? '') == 'manual' && $totalSesi == 15) {
            $totalSesi += 1;
        }

        $sesiTerpakai = Jadwal::where('user_id', $user->id)->where('status', '!=', 'Batal')->count();
        $sisaSesi = $totalSesi - $sesiTerpakai;

        
        return view('siswa.dashboard', compact('user', 'tagihanUtama', 'tagihanTambahan', 'setting', 'mySchedules', 'sisaSesi', 'riwayatPembayaran'));
    }

    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|max:2048',
            'metode_pembayaran' => 'required|string' 
        ]);
        
        $user = Auth::user();
        $tagihan = Pembayaran::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        
        if ($request->hasFile('bukti_bayar')) {
            if ($tagihan->bukti_bayar && Storage::disk('public')->exists('uploads/bukti/' . $tagihan->bukti_bayar)) {
                Storage::disk('public')->delete('uploads/bukti/' . $tagihan->bukti_bayar);
            }
            
            $file = $request->file('bukti_bayar');
            $namaFile = time() . '_bukti_' . $id . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            
            $file->storeAs('uploads/bukti', $namaFile, 'public');
            
            $keteranganUpdate = $tagihan->keterangan;
            if (!str_contains($keteranganUpdate, 'Via Bank:')) {
                $keteranganUpdate = $keteranganUpdate . ' (Via Bank: ' . $request->metode_pembayaran . ')';
            }

            $tagihan->update([
                'bukti_bayar' => $namaFile, 
                'status' => 'Pending',
                'penolakan' => null,
                'keterangan' => $keteranganUpdate 
            ]);
        }
        
        return back()->with('success', 'Bukti pembayaran berhasil diunggah! Mohon lakukan konfirmasi ke Admin.');
    }

    public function simpanJadwal(Request $request)
    {
        $user = User::with('package')->find(Auth::id());
        $tagihanUtama = Pembayaran::where('user_id', $user->id)->where('jenis_tagihan', 'Paket Utama')->first();
        
        if ($user->status !== 'Aktif' || !$tagihanUtama || $tagihanUtama->status !== 'Lunas') {
            return back()->with('error', 'Selesaikan pembayaran paket utama terlebih dahulu untuk mengaktifkan fitur jadwal.');
        }
        
        $max = $user->package->pertemuan ?? 0;

        // 🔥 LOGIC BARU: Promo Manual 15x dapat tambahan 1 sesi
        if (strtolower($user->package->transmisi ?? '') == 'manual' && $max == 15) {
            $max += 1;
        }

        $count = Jadwal::where('user_id', $user->id)->where('status', '!=', 'Batal')->count();
        if ($count >= $max) {
            return back()->with('error', "Kuota pertemuan paket Anda sudah habis.");
        }
        
        $selfDouble = Jadwal::where('user_id', $user->id)
            ->where('tanggal', $request->tanggal)
            ->where('jam_mulai', $request->jam_mulai)
            ->whereIn('status', ['Pending', 'Disetujui'])
            ->exists();
        if($selfDouble) return back()->with('error', 'Anda sudah memiliki pengajuan jadwal di waktu yang sama.'); 

        $jamMulai = (int) substr($request->jam_mulai, 0, 2);
        
        // 🔥 LOGIC IZIN JAM 12:00 SUDAH DIBUKA (Pengecekan penolakan jam 12 telah dihapus)

        $kategoriPaket = $user->package->kategori ?? 'Reguler';
        $is_extra = 0; 
        $status_bayar = 'Tidak Ada';

        if ($kategoriPaket == 'Reguler' && $jamMulai >= 16) {
            if ($request->acc_extra_charge != '1') {
                return back()->with('error', 'Persetujuan biaya tambahan diperlukan untuk mengambil jadwal di luar jam reguler.');
            }
            $is_extra = 1; 
            $status_bayar = 'Belum Lunas';
        }

        $transmisiSiswa = $user->package->transmisi ?? 'Manual';
        $instructors = User::where('role', 'instruktur')->whereIn('kategori_transmisi', [$transmisiSiswa, 'Manual & Matic'])->get();
        $availableInstructors = 0;
        
        foreach ($instructors as $ins) {
            if (!$ins->isCuti($request->tanggal) && (!$ins->isSibuk($request->tanggal, $request->jam_mulai))) {
                $availableInstructors++;
            }
        }
        
        if ($availableInstructors == 0) {
            return back()->with('error', "Maaf, tidak ada instruktur spesialis {$transmisiSiswa} yang tersedia pada jam tersebut.");
        }
        
        $jadwal = Jadwal::create([
            'user_id' => $user->id,
            'branch_id' => $user->branch_id,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'status' => 'Pending',
            'is_extra_charge' => $is_extra,
            'status_pembayaran_extra' => $status_bayar
        ]);

        if ($is_extra == 1) {
            Pembayaran::create([
                'user_id' => $user->id,
                'branch_id' => $user->branch_id,
                'jenis_tagihan' => 'Tambahan',
                'keterangan' => 'Biaya tambahan sesi jam non-reguler (Pukul ' . $request->jam_mulai . ' WIB)',
                'total_tagihan' => 20000, 
                'status' => 'Pending'
            ]);
        }

        $pesan = $is_extra 
            ? 'Jadwal berhasil diajukan! Invoice biaya tambahan sebesar Rp 20.000 telah terbit otomatis di dashboard Anda.' 
            : 'Jadwal latihan berhasil diajukan!';

        return back()->with('success', $pesan);
    }

    public function simpanFeedback(Request $request, $id)
    {
        $jadwal = Jadwal::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $request->validate(['rating' => 'required|integer|min:1|max:5', 'feedback_siswa' => 'required|string']);
        $jadwal->update(['rating' => $request->rating, 'feedback_siswa' => $request->feedback_siswa]);
        return back()->with('success', 'Penilaian latihan Anda berhasil disimpan. Terima kasih!');
    }
}