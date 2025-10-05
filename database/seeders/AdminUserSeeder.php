<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@whatsapp-provider.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // Create a demo user
        User::create([
            'name' => 'Demo User',
            'email' => 'demo@whatsapp-provider.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $this->command->info('Admin users created successfully!');
        $this->command->info('Email: admin@whatsapp-provider.com');
        $this->command->info('Password: password123');
    }
}
