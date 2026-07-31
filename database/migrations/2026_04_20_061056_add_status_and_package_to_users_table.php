<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {     
            // Tambahin kolom status dengan default 'Non-Aktif'  
            $table->string('status')->default('Non-Aktif')->after('role');

            // Tambahin kolom package_id biar konek ke tabel packages
            $table->unsignedBigInteger('package_id')->nullable()->after('branch_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {     
            $table->dropColumn(['status', 'package_id']);        
        });
    }
};