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
        Schema::table('settings', function (Blueprint $table) {
            // Taruh kodenya di dalam sini!
            $table->string('foto_hero')->nullable()->after('hero_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // Ini untuk menghapus kolom jika migrasi dibatalkan
            $table->dropColumn('foto_hero');
        });
    }
};