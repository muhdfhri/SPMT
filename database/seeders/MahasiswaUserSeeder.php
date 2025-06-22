<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MahasiswaUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Nama Mahasiswa',
            'email' => 'mahasiswa@example.com',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa',
        ]);
    }
}