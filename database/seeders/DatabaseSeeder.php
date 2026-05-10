<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    // Bikin 1 Akun Admin buat admin login
    \App\Models\User::factory()->create([
        'nama' => 'Admin Utama',
        'username' => 'admin',
        'email' => 'admin@gmail.com',
        'level' => 'admin', // 
    ]);

    // Bikin 1 Akun Bendahara
    \App\Models\User::factory()->create([
        'nama' => 'Bendahara Kelas',
        'username' => 'bendahara',
        'email' => 'bendahara@gmail.com',
        'level' => 'bendahara', 
    ]);

    // Sisanya bikin 10 user random (campuran guru/bendahara/admin)
    \App\Models\User::factory(10)->create();
    }
}
