<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('packages', function (Blueprint $table) {
            // Menambah kolom untuk batasan sesi latihan
            $table->integer('jumlah_pertemuan')->default(1)->after('harga');
        });
    }

    public function down(): void {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('jumlah_pertemuan');
        });
    }
};