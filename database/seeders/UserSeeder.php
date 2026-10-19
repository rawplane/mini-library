<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1 Admin
        User::firstOrCreate(
            ['email' => 'admin@perpus.test'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 1 Anggota Utama (untuk login testing mudah)
        User::firstOrCreate(
            ['email' => 'anggota@perpus.test'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'anggota',
                'email_verified_at' => now(),
            ]
        );

        // 9 Anggota Lainnya (total 10 anggota)
        User::factory()->count(9)->anggota()->create();
    }
}
