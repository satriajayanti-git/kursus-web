<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\{Pembayaran, User, Jadwal, Package};

class KeuanganController extends Controller
{
    /**
     * Daftar jumlah pertemuan yang memang diperbolehkan untuk paket kursus.
     * Urutan ini juga dipakai untuk menentukan opsi upgrade.
     */
    private const PAKET_PERTEMUAN = [7, 8, 10, 12, 14, 15];

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

        // Data Siswa untuk dropdown Modal Tambah Tagihan.
        // Relasi package di-load untuk kebutuhan fitur Upgrade Paket.
        $siswas = User::with('package')
            ->where('role', 'siswa')
            ->where('branch_id', $user->branch_id)
            ->get();

        // Semua paket yang tersedia dipakai untuk menentukan target upgrade.
        // Paket disaring lagi di helper berdasarkan kategori + transmisi siswa.
        $packages = Package::orderBy('id_package')->get();
        $upgradeOptionsByUser = $this->buildUpgradeOptions($siswas, $packages);

        // LOGIC PERBAIKAN: Query dari sisi tabel Pembayaran (menghindari error Model User)
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

        return view('admin.keuangan.index', compact(
            'siswasMutasi',
            'siswas',
            'total_omset',
            'search',
            'bulan',
            'status_bayar',
            'upgradeOptionsByUser'
        ));
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
            'user_id'            => 'required|exists:users,id',
            'jenis_tambahan'     => 'required|in:biaya_sim,tip_instruktur,upgrade_paket',
            'total_tagihan'     => 'required|numeric|min:1000',
            'target_package_id' => 'nullable|integer|exists:packages,id_package',
        ]);

        $admin = Auth::user();

        // Pastikan admin hanya membuat tagihan untuk siswa di cabangnya sendiri.
        $siswa = User::with('package')
            ->where('id', $request->user_id)
            ->where('role', 'siswa')
            ->where('branch_id', $admin->branch_id)
            ->first();

        if (!$siswa) {
            return back()->withInput()->with('error', 'Siswa tidak ditemukan atau bukan siswa pada cabang Anda.');
        }

        // Biaya non-upgrade tetap mengikuti alur lama: admin menentukan nominal sendiri.
        if ($request->jenis_tambahan !== 'upgrade_paket') {
            $keterangan = $request->jenis_tambahan === 'biaya_sim'
                ? 'Biaya SIM'
                : 'Tip Instruktur';

            Pembayaran::create([
                'user_id'       => $siswa->id,
                'branch_id'     => $admin->branch_id,
                'total_tagihan' => $request->total_tagihan,
                'jenis_tagihan' => 'Tambahan',
                'keterangan'    => $keterangan,
                'status'        => 'Pending',
                'approved_by'   => Auth::id(),
            ]);

            return back()->with('success', 'Tagihan tambahan berhasil dibuat!');
        }

        if (!$siswa->package) {
            return back()->withInput()->with('error', 'Siswa tersebut belum memiliki paket yang dapat di-upgrade.');
        }

        $targetPackage = Package::where('id_package', $request->target_package_id)->first();

        if (!$targetPackage) {
            return back()->withInput()->with('error', 'Paket tujuan upgrade tidak ditemukan.');
        }

        $options = $this->buildUpgradeOptions(collect([$siswa]), Package::orderBy('id_package')->get());
        $allowedPackageIds = collect($options[$siswa->id] ?? [])->pluck('id_package')->map(fn ($id) => (int) $id);

        if (!$allowedPackageIds->contains((int) $targetPackage->id_package)) {
            return back()->withInput()->with('error', 'Paket tujuan tidak tersedia untuk upgrade dari paket siswa saat ini.');
        }

        $currentPackagePrice = (int) ($siswa->package->harga ?? 0);
        $targetPackagePrice = (int) ($targetPackage->harga ?? 0);
        $selisihPaket = $targetPackagePrice - $currentPackagePrice;

        if ($selisihPaket <= 0) {
            return back()->withInput()->with('error', 'Nominal upgrade tidak valid karena harga paket tujuan tidak lebih tinggi dari paket saat ini.');
        }

        // Gunakan nominal yang dipilih admin. Nilai awal dari UI adalah selisih paket.
        // Nominal sengaja tetap editable sesuai kebutuhan operasional.
        $totalTagihan = (int) $request->total_tagihan;

        DB::transaction(function () use ($siswa, $targetPackage, $totalTagihan, $selisihPaket) {
            $namaPaketLama = $siswa->package->nama_package ?? 'Paket Saat Ini';
            $namaPaketBaru = $targetPackage->nama_package ?? 'Paket Baru';

            Pembayaran::create([
                'user_id'       => $siswa->id,
                'id_package'    => $targetPackage->id_package,
                'branch_id'     => Auth::user()->branch_id,
                'total_tagihan' => $totalTagihan,
                'jenis_tagihan' => 'Tambahan',
                'keterangan'    => 'Upgrade Paket: ' . $namaPaketLama . ' -> ' . $namaPaketBaru . ' (Selisih Rp ' . number_format($selisihPaket, 0, ',', '.') . ')',
                'status'        => 'Pending',
                'approved_by'   => Auth::id(),
            ]);

            // Sesuai permintaan operasional: setelah upgrade dibuat, paket siswa langsung berubah
            // ke paket tujuan agar data siswa dan paket yang tampil di sistem selalu mengikuti pilihan admin.
            $siswa->update([
                'id_package' => $targetPackage->id_package,
            ]);
        });

        return back()->with('success', 'Tagihan upgrade paket berhasil dibuat dan paket siswa otomatis diperbarui!');
    }

    /**
     * Membentuk opsi upgrade per siswa berdasarkan:
     * - jumlah pertemuan paket saat ini
     * - daftar jumlah pertemuan resmi perusahaan
     * - kategori paket saat ini (Reguler/Non-Reguler)
     * - transmisi paket saat ini (Manual/Matic/Manual & Matic)
     */
    private function buildUpgradeOptions($siswas, $packages): array
    {
        $result = [];

        foreach ($siswas as $siswa) {
            $currentPackage = $siswa->package;

            if (!$currentPackage) {
                $result[$siswa->id] = [];
                continue;
            }

            $currentMeetings = $this->getPackageMeetingCount($currentPackage);
            $currentCategory = $currentPackage->kategori ?? null;
            $currentTransmission = $currentPackage->transmisi ?? null;

            $candidates = $packages->filter(function ($package) use ($currentMeetings, $currentCategory, $currentTransmission) {
                $packageMeetings = $this->getPackageMeetingCount($package);

                if ($packageMeetings <= $currentMeetings || !in_array($packageMeetings, self::PAKET_PERTEMUAN, true)) {
                    return false;
                }

                // Upgrade tetap dalam kategori dan transmisi yang sama agar siswa tidak
                // otomatis berpindah karakter paket hanya karena memilih jumlah pertemuan.
                if ($currentCategory !== null && isset($package->kategori) && $package->kategori !== $currentCategory) {
                    return false;
                }

                if ($currentTransmission !== null && isset($package->transmisi) && $package->transmisi !== $currentTransmission) {
                    return false;
                }

                return true;
            });

            // Satu opsi per jumlah pertemuan. Jika ada duplikasi paket, gunakan record pertama
            // berdasarkan urutan id_package agar dropdown tidak membingungkan admin.
            $options = $candidates
                ->sortBy(function ($package) {
                    return [$this->getPackageMeetingCount($package), (int) $package->id_package];
                })
                ->groupBy(function ($package) {
                    return $this->getPackageMeetingCount($package);
                })
                ->map(function ($group) {
                    $package = $group->first();

                    return [
                        'id_package'       => (int) $package->id_package,
                        'nama_package'     => $package->nama_package,
                        'jumlah_pertemuan' => $this->getPackageMeetingCount($package),
                        'harga'            => (int) ($package->harga ?? 0),
                    ];
                })
                ->values()
                ->all();

            $result[$siswa->id] = $options;
        }

        return $result;
    }

    /**
     * Database lama memiliki dua kolom jumlah pertemuan (pertemuan dan jumlah_pertemuan).
     * Kolom "pertemuan" diprioritaskan karena pada data paket yang ada nilainya merepresentasikan
     * jumlah sesi (misalnya paket 8x memiliki pertemuan=8 walaupun jumlah_pertemuan bisa berisi 1).
     */
    private function getPackageMeetingCount($package): int
    {
        $pertemuan = (int) ($package->pertemuan ?? 0);
        if ($pertemuan > 0) {
            return $pertemuan;
        }

        return (int) ($package->jumlah_pertemuan ?? 0);
    }
}
