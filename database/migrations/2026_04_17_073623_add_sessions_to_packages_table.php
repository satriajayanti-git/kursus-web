<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('packages', function (Blueprint $table) {
            $table->integer('jumlah_sesi')->default(5)->after('harga');
        });
    }
    public function down(): void {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('jumlah_sesi');
        });
    }
};