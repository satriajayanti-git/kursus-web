<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'nama_cabang',
        'foto', 
        'foto_cabang',
        'link_gmaps',    
        'no_telp_admin'
    ];
}