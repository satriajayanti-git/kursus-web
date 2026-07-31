<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username', 'id_siswa', 'email', 'password', 'role', 'branch_id',
        'id_package', 'nama_lengkap', 'no_telp', 'alamat', 'kategori_transmisi', 'tipe_instruktur', 'status'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Relasi ke Cabang
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    // Relasi ke Paket Kursus (Khusus Siswa) - FIX Menggunakan id_package
    public function package()
    {
        return $this->belongsTo(Package::class, 'id_package', 'id_package');
    }

    // Relasi Jadwal Siswa
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'user_id', 'id');
    }

    // Relasi Jadwal Instruktur
    public function instructor_jadwals()
    {
        return $this->hasMany(Jadwal::class, 'instructor_id', 'id');
    }

    // Relasi Cuti
    public function cutis()
    {
        return $this->hasMany(Cuti::class, 'user_id', 'id');
    }
    
    // Logic Cek Cuti
    public function isCuti($tanggal)
    {
        return $this->cutis()
                    ->where('status', 'Disetujui')
                    ->where('tanggal_mulai', '<=', $tanggal)
                    ->where('tanggal_selesai', '>=', $tanggal)
                    ->exists();
    }

    // Logic Cek Jadwal Bentrok
    public function isSibuk($tanggal, $jam)
    {
        return $this->instructor_jadwals()
                    ->whereIn('status', ['Pending', 'Disetujui'])
                    ->where('tanggal', $tanggal)
                    ->where('jam_mulai', $jam)
                    ->exists();
    }
    public function unit_pegangan()
    {
        return $this->hasOne(Unit::class, 'instruktur_id');
    }
}