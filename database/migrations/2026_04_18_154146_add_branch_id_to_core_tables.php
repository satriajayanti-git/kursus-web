<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Tambahkan branch_id ke Users (Untuk lokasi penugasan Admin/Instruktur)
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null')->after('role');
        });

        // Tambahkan branch_id ke Jadwals (Agar jadwal tertata per cabang)
        Schema::table('jadwals', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade')->after('user_id');
        });

        // Tambahkan branch_id ke Pembayarans (Agar laporan keuangan terpisah per cabang)
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade')->after('user_id');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) { $table->dropColumn('branch_id'); });
        Schema::table('jadwals', function (Blueprint $table) { $table->dropColumn('branch_id'); });
        Schema::table('pembayarans', function (Blueprint $table) { $table->dropColumn('branch_id'); });
    }
};