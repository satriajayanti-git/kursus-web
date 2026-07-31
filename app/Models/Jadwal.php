<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $fillable = [
        'user_id', 
        'instructor_id', 
        'branch_id',
        'tanggal', 
        'jam_mulai', 
        'status', 
        'catatan_evaluasi', 
        'rating', 
        'feedback_siswa',
        'is_extra_charge',
        'unit_id',          
        'status_pembayaran_extra'   
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}