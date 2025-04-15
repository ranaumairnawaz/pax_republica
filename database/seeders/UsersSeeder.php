<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Character;
use App\Models\Species;
use App\Models\Attribute;
use App\Models\Skill;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'account_name' => 'tester',
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'timezone' => 'UTC',
                'user_type' => 'admin',
                'is_active' => true,
            ]
        );

        // Get a species for the character
        $species = Species::first();
        if (!$species) {
            $species = Species::create([
                'name' => 'Human',
                'description' => 'Humans are the most numerous and politically dominant species in the galaxy.',
            ]);
        }

        // Create a character for the user if they don't have one
        if ($user->characters()->count() === 0) {
            $character = Character::create([
                'name' => 'Test Character',
                'user_id' => $user->id,
                'species_id' => $species->id,
                'status' => Character::STATUS_ACTIVE,
                'details' => [
                    'biography' => 'This is a test character created by the seeder.'
                ],
                'approved_at' => now(),
            ]);

            // Attach some attributes to the character
            $attributes = Attribute::all();
            foreach ($attributes as $attribute) {
                $character->attributes()->attach($attribute->id, ['value' => 3]);
            }

            // Attach some skills to the character
            $skills = Skill::all();
            foreach ($skills as $skill) {
                $character->skills()->attach($skill->id, ['value' => 2]);
            }
        }
    }
}
