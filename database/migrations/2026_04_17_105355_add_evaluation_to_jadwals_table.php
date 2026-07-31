<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('jadwals', function (Blueprint $table) {
            // Kolom untuk menyimpan catatan dari instruktur
            $table->text('catatan_evaluasi')->nullable()->after('status');
        });
    }

    public function down(): void {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropColumn('catatan_evaluasi');
        });
    }
};