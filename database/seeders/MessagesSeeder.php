<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Message;
use Carbon\Carbon;

class MessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the admin user and another test user
        $admin = User::where('email', 'test@example.com')->first();
        
        if (!$admin) {
            $this->command->info('Admin user not found. Please run UsersSeeder first.');
            return;
        }
        
        // Create a second test user if not exists
        $testUser = User::where('email', 'player@example.com')->first();
        
        if (!$testUser) {
            $testUser = User::create([
                'name' => 'Test Player',
                'account_name' => 'player',
                'email' => 'player@example.com',
                'password' => bcrypt('password'),
                'timezone' => 'UTC',
                'user_type' => 'player',
                'is_active' => true,
            ]);
            $this->command->info('Created test player user.');
        }
        
        // Create some test messages
        
        // Message 1: Admin to Player
        Message::create([
            'sender_id' => $admin->id,
            'recipient_id' => $testUser->id,
            'subject' => 'Welcome to Pax Republica',
            'content' => "Hello and welcome to Pax Republica!\n\nWe're excited to have you join our Star Wars roleplaying community. If you have any questions, feel free to message me directly.\n\nMay the Force be with you!",
            'created_at' => Carbon::now()->subDays(5),
        ]);
        
        // Message 2: Player to Admin
        Message::create([
            'sender_id' => $testUser->id,
            'recipient_id' => $admin->id,
            'subject' => 'Re: Welcome to Pax Republica',
            'content' => "Thank you for the warm welcome!\n\nI'm excited to get started. I'm working on my character concept now and will submit it soon for approval.\n\nBest regards,\nPlayer",
            'created_at' => Carbon::now()->subDays(4),
            'is_read' => true,
            'read_at' => Carbon::now()->subDays(3),
        ]);
        
        // Message 3: Admin to Player
        Message::create([
            'sender_id' => $admin->id,
            'recipient_id' => $testUser->id,
            'subject' => 'New Scene Opening',
            'content' => "Greetings!\n\nI wanted to let you know that we're opening a new scene on Coruscant next week. It will be a diplomatic mission with potential for interesting character development.\n\nWould you be interested in participating with your character?",
            'created_at' => Carbon::now()->subDays(2),
            'is_read' => false,
        ]);
        
        // Message 4: Admin to Player (unread)
        Message::create([
            'sender_id' => $admin->id,
            'recipient_id' => $testUser->id,
            'subject' => 'Character Approval',
            'content' => "Great news!\n\nYour character has been approved and is ready for play. You can now join scenes and start roleplaying.\n\nLooking forward to seeing you in-game!",
            'created_at' => Carbon::now()->subHours(12),
            'is_read' => false,
        ]);
        
        $this->command->info('Created test messages between users.');
    }
}
