<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('jam_mulai', ['08:00', '10:00', '13:00', '15:00']);
            $table->enum('status', ['Pending', 'Disetujui', 'Selesai', 'Batal'])->default('Pending');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('jadwals');
    }
};