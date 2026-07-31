<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laporan_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('instruktur_id');
            // 🔥 Level kendala menentukan respon sistem di Management nanti
            $table->enum('tingkat_kendala', ['Ringan', 'Berat'])->default('Ringan'); 
            $table->text('deskripsi');
            $table->enum('status_laporan', ['Menunggu', 'Diproses', 'Selesai'])->default('Menunggu');
            $table->timestamps();

            // Foreign Keys
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('instruktur_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan_units');
    }
};