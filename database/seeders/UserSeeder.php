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
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => 'password',
            ]
        );

        // Create hasan user
        User::firstOrCreate(
            ['email' => 'hasan@example.com'],
            [
                'name' => 'Hasan Tahseen',
                'password' => 'password',
            ]
        );

        // Create test user
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        // Create regular users
        User::factory(10)->create();
    }
}
