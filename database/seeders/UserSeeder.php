<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => '12345678',
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@example.com'], 
            [
                'name' => 'Test',
                'password' => '12345678',
                'role' => 'user',
            ]
        );
    }
}
