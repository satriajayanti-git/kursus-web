<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{Jadwal, User, Cuti, Unit, Pembayaran}; 

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->branch_id) {
            return redirect('/admin/dashboard')->with('error', 'Akun Anda belum ditugaskan ke cabang manapun!');
        }

        $query = Jadwal::with(['user.package', 'instructor', 'unit']) 
            ->where('branch_id', $user->branch_id);

        $status = $request->get('status', 'Pending');
        
        if ($status !== 'all') {
            if ($status == 'Pending') {
                $query->where('status', 'Pending')->whereNull('instructor_id');
            } elseif ($status == 'Disetujui') {
                $query->where(function($q) {
                    $q->where('status', 'Disetujui')
                      ->orWhere(function($sq) {
                          $sq->where('status', 'Pending')
                             ->whereNotNull('instructor_id');
                      });
                });
            } elseif ($status == 'Dibatalkan' || $status == 'Batal') {
                $query->where('status', 'Batal');
            } else {
                $query->where('status', $status);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($qUser) use ($search) {
                    $qUser->where('nama_lengkap', 'like', '%' . $search . '%');
                })
                ->orWhereHas('instructor', function($qInstructor) use ($search) {
                    $qInstructor->where('nama_lengkap', 'like', '%' . $search . '%');
                });
            });
        }

        $jadwals = $query->orderBy('tanggal', 'desc')->orderBy('jam_mulai', 'asc')->get();
        
        $instructors = User::where('role', 'instruktur')
            ->where('branch_id', $user->branch_id)
            ->with('unit_pegangan') 
            ->get();

        $units = Unit::where('branch_id', $user->branch_id)
            ->where('status_operasional', 'Aktif')
            ->get();

        $siswas = User::with('package')
            ->where('role', 'siswa')
            ->where('branch_id', $user->branch_id)
            ->where('status', 'Aktif')
            ->get();

        $search = $request->search;

        return view('admin.jadwal.index', compact('jadwals', 'instructors', 'units', 'siswas', 'status', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'tanggal'       => 'required|date',
            'jam_mulai'     => 'required',
            'instructor_id' => 'required|exists:users,id',
            'unit_id'       => 'required|exists:units,id',
        ]);

        $siswa = User::with('package')->findOrFail($request->user_id);
        $instructor = User::findOrFail($request->instructor_id);
        $unit = Unit::findOrFail($request->unit_id);

        // 1. Cek Kuota Sesi
        $maxSesi = $siswa->package->pertemuan ?? 0;
        $sesiTerpakai = Jadwal::where('user_id', $siswa->id)->where('status', '!=', 'Batal')->count();
        if ($sesiTerpakai >= $maxSesi) {
            return back()->with('error', 'SISTEM MENOLAK: Kuota pertemuan siswa ini sudah habis (' . $maxSesi . '/' . $maxSesi . ').');
        }

        // 2. Cek Validasi Transmisi (MUTLAK: Instruktur vs Unit Mobil)
        $transmisiSiswa = $siswa->package->transmisi ?? 'Manual';
        $transmisiUnit = $unit->transmisi;
        $transmisiInstruktur = $instructor->kategori_transmisi;

        if ($transmisiUnit !== 'Manual & Matic') {
            if ($transmisiInstruktur !== 'Manual & Matic' && $transmisiInstruktur !== $transmisiUnit) {
                return back()->with('error', "SISTEM MENOLAK: Instruktur spesialis {$transmisiInstruktur} tidak bisa mengajar menggunakan mobil {$transmisiUnit}.");
            }
        }

        // 3. Logic: Pindah Transmisi Siswa (Manual -> Matic Kena Charge)
        $is_pindah_matic = false;
        if ($transmisiSiswa === 'Manual' && $transmisiUnit === 'Matic') {
            $is_pindah_matic = true;
        }

        // 4. Cek Cuti Instruktur
        if ($instructor->isCuti($request->tanggal)) {
            return back()->with('error', 'SISTEM MENOLAK: Instruktur sedang cuti/izin pada tanggal tersebut.');
        }

        // 5. Cek Bentrok Jadwal Siswa
        $bentrokSiswa = Jadwal::where('user_id', $siswa->id)
            ->where('tanggal', $request->tanggal)
            ->where('jam_mulai', $request->jam_mulai)
            ->whereIn('status', ['Pending', 'Disetujui'])
            ->exists();
        if ($bentrokSiswa) {
            return back()->with('error', 'SISTEM MENOLAK: Siswa tersebut sudah memiliki jadwal di hari dan jam yang sama.');
        }

        // 6. Cek Bentrok Instruktur
        $bentrokInstruktur = Jadwal::where('instructor_id', $instructor->id)
            ->where('tanggal', $request->tanggal)
            ->where('jam_mulai', $request->jam_mulai)
            ->whereIn('status', ['Pending', 'Disetujui'])
            ->exists();
        if ($bentrokInstruktur) {
            return back()->with('error', 'SISTEM MENOLAK: Instruktur sudah memiliki jadwal mengajar di jam tersebut.');
        }

        // 7. Cek Bentrok Unit Armada
        $bentrokUnit = Jadwal::where('unit_id', $unit->id)
            ->where('tanggal', $request->tanggal)
            ->where('jam_mulai', $request->jam_mulai)
            ->whereIn('status', ['Pending', 'Disetujui'])
            ->exists();
        if ($bentrokUnit) {
            return back()->with('error', 'SISTEM MENOLAK: Unit kendaraan ini sudah di-booking pada jam tersebut.');
        }

        // 8. Cek Extra Charge Lembut (Jam >= 16:00 Reguler)
        $jamMulaiInt = (int) substr($request->jam_mulai, 0, 2);
        
        // 🔥 LOGIC IZIN JAM 12:00 SUDAH DIBUKA (Pengecekan penolakan jam 12 telah dihapus)

        $is_extra = 0;
        $status_pembayaran_extra = 'Tidak Ada';

        if (($siswa->package->kategori ?? 'Reguler') == 'Reguler' && $jamMulaiInt >= 16) {
            $is_extra = 1;
            $status_pembayaran_extra = 'Belum Lunas';
            
            Pembayaran::create([
                'user_id'       => $siswa->id,
                'branch_id'     => Auth::user()->branch_id,
                'jenis_tagihan' => 'Tambahan',
                'keterangan'    => 'Biaya tambahan sesi jam non-reguler (Pukul ' . $request->jam_mulai . ' WIB)',
                'total_tagihan' => 20000, 
                'status'        => 'Pending'
            ]);
        }

        // 9. Generate Invoice Pindah Matic (Jika Berlaku)
        if ($is_pindah_matic) {
            Pembayaran::create([
                'user_id'       => $siswa->id,
                'branch_id'     => Auth::user()->branch_id,
                'jenis_tagihan' => 'Tambahan',
                'keterangan'    => 'Biaya charge pindah transmisi Manual ke Matic (Jadwal: ' . $request->tanggal . ')',
                'total_tagihan' => 20000, 
                'status'        => 'Pending'
            ]);
        }

        // 10. Eksekusi Simpan Jadwal
        Jadwal::create([
            'user_id'                 => $siswa->id,
            'instructor_id'           => $instructor->id,
            'unit_id'                 => $unit->id,
            'branch_id'               => Auth::user()->branch_id,
            'tanggal'                 => $request->tanggal,
            'jam_mulai'               => $request->jam_mulai,
            'status'                  => 'Disetujui',
            'is_extra_charge'         => $is_extra,
            'status_pembayaran_extra' => $status_pembayaran_extra
        ]);

        $pesan = 'Jadwal siswa berhasil di-plotting!';
        if ($is_extra && $is_pindah_matic) {
            $pesan = 'Jadwal di-plotting! Tagihan lembur (Rp 20.000) & charge pindah Matic (Rp 20.000) otomatis diterbitkan.';
        } elseif ($is_extra) {
            $pesan = 'Jadwal di-plotting! Tagihan lembur Rp 20.000 otomatis diterbitkan.';
        } elseif ($is_pindah_matic) {
            $pesan = 'Jadwal di-plotting! Tagihan charge pindah ke Matic Rp 20.000 otomatis diterbitkan.';
        }

        return redirect('/admin/jadwal?status=Disetujui')->with('success', $pesan);
    }

    public function updateFull(Request $request, $id)
    {
        $jadwal = Jadwal::with('user.package')->findOrFail($id);

        $request->validate([
            'instructor_id' => 'nullable|exists:users,id',
            'unit_id'       => 'nullable|exists:units,id', 
            'status'        => 'required|in:Pending,Disetujui,Selesai,Batal,Dibatalkan'
        ]);

        $statusUpdate = $request->status;
        if ($statusUpdate == 'Dibatalkan') {
            $statusUpdate = 'Batal';
        }

        $instructor_id = $request->instructor_id;
        $unit_id = $request->unit_id; 

        if ($statusUpdate == 'Pending') {
            $instructor_id = null;
            $unit_id = null;
        }

        $is_pindah_matic = false;

        if (in_array($statusUpdate, ['Disetujui', 'Selesai'])) {
            
            if ($statusUpdate == 'Disetujui' && $jadwal->is_extra_charge == 1) {
                if ($jadwal->status_pembayaran_extra !== 'Lunas') {
                    $oldStatus = $jadwal->status == 'Batal' ? 'Dibatalkan' : $jadwal->status;
                    return redirect('/admin/jadwal?status='.$oldStatus)->with('error', 'SISTEM TERKUNCI: Siswa belum melunasi biaya tambahan (Rp 20.000). Harap verifikasi pembayaran di menu Keuangan lebih dulu!');
                }
            }

            if (!$instructor_id) {
                $oldStatus = $jadwal->status == 'Batal' ? 'Dibatalkan' : $jadwal->status;
                return redirect('/admin/jadwal?status='.$oldStatus)->with('error', 'Gagal Plotting! Silahkan pilih instruktur bertugas terlebih dahulu.');
            }

            if (!$unit_id) {
                $oldStatus = $jadwal->status == 'Batal' ? 'Dibatalkan' : $jadwal->status;
                return redirect('/admin/jadwal?status='.$oldStatus)->with('error', 'Gagal Plotting! Silahkan pilih Unit Kendaraan terlebih dahulu.');
            }

            $instructor = User::findOrFail($instructor_id);
            $unit = Unit::findOrFail($unit_id);

            // Validasi Mutlak Instruktur vs Unit
            $transmisiSiswa = $jadwal->user->package->transmisi ?? 'Manual';
            $transmisiUnit = $unit->transmisi;
            $transmisiInstruktur = $instructor->kategori_transmisi;
            
            if ($transmisiUnit !== 'Manual & Matic') {
                if ($transmisiInstruktur !== 'Manual & Matic' && $transmisiInstruktur !== $transmisiUnit) {
                    $oldStatus = $jadwal->status == 'Batal' ? 'Dibatalkan' : $jadwal->status;
                    return redirect('/admin/jadwal?status='.$oldStatus)->with('error', "SISTEM MENOLAK: Instruktur spesialis {$transmisiInstruktur} tidak bisa mengajar menggunakan mobil {$transmisiUnit}.");
                }
            }

            // Logic Cek Tagihan Pindah Matic
            if ($transmisiSiswa === 'Manual' && $transmisiUnit === 'Matic') {
                // Cegah duplicate charge untuk jadwal yang sama
                $sudah_charge = Pembayaran::where('user_id', $jadwal->user_id)
                    ->where('keterangan', 'like', 'Biaya charge pindah transmisi Manual ke Matic (Jadwal: ' . $jadwal->tanggal . ')%')
                    ->exists();
                
                if (!$sudah_charge) {
                    $is_pindah_matic = true;
                }
            }

            if ($instructor->isCuti($jadwal->tanggal)) {
                $oldStatus = $jadwal->status == 'Batal' ? 'Dibatalkan' : $jadwal->status;
                return redirect('/admin/jadwal?status='.$oldStatus)->with('error', 'Instruktur sedang cuti/izin pada tanggal tersebut.');
            }

            if ($instructor->isSibuk($jadwal->tanggal, $jadwal->jam_mulai, $id)) {
                $oldStatus = $jadwal->status == 'Batal' ? 'Dibatalkan' : $jadwal->status;
                return redirect('/admin/jadwal?status='.$oldStatus)->with('error', 'Instruktur sudah memiliki jadwal lain di jam tersebut.');
            }

            $jadwalBentrokUnit = Jadwal::where('tanggal', $jadwal->tanggal)
                ->where('jam_mulai', $jadwal->jam_mulai)
                ->where('unit_id', $unit_id)
                ->whereNotIn('status', ['Batal', 'Dibatalkan', 'Ditolak']) 
                ->where('id', '!=', $id) 
                ->exists();

            if($jadwalBentrokUnit) {
                $oldStatus = $jadwal->status == 'Batal' ? 'Dibatalkan' : $jadwal->status;
                return redirect('/admin/jadwal?status='.$oldStatus)->with('error', 'SISTEM MENOLAK: Unit kendaraan ini sudah di-booking untuk kegiatan operasional pada jam tersebut.');
            }
        }

        // Eksekusi Generate Tagihan Baru (Jika Berlaku)
        if ($is_pindah_matic && in_array($statusUpdate, ['Disetujui', 'Selesai'])) {
            Pembayaran::create([
                'user_id'       => $jadwal->user_id,
                'branch_id'     => Auth::user()->branch_id,
                'jenis_tagihan' => 'Tambahan',
                'keterangan'    => 'Biaya charge pindah transmisi Manual ke Matic (Jadwal: ' . $jadwal->tanggal . ')',
                'total_tagihan' => 20000, 
                'status'        => 'Pending'
            ]);
        }

        $jadwal->update([
            'instructor_id' => $instructor_id,
            'unit_id'       => $unit_id, 
            'status'        => $statusUpdate,
            'branch_id'     => Auth::user()->branch_id 
        ]);

        if ($statusUpdate == 'Batal') {
            $redirectTab = 'Dibatalkan'; 
        } elseif ($statusUpdate == 'Pending') {
            $redirectTab = 'Pending'; 
        } else {
            $redirectTab = $statusUpdate; 
        }

        $pesan_akhir = 'Jadwal dan Plotting Kendaraan berhasil diperbarui!';
        if ($is_pindah_matic) {
            $pesan_akhir .= ' Tagihan charge pindah ke Matic (Rp 20.000) otomatis diterbitkan ke siswa.';
        }

        return redirect('/admin/jadwal?status=' . $redirectTab)->with('success', $pesan_akhir);
    }

    public function updateJadwal(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'required'
        ]);

        $jadwal = Jadwal::findOrFail($id);
        
        if ($jadwal->instructor_id) {
            $bentrokJadwal = Jadwal::where('instructor_id', $jadwal->instructor_id)
                ->where('tanggal', $request->tanggal)
                ->where('jam_mulai', $request->jam_mulai)
                ->where('id', '!=', $id) 
                ->whereIn('status', ['Pending', 'Disetujui'])
                ->exists();

            if ($bentrokJadwal) {
                $oldStatus = $jadwal->status == 'Batal' ? 'Dibatalkan' : $jadwal->status;
                return redirect('/admin/jadwal?status='.$oldStatus)->with('error', 'SISTEM MENOLAK: Reschedule Bentrok! Instruktur tersebut tidak bisa di jam baru ini.');
            }
        }

        if ($jadwal->unit_id) {
            $bentrokUnit = Jadwal::where('unit_id', $jadwal->unit_id)
                ->where('tanggal', $request->tanggal)
                ->where('jam_mulai', $request->jam_mulai)
                ->where('id', '!=', $id)
                ->whereIn('status', ['Pending', 'Disetujui'])
                ->exists();

            if ($bentrokUnit) {
                $oldStatus = $jadwal->status == 'Batal' ? 'Dibatalkan' : $jadwal->status;
                return redirect('/admin/jadwal?status='.$oldStatus)->with('error', 'SISTEM MENOLAK: Reschedule Bentrok! Mobil operasional sudah terpakai di jam baru ini.');
            }
        }

        $jadwal->update([
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
        ]);

        $oldStatus = $jadwal->status == 'Batal' ? 'Dibatalkan' : $jadwal->status;
        return redirect('/admin/jadwal?status='.$oldStatus)->with('success', 'Waktu jadwal berhasil diubah.');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $currentStatus = $jadwal->status == 'Batal' ? 'Dibatalkan' : $jadwal->status;
        $jadwal->delete();
        return redirect('/admin/jadwal?status='.$currentStatus)->with('success', 'Jadwal berhasil dihapus.');
    }
}