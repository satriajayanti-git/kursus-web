<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        // Mengubah kolom jam_mulai menjadi VARCHAR agar bisa menerima jam berapapun
        DB::statement("ALTER TABLE jadwals MODIFY jam_mulai VARCHAR(10)");
    }

    public function down(): void {
        // Rollback ke ENUM lama (hanya sebagai cadangan)
        DB::statement("ALTER TABLE jadwals MODIFY jam_mulai ENUM('08:00', '10:00', '13:00', '15:00')");
    }
};