<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
        // Tambahin penanda lembur dan status bayar
            $table->boolean('is_extra_charge')->default(0)->after('status');
            $table->enum('status_pembayaran_extra', ['Tidak Ada', 'Belum Lunas', 'Lunas'])->default('Tidak Ada')->after('is_extra_charge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            //
        });
    }
};
