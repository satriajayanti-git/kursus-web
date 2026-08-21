<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\{User, Package, Pembayaran}; 

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $admin = Auth::user();
        
        if (!$admin->branch_id) {
            return redirect('/admin/dashboard')->with('error', 'Akun Anda belum ditugaskan ke cabang manapun!');
        }

        $query = User::with('package')
                    ->where('role', 'siswa')
                    ->where('branch_id', $admin->branch_id);

        if ($request->filled('search')) {
            $search = $request->search;
            
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('username', 'like', '%' . $search . '%')
                  ->orWhere('id_siswa', 'like', '%' . $search . '%');
            });
        }

        $students = $query->orderBy('created_at', 'desc')->get();
        $search = $request->search;

        return view('admin.siswa.index', compact('students', 'search'));
    }

    public function store(Request $request)
    {
        $admin = Auth::user();
        
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username'     => 'required|string|unique:users',
            'email'        => 'required|email|unique:users',
            'password'     => 'required|string|min:6',
            'no_telp'      => 'required|string|max:20',
            'alamat'       => 'required|string',
            'id_package'   => 'required|exists:packages,id_package',
        ]);

        $currentMonth = date('m'); 
        $currentYear = date('Y');  
        $currentYearShort = date('y'); 
        
        $idCabangPad = str_pad($admin->branch_id, 2, '0', STR_PAD_LEFT);
        $prefixId = 'SJN' . $currentMonth . $currentYearShort . $idCabangPad; 

        $lastStudent = User::where('role', 'siswa')
            ->where('id_siswa', 'like', $prefixId . '%')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->orderBy('id_siswa', 'desc')
            ->first();

        if ($lastStudent && preg_match('/' . $prefixId . '(\d+)/', $lastStudent->id_siswa, $matches)) {
            $urutan = intval($matches[1]) + 1;
        } else {
            $urutan = 1;
        }

        $id_siswa = $prefixId . str_pad($urutan, 2, '0', STR_PAD_LEFT);

        // Buat Akun Siswa Baru & Kunci Nama Admin
        $siswa = User::create([
            'id_siswa'     => $id_siswa,
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => $request->username,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'no_telp'      => $request->no_telp,
            'alamat'       => $request->alamat,
            'id_package'   => $request->id_package,
            'role'         => 'siswa',
            'branch_id'    => $admin->branch_id,
            'status'       => 'Non-Aktif',
            'registered_by'=> $admin->id, // 🔥 Mengikat ID Admin yang mendaftarkan siswa ini secara permanen
        ]);

        $paket = Package::where('id_package', $request->id_package)->first();

        Pembayaran::create([
            'user_id'       => $siswa->id,
            'id_package'    => $paket->id_package,
            'branch_id'     => $admin->branch_id,
            'total_tagihan' => $paket->harga,
            'jenis_tagihan' => 'Paket Utama',
            'status'        => 'Pending',
            'keterangan'    => 'Pendaftaran Offline via Admin. Menunggu upload bukti bayar.',
        ]);

        return back()->with('success', 'Akun siswa berhasil dibuat dengan ID ' . $id_siswa . '! Tagihan otomatis telah diterbitkan di menu Keuangan.');
    }

    public function update(Request $request, $id)
    {
        $admin = Auth::user();
        $student = User::findOrFail($id);

        $statusAwal = $student->status;

        if ($student->branch_id != $admin->branch_id) {
            return back()->with('error', 'Akses ditolak! Anda tidak dapat mengubah data siswa dari cabang lain.');
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'id_package'   => 'required|exists:packages,id_package',
            'username'     => 'required|string|unique:users,username,' . $id,
            'email'        => 'required|email|unique:users,email,' . $id,
            'no_telp'      => 'required|string|max:20',
            'status'       => 'required|in:Aktif,Non-Aktif',
            'alamat'       => 'required|string',
            'password'     => 'nullable|string|min:6',
        ]);

        $student->nama_lengkap = $request->nama_lengkap;
        $student->username     = $request->username;
        $student->email        = $request->email;
        $student->no_telp      = $request->no_telp;
        $student->status       = $request->status;
        $student->alamat       = $request->alamat;

        if ($statusAwal !== 'Aktif') {
            $student->id_package = $request->id_package;
        }

        if ($request->filled('password')) {
            $student->password = Hash::make($request->password);
        }

        $student->save();

        return back()->with('success', 'Perubahan data siswa ' . $student->nama_lengkap . ' berhasil disimpan!');
    }

    public function destroy($id)
    {
        $student = User::findOrFail($id);

        if ($student->branch_id != Auth::user()->branch_id) {
            return back()->with('error', 'Akses ditolak! Anda tidak dapat menghapus siswa dari cabang lain.');
        }

        $student->delete();

        return back()->with('success', 'Data siswa berhasil dihapus dari sistem.');
    }
}