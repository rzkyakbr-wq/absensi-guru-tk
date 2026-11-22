<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun Admin
        User::create([
            'name'     => 'Admin TK Aisyiyah 06',
            'email'    => 'admin@tkaisyiyah06.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        // Akun Guru
        User::create([
            'name'     => 'Guru Aisyiyah',
            'email'    => 'guru@tkaisyiyah06.com',
            'password' => Hash::make('password123'),
            'role'     => 'guru',
        ]);
    }
}