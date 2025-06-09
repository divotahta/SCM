<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'nama' => 'Admin',
            'email' => 'admin@omahkulos.com',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'nama' => 'Owner',
            'email' => 'owner@omahkulos.com',
            'username' => 'owner',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);
    }
} 