<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Membuat Akun Admin
        User::create([
            'username' => 'admin_rakan',
            'nama_lengkap' => 'Rakan Wafi',
            'email' => 'admin@satriajayanti.com',
            'password' => Hash::make('password123'),
            'alamat' => 'Pondok Gede, Bekasi',
            'no_telp' => '081234567890',
            'role' => 'admin',
            'status' => 'Aktif', // Penting agar akun langsung bisa dipakai login
        ]);

        // 2. Membuat Akun Management
        User::create([
            'username' => 'manajer_ops',
            'nama_lengkap' => 'Manajer Operasional',
            'email' => 'management@satriajayanti.com',
            'password' => Hash::make('password123'),
            'role' => 'management',
            'status' => 'Aktif',
        ]);

        $this->command->info('Akun Admin dan Management berhasil dibuat!');
    }
}