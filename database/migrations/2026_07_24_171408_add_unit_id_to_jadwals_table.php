<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('jadwals', function (Blueprint $table) {
            // ID Unit kendaraan yang digunakan pada sesi tersebut
            $table->unsignedBigInteger('unit_id')->nullable()->after('instructor_id');
            
            // Set Foreign Key
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn('unit_id');
        });
    }
};