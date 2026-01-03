<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a default user compatible with current users table (username exists, name column removed)
        User::firstOrCreate([
            'email' => 'test@example.com'
        ], [
            'username' => 'admin',
            'password' => Hash::make('password'),
        ]);

        $this->call(AkunSeeder::class);
        // Demo/test data for dashboard and functional testing
        $this->call(DemoDataSeeder::class);
    }
}
