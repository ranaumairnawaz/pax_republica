<?php

namespace Database\Seeders;

use App\Models\Species;
use Illuminate\Database\Seeder;

class SpeciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $species = [
            [
                'name' => 'Human',
                'description' => 'Humans are the most numerous and politically dominant species in the galaxy, with a range of appearances and cultures.',
                'modifiers' => json_encode(['adaptability' => 1]),
                'wiki_url' => 'https://starwars.fandom.com/wiki/Human',
            ],
            [
                'name' => 'Twi\'lek',
                'description' => 'Twi\'leks are a humanoid species with distinctive head tails called lekku. They come from the planet Ryloth.',
                'modifiers' => json_encode(['charisma' => 1]),
                'wiki_url' => 'https://starwars.fandom.com/wiki/Twi%27lek',
            ],
            [
                'name' => 'Wookiee',
                'description' => 'Wookiees are a tall, hairy, bipedal species from the planet Kashyyyk, known for their strength and loyalty.',
                'modifiers' => json_encode(['strength' => 2, 'technology' => -1]),
                'wiki_url' => 'https://starwars.fandom.com/wiki/Wookiee',
            ],
            [
                'name' => 'Rodian',
                'description' => 'Rodians are green-skinned humanoids from the planet Rodia, known for their hunting skills.',
                'modifiers' => json_encode(['perception' => 1]),
                'wiki_url' => 'https://starwars.fandom.com/wiki/Rodian',
            ],
            [
                'name' => 'Zabrak',
                'description' => 'Zabraks are a humanoid species known for their resilience and distinctive facial horns.',
                'modifiers' => json_encode(['endurance' => 1]),
                'wiki_url' => 'https://starwars.fandom.com/wiki/Zabrak',
            ],
        ];

        foreach ($species as $speciesData) {
            Species::create($speciesData);
        }
    }
}