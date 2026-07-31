<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan foreign key yang merujuk ke id_package di tabel packages
            $table->unsignedBigInteger('id_package')->nullable()->after('role');
            
            // Opsional: Jika ingin memastikan integritas data (Foreign Key Constraint)
            // $table->foreign('id_package')->references('id_package')->on('packages')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id_package');
        });
    }
};