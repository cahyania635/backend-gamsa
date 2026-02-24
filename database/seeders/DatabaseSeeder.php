<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat akun Admin Default
        User::create([
            'name' => 'admin',
            'email' => 'admin@game.com',
            'password' => Hash::make('admin123'), // Password admin
        ]);
    }
}
