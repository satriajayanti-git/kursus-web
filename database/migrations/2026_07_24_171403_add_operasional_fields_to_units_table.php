<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('units', function (Blueprint $table) {
            // 1. Tambahkan relasi ke cabang (Wajib untuk melacak unit rolling)
            $table->unsignedBigInteger('branch_id')->nullable()->after('id');
            
            // 2. ID Instruktur penanggung jawab utama
            $table->unsignedBigInteger('instruktur_id')->nullable()->after('branch_id');
            
            // 3. Status kepemilikan operasional unit
            $table->enum('status_kepemilikan', ['Tetap', 'Rolling'])->default('Tetap')->after('instruktur_id');
            
            // 4. Status kondisi mobil
            $table->enum('status_operasional', ['Aktif', 'Maintenance'])->default('Aktif')->after('status_kepemilikan');

            // Set Foreign Keys
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
            $table->foreign('instruktur_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('units', function (Blueprint $table) {
            // Drop foreign keys terlebih dahulu
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['instruktur_id']);
            
            // Drop kolom
            $table->dropColumn(['branch_id', 'instruktur_id', 'status_kepemilikan', 'status_operasional']);
        });
    }
};