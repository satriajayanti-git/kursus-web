<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id', 'instruktur_id', 'tingkat_kendala', 'deskripsi', 'status_laporan'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function instruktur()
    {
        return $this->belongsTo(User::class, 'instruktur_id');
    }
}