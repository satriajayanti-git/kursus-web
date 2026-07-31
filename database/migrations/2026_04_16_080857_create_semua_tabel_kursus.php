<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Master
        Schema::create('packages', function (Blueprint $table) {
            $table->id('id_package');
            $table->string('nama_package', 100);
            $table->integer('harga');
            $table->text('detail')->nullable();
            $table->timestamps();
        });

        Schema::create('unit_kendaraans', function (Blueprint $table) {
            $table->id('id_unit');
            $table->string('merk_mobil', 100);
            $table->string('nopol', 20);
            $table->string('transmisi', 20);
            $table->date('kir')->nullable();
            $table->date('pajak')->nullable();
            $table->timestamps();
        });

        // 2. Tabel Profil (Relasi ke tabel users bawaan Laravel)
        // Catatan: Laravel sudah punya tabel 'users', kita cukup tambahkan role di sana nanti
        
        Schema::create('admins', function (Blueprint $table) {
            $table->id('id_admin');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama', 100);
            $table->text('alamat')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('no_telp', 20);
            $table->timestamps();
        });

        Schema::create('managements', function (Blueprint $table) {
            $table->id('id_management');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_direktur', 100);
            $table->text('alamat')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('no_telp', 20);
            $table->timestamps();
        });

        Schema::create('murids', function (Blueprint $table) {
            $table->id('id_murid');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_murid', 100);
            $table->text('alamat')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('no_telp', 20);
            $table->timestamps();
        });

        // 3. Tabel Operasional
        Schema::create('cabangs', function (Blueprint $table) {
            $table->id('id_cabang');
            $table->string('nama_cabang', 100);
            $table->text('alamat_cabang');
            $table->string('no_telp', 20);
            $table->unsignedBigInteger('id_admin')->nullable();
            $table->unsignedBigInteger('id_unit')->nullable();
            $table->timestamps();

            $table->foreign('id_admin')->references('id_admin')->on('admins')->onDelete('set null');
            $table->foreign('id_unit')->references('id_unit')->on('unit_kendaraans')->onDelete('set null');
        });

        Schema::create('instrukturs', function (Blueprint $table) {
            $table->id('id_instruktur');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_instruktur', 100);
            $table->text('alamat')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('no_telp', 20);
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->unsignedBigInteger('id_unit')->nullable();
            $table->unsignedBigInteger('id_cabang')->nullable();
            $table->timestamps();

            $table->foreign('id_unit')->references('id_unit')->on('unit_kendaraans')->onDelete('set null');
            $table->foreign('id_cabang')->references('id_cabang')->on('cabangs')->onDelete('set null');
        });

        // 4. Tabel Transaksi & Jadwal
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->date('tanggal_transaksi');
            $table->string('jenis_transaksi', 100);
            $table->integer('jumlah_bayar');
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('id_murid')->nullable();
            $table->unsignedBigInteger('id_admin')->nullable();
            $table->unsignedBigInteger('id_package')->nullable();
            $table->timestamps();

            $table->foreign('id_murid')->references('id_murid')->on('murids')->onDelete('cascade');
            $table->foreign('id_admin')->references('id_admin')->on('admins')->onDelete('set null');
            $table->foreign('id_package')->references('id_package')->on('packages')->onDelete('set null');
        });

        Schema::create('jadwal_kursus', function (Blueprint $table) {
            $table->id('id_kursus');
            $table->dateTime('tanggal_kursus');
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('id_unit')->nullable();
            $table->unsignedBigInteger('id_murid')->nullable();
            $table->unsignedBigInteger('id_instruktur')->nullable();
            $table->unsignedBigInteger('id_package')->nullable();
            $table->timestamps();

            $table->foreign('id_unit')->references('id_unit')->on('unit_kendaraans')->onDelete('cascade');
            $table->foreign('id_murid')->references('id_murid')->on('murids')->onDelete('cascade');
            $table->foreign('id_instruktur')->references('id_instruktur')->on('instrukturs')->onDelete('cascade');
            $table->foreign('id_package')->references('id_package')->on('packages')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_kursus');
        Schema::dropIfExists('transaksis');
        Schema::dropIfExists('instrukturs');
        Schema::dropIfExists('cabangs');
        Schema::dropIfExists('murids');
        Schema::dropIfExists('managements');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('unit_kendaraans');
        Schema::dropIfExists('packages');
    }
};