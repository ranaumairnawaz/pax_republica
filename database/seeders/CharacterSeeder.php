<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\CharacterAttribute;
use App\Models\CharacterSkill;
use App\Models\CharacterTrait;
use Illuminate\Database\Seeder;

class CharacterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a Jedi character for John Doe (user_id: 2)
        $jediCharacter = Character::create([
            'user_id' => 2,
            'name' => 'Kalen Marek',
            'status' => 'active',
            'xp' => 100,
            'details' => [
                'species_id' => 1, // Human
                'faction_id' => 3, // Jedi Order
                'rank_id' => 13, // Jedi Knight
                'age' => 28,
                'occupation' => 'Jedi Knight',
                'hair' => 'Brown',
                'eyes' => 'Blue',
                'height' => '1.85m',
                'profile' => 'A dedicated Jedi Knight who believes in justice and peace throughout the galaxy.',
                'background' => 'Born on Coruscant, Kalen was identified as Force-sensitive at a young age and brought to the Jedi Temple for training. He excelled in his studies and was particularly adept at lightsaber combat.',
                'picture_url' => 'https://example.com/kalen_marek.jpg',
                'tier' => 2,
                'is_force_user' => true,
                'banked_xp' => 5
            ],
            'approved_at' => now(),
            'submitted_at' => now(),
        ]);

        // Create a Smuggler character for Jane Smith (user_id: 3)
        $smugglerCharacter = Character::create([
            'user_id' => 3,
            'name' => 'Lyra Vex',
            'status' => 'active',
            'xp' => 75,
            'details' => [
                'species_id' => 2, // Twi'lek
                'faction_id' => 5, // Hutt Cartel
                'rank_id' => 22, // Smuggler
                'age' => 26,
                'occupation' => 'Smuggler',
                'hair' => 'N/A (Head tails)',
                'eyes' => 'Green',
                'height' => '1.7m',
                'profile' => 'A cunning smuggler with connections throughout the Outer Rim.',
                'background' => 'Born on Ryloth, Lyra escaped the harsh conditions of her homeworld by stowing away on a freighter. She learned to survive by her wits and eventually became a successful smuggler working for various Hutt crime lords.',
                'picture_url' => 'https://example.com/lyra_vex.jpg',
                'tier' => 1,
                'is_force_user' => false,
                'banked_xp' => 10
            ],
            'approved_at' => now(),
            'submitted_at' => now(),
        ]);

        // Create a Sith character for Alex Johnson (user_id: 4)
        $sithCharacter = Character::create([
            'user_id' => 4,
            'name' => 'Darth Malus',
            'status' => 'active',
            'xp' => 150,
            'details' => [
                'species_id' => 5, // Zabrak
                'faction_id' => 4, // Sith Empire
                'rank_id' => 19, // Sith Warrior
                'age' => 30,
                'occupation' => 'Sith Warrior',
                'hair' => 'Black',
                'eyes' => 'Yellow',
                'height' => '1.9m',
                'profile' => 'A fearsome Sith warrior who revels in combat and the dark side of the Force.',
                'background' => 'Raised in the Sith Academy on Korriban, Darth Malus embraced the dark side from an early age. His natural aggression and combat prowess quickly earned him a reputation as a formidable warrior.',
                'picture_url' => 'https://example.com/darth_malus.jpg',
                'tier' => 3,
                'is_force_user' => true,
                'banked_xp' => 0
            ],
            'approved_at' => now(),
            'submitted_at' => now(),
        ]);

        // Add character attributes, skills, and traits here
        // This would involve creating entries in the character_attributes, character_skills, and character_traits tables
        // For brevity, I'm not including all of these, but in a real implementation, you would add appropriate values for each character
    }
}