<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ManagementSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nama_lengkap' => 'Direktur Utama',
            'username' => 'management',
            'email' => 'management@satriajayanti.com',
            'password' => Hash::make('password123'), // Password buat login
            'no_telp' => '081234567890',
            'role' => 'management',
            // Management gak butuh branch_id karena dia megang semua cabang
        ]);
    }
}