<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('branches', function (Blueprint $table) {
            // Nambahin kolom nama_cabang kalau belum ada
            if (!Schema::hasColumn('branches', 'nama_cabang')) {
                $table->string('nama_cabang')->nullable();
            }
            // Nambahin kolom lokasi kalau belum ada
            if (!Schema::hasColumn('branches', 'lokasi')) {
                $table->string('lokasi')->nullable()->after('nama_cabang');
            }
            // Nambahin kolom detail kalau belum ada
            if (!Schema::hasColumn('branches', 'detail')) {
                $table->text('detail')->nullable()->after('lokasi');
            }
            // Jaga-jaga mastiin kolom foto juga beneran ada di tabel cabang lu
            if (!Schema::hasColumn('branches', 'foto')) {
                $table->string('foto')->nullable()->after('detail');
            }
        });
    }

    public function down()
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn(['nama_cabang', 'lokasi', 'detail', 'foto']);
        });
    }
};