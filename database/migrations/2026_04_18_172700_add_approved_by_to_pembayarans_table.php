<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pembayarans', function (Blueprint $table) {
            // Nambahin relasi ke tabel users untuk nyatet admin siapa yang ACC
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->after('status');
        });
    }

    public function down(): void {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn('approved_by');
        });
    }
};