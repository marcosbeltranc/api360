<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin API360',
            'email' => 'admin@api360.com',
            'password' => bcrypt('password'), // ¡Cámbiala después!
            'level' => 0,
        ]);
    }
}
