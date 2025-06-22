<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@spmt.test'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'), // Default password is 'password'
                'role' => 'admin',
                'email_verified_at' => now(),
                'phone' => '081234567890',
            ]
        );

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@spmt.test');
        $this->command->info('Password: password');
    }
}
