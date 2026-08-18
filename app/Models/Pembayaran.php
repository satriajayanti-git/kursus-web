<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $fillable = [
        'user_id', 
        'id_package', 
        'branch_id', 
        'total_tagihan', 
        'status', 
        'approved_by', 
        'bukti_bayar', 
        'jenis_tagihan', 
        'keterangan'
    ];

    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function branch() { return $this->belongsTo(Branch::class, 'branch_id'); }
    public function package() { return $this->belongsTo(Package::class, 'id_package', 'id_package'); }
    
    // 🔥 RELASI PIC ADMIN: Digunakan untuk memanggil nama admin di laporan cetak
    public function approver() { return $this->belongsTo(User::class, 'approved_by'); }
}