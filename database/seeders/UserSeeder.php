<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'account_name' => 'admin',
            'email' => 'admin@paxrepublica.net',
            'timezone' => 'UTC',
            'user_type' => 'admin',
            'real_name' => 'System Administrator',
            'sex' => 'M',
            'age' => 30,
            'profile' => 'System administrator for Pax Republica.',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create player users
        $players = [
            [
                'name' => 'John Doe',
                'account_name' => 'johndoe',
                'email' => 'john@example.com',
                'timezone' => 'America/New_York',
                'user_type' => 'player',
                'real_name' => 'John Doe',
                'sex' => 'M',
                'age' => 25,
                'profile' => 'Star Wars fan and roleplayer.',
            ],
            [
                'name' => 'Jane Smith',
                'account_name' => 'jsmith',
                'email' => 'jane@example.com',
                'timezone' => 'Europe/London',
                'user_type' => 'player',
                'real_name' => 'Jane Smith',
                'sex' => 'F',
                'age' => 28,
                'profile' => 'Sci-fi enthusiast and writer.',
            ],
            [
                'name' => 'Alex Johnson',
                'account_name' => 'alexj',
                'email' => 'alex@example.com',
                'timezone' => 'Asia/Tokyo',
                'user_type' => 'player',
                'real_name' => 'Alex Johnson',
                'sex' => 'M',
                'age' => 32,
                'profile' => 'Long-time Star Wars roleplayer.',
            ],
        ];

        foreach ($players as $playerData) {
            // Add common fields to all players
            $playerData['password'] = Hash::make('password');
            $playerData['email_verified_at'] = now();
            
            User::create($playerData);
        }
    }
}