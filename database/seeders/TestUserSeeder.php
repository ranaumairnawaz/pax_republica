<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'account_name' => 'testuser',
            'email' => 'test2@example.com',
            'timezone' => 'Asia/Karachi',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        $user->sendEmailVerificationNotification();
    }
}
