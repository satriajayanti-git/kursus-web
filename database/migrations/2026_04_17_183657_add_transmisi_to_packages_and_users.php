<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Tambah transmisi di paket kursus
        Schema::table('packages', function (Blueprint $table) {
            $table->enum('transmisi', ['Manual', 'Matic', 'Manual & Matic'])->default('Manual')->after('jumlah_pertemuan');
        });

        // Tambah spesialisasi transmisi di instruktur (tabel users)
        Schema::table('users', function (Blueprint $table) {
            $table->enum('kategori_transmisi', ['Manual', 'Matic', 'Manual & Matic'])->nullable()->after('alamat');
        });
    }

    public function down(): void {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('transmisi');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kategori_transmisi');
        });
    }
};