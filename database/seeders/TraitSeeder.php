<?php

namespace Database\Seeders;

use App\Models\CharacterTrait;
use Illuminate\Database\Seeder;

class TraitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $traits = [
            [
                'name' => 'Force Sensitive',
                'description' => 'Character has a natural connection to the Force, allowing them to sense and potentially manipulate it.',
                'modifiers' => json_encode(['force_sensitivity' => 2]),
            ],
            [
                'name' => 'Natural Leader',
                'description' => 'Character has an innate ability to inspire and lead others.',
                'modifiers' => json_encode(['leadership' => 1, 'persuasion' => 1]),
            ],
            [
                'name' => 'Tech Savvy',
                'description' => 'Character has a natural aptitude for technology and mechanical systems.',
                'modifiers' => json_encode(['engineering' => 1, 'slicing' => 1]),
            ],
            [
                'name' => 'Combat Reflexes',
                'description' => 'Character has enhanced reaction time in combat situations.',
                'modifiers' => json_encode(['initiative' => 1, 'dodge' => 1]),
            ],
            [
                'name' => 'Diplomat',
                'description' => 'Character is skilled in negotiation and diplomatic relations.',
                'modifiers' => json_encode(['persuasion' => 1, 'deception' => 1]),
            ],
            [
                'name' => 'Tough',
                'description' => 'Character has increased physical resilience and stamina.',
                'modifiers' => json_encode(['endurance' => 1, 'health' => 5]),
            ],
            [
                'name' => 'Quick Learner',
                'description' => 'Character learns new skills and abilities more quickly than others.',
                'modifiers' => json_encode(['xp_gain' => 0.1]),
            ],
        ];

        foreach ($traits as $traitData) {
            CharacterTrait::create($traitData);
        }
    }
}