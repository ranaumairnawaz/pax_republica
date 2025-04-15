<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use App\Models\User;
use Database\Seeders\DatabaseResetSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call DatabaseResetSeeder to drop all tables and run migrations
        // $this->call(DatabaseResetSeeder::class);

        // Call seeders in the correct order to maintain relationships
        $this->call([
            // Base game data
            SpeciesSeeder::class,
            AttributeSeeder::class,
            SkillSeeder::class,
            SpecializationSeeder::class,
            TraitSeeder::class,
            
            // Faction data
            FactionSeeder::class,
            FactionRankSeeder::class,
            
            // User and character data
            UserSeeder::class,
            CharacterSeeder::class,
        ]);

        $this->call(TestUserSeeder::class);

        // Run our attribute seeders first, then skills (which depend on attributes)
        $this->call([
            // AttributesSeeder::class,
            SkillsSeeder::class,
            LocationsSeeder::class,
        ]);
        
        // Create a test user if needed
        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'account_name' => 'tester',
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }
    }
}
