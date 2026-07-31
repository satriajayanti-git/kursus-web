<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            // Nyambungin ke ID Siswa (user_id)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Nyambungin ke ID Paket
            $table->unsignedBigInteger('package_id'); 
            $table->string('bukti_bayar')->nullable(); // Foto struk transfer
            $table->integer('total_tagihan');
            $table->enum('status', ['Pending', 'Lunas', 'Batal'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};