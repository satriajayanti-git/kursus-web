<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('units', function (Blueprint $table) {
            // Menambahkan kolom tanggal (nullable agar data lama tidak error)
            $table->date('tgl_jatuh_tempo_pajak')->nullable()->after('foto_unit');
            $table->date('tgl_terakhir_bayar_pajak')->nullable()->after('tgl_jatuh_tempo_pajak');
            $table->date('tgl_jatuh_tempo_kir')->nullable()->after('tgl_terakhir_bayar_pajak');
            $table->date('tgl_terakhir_bayar_kir')->nullable()->after('tgl_jatuh_tempo_kir');

            // Jika nopol belum ada di tabel, aktifkan baris di bawah ini:
            // $table->string('nopol')->nullable()->after('nama_mobil');
        });
    }

    public function down()
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn([
                'tgl_jatuh_tempo_pajak', 
                'tgl_terakhir_bayar_pajak', 
                'tgl_jatuh_tempo_kir', 
                'tgl_terakhir_bayar_kir'
            ]);
        });
    }
};