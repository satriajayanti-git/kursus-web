<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, Branch, Setting, Package, Pembayaran};
use Illuminate\Support\Facades\{Hash, DB};

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $branches = Branch::all(); 
        $packages = Package::all(); 
        $setting = Setting::first(); 
        
        return view('auth.register', compact('branches', 'packages', 'setting')); 
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username'     => 'required|string|max:255|unique:users',
            'email'        => 'required|email|unique:users',
            'password'     => 'required|string|min:6',
            'no_telp'      => 'required|string|max:20',
            'alamat'       => 'required|string', 
            'branch_id'    => 'required|exists:branches,id',
            'package_id'   => 'required|exists:packages,id_package' 
        ]);

        DB::transaction(function () use ($request) {
            
            // 🔥 LOGIC AUTO-GENERATE ID SISWA FORMAT BARU
            $prefix = 'SJN';
            $bulan = date('m'); 
            $tahun = date('y'); 
            
            $idCabangPad = str_pad($request->branch_id, 2, '0', STR_PAD_LEFT);
            $formatDepan = $prefix . $bulan . $tahun . $idCabangPad;

            $siswaTerakhir = User::where('role', 'siswa')
                                ->where('id_siswa', 'like', $formatDepan . '%')
                                ->orderBy('id_siswa', 'desc')
                                ->first();

            if ($siswaTerakhir && $siswaTerakhir->id_siswa) {
                $urutanTerakhir = (int) substr($siswaTerakhir->id_siswa, strlen($formatDepan));
                $urutanBaru = $urutanTerakhir + 1;
                $id_siswa_baru = $formatDepan . str_pad($urutanBaru, 2, '0', STR_PAD_LEFT); 
            } else {
                $id_siswa_baru = $formatDepan . '01';
            }

            // 1. Simpan User Siswa
            $user = User::create([
                'id_siswa'     => $id_siswa_baru, 
                'nama_lengkap' => $request->nama_lengkap,
                'username'     => $request->username,
                'email'        => $request->email,
                'password'     => Hash::make($request->password),
                'no_telp'      => $request->no_telp,
                'alamat'       => $request->alamat, 
                'role'         => 'siswa',
                'branch_id'    => $request->branch_id,
                'id_package'   => $request->package_id, 
                'status'       => 'Non-Aktif' 
            ]);

            $package = Package::where('id_package', $request->package_id)->first();

            // 2. 🔥 LOGIC BARU: Otomatisasi Invoice Paket Utama (Harga Asli Paket Tanpa 40k)
            Pembayaran::create([
                'user_id'       => $user->id,
                'id_package'    => $request->package_id,
                'branch_id'     => $request->branch_id,
                'total_tagihan' => $package->harga,
                'jenis_tagihan' => 'Paket Utama',
                'status'        => 'Pending',
                'keterangan'    => 'Pendaftaran kursus paket: ' . $package->nama_package
            ]);

            // 3. 🔥 LOGIC BARU: Otomatisasi Invoice Biaya Pendaftaran (Terpisah)
            Pembayaran::create([
                'user_id'       => $user->id,
                'id_package'    => $request->package_id, // Disematkan agar sinkron
                'branch_id'     => $request->branch_id,
                'total_tagihan' => 40000,
                'jenis_tagihan' => 'Tambahan', // Dibuat 'Tambahan' agar masuk ke kotak tagihan ekstra
                'status'        => 'Pending',
                'keterangan'    => 'Biaya Pendaftaran Siswa Baru'
            ]);
        });

        return redirect('/login')->with('success', 'Pendaftaran berhasil! Silakan login untuk melanjutkan ke Dashboard Siswa.');
    }
}