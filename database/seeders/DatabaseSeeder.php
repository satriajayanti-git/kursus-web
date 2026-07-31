<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Membuat Akun Login Admin
        $adminUser = User::create([
            'username' => 'admin_rakan',
            'email' => 'admin@satriajayanti.com',
            'password' => Hash::make('password123'), // Password yang akan kamu pakai login
            'role' => 'admin',
        ]);

        // 2. Mengisi Biodata ke Tabel Profil Admin
        DB::table('admins')->insert([
            'user_id' => $adminUser->id,
            'nama' => 'Rakan Wafi',
            'alamat' => 'Pondok Gede, Bekasi',
            'jenis_kelamin' => 'Laki-laki',
            'no_telp' => '081234567890',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('Akun Admin berhasil dibuat!');
    }
}