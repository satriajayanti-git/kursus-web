<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    // Mengizinkan Laravel untuk menyimpan data ke kolom-kolom ini
    protected $fillable = [
        'nama_mobil', 
        'foto_unit',
        'nopol', // Gua tambahin nopol sesuai penjelasan lu sebelumnya
        'tgl_jatuh_tempo_pajak',
        'tgl_terakhir_bayar_pajak',
        'tgl_jatuh_tempo_kir',
        'tgl_terakhir_bayar_kir',
        'branch_id', 
        'instruktur_id', 
        'status_kepemilikan', 
        'status_operasional',
        'transmisi'
    ];


    // Mengubah tipe data tanggal menjadi objek Carbon secara otomatis
    // Ini sangat krusial untuk logic pengecekan H-14 di Controller nanti
    protected $casts = [
        'tgl_jatuh_tempo_pajak' => 'date',
        'tgl_terakhir_bayar_pajak' => 'date',
        'tgl_jatuh_tempo_kir' => 'date',
        'tgl_terakhir_bayar_kir' => 'date',
    ];
    // ====================================================================
    // 🔥 RELASI ERP OPERASIONAL (JANGAN HAPUS KODE LAMA DI ATASNYA)
    // ====================================================================

    // 1. Relasi ke Cabang (Untuk melacak posisi mobil rolling)
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    // 2. Relasi ke Instruktur Penanggung Jawab
    public function instruktur()
    {
        return $this->belongsTo(User::class, 'instruktur_id');
    }

    // 3. Relasi ke Jadwal (Untuk Audit Trail / Riwayat Pemakaian Mobil)
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'unit_id');
    }
    public function unit_pegangan()
    {
        return $this->hasOne(Unit::class, 'instruktur_id');
    }
}
