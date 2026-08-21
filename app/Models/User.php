<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username', 'id_siswa', 'email', 'password', 'role', 'branch_id',
        'id_package', 'nama_lengkap', 'no_telp', 'alamat', 'kategori_transmisi', 'tipe_instruktur', 'status', 
        'registered_by' // 🔥 Tambahan kolom agar ID Admin bisa disimpan permanen
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'id_package', 'id_package');
    }

    // 🔥 LOGIC BARU: Relasi ke Admin yang melayani pendaftaran siswa ini
    public function registrar()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'user_id', 'id');
    }

    public function instructor_jadwals()
    {
        return $this->hasMany(Jadwal::class, 'instructor_id', 'id');
    }

    public function cutis()
    {
        return $this->hasMany(Cuti::class, 'user_id', 'id');
    }
    
    public function isCuti($tanggal)
    {
        return $this->cutis()
                    ->where('status', 'Disetujui')
                    ->where('tanggal_mulai', '<=', $tanggal)
                    ->where('tanggal_selesai', '>=', $tanggal)
                    ->exists();
    }

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