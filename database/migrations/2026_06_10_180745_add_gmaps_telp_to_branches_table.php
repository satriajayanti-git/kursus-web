<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('branches', function (Blueprint $table) {
            // Gunakan text() untuk link Gmaps karena URL bisa sangat panjang
            $table->text('link_gmaps')->nullable()->after('nama_cabang'); 
            $table->string('no_telp_admin', 20)->nullable()->after('link_gmaps');
        });
    }

    public function down()
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn(['link_gmaps', 'no_telp_admin']);
        });
    }
};
