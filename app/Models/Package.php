<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $primaryKey = 'id_package';

    protected $fillable = [
        'nama_package', 
        'harga', 
        'jumlah_pertemuan',
        'transmisi', // Tambahan kategori transmisi paket
        'detail',
        'kategori' // 🔥 Field baru untuk membedakan Reguler & Non-Reguler (VIP)
    ];
}