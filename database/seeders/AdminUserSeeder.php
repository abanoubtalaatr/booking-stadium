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
        // Create admin user with specified credentials
        User::updateOrCreate(
            ['email' => 'safa@admin.com'],
            [
                'name' => 'Safa Admin',
                'email' => 'safa@admin.com',
                'password' => Hash::make('P@ssword12'),
                'email_verified_at' => now(),
            ]
        );
    }
}
