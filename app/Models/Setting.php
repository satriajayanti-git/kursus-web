<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'nama_website', 
        'logo', // Tambahan izin buat logo
        'hero_title', 
        'hero_description', 
        'foto_hero',
        'no_telp', 
        'email', 
        'alamat', 
        'instagram',
        'visi',
        'misi',
        'about_text',
        'cara_daftar'
    ];
}