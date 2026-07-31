<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::create([
            'nama_website' => 'PT. Satria Jayanti',
            'hero_title' => 'Wujudkan Mimpimu Mengemudi',
            'hero_description' => 'Aman, nyaman, dan profesional bersama instruktur berpengalaman.',
            'no_telp' => '0812-3456-7890',
            'email' => 'info@satriajayanti.com',
            'alamat' => 'Jl. Jatiwaringin Raya, Pondok Gede, Bekasi',
            'instagram' => '@satriajayanti_kursus'
        ]);
    }
}