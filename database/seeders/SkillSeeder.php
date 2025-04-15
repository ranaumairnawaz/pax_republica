<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            // Strength-based skills (attribute_id: 1)
            [
                'name' => 'Melee Combat',
                'description' => 'Proficiency with melee weapons and hand-to-hand combat.',
                'attribute_id' => 1,
            ],
            [
                'name' => 'Athletics',
                'description' => 'Physical prowess in activities like running, jumping, and climbing.',
                'attribute_id' => 1,
            ],
            
            // Dexterity-based skills (attribute_id: 2)
            [
                'name' => 'Blaster',
                'description' => 'Proficiency with blaster weapons and other ranged energy weapons.',
                'attribute_id' => 2,
            ],
            [
                'name' => 'Piloting',
                'description' => 'Ability to operate starships, speeders, and other vehicles.',
                'attribute_id' => 2,
            ],
            [
                'name' => 'Stealth',
                'description' => 'Ability to move quietly and remain undetected.',
                'attribute_id' => 2,
            ],
            
            // Constitution-based skills (attribute_id: 3)
            [
                'name' => 'Endurance',
                'description' => 'Ability to withstand physical hardship and exertion.',
                'attribute_id' => 3,
            ],
            [
                'name' => 'Survival',
                'description' => 'Knowledge of how to survive in hostile environments.',
                'attribute_id' => 3,
            ],
            
            // Intelligence-based skills (attribute_id: 4)
            [
                'name' => 'Slicing',
                'description' => 'Ability to bypass computer security and access protected data.',
                'attribute_id' => 4,
            ],
            [
                'name' => 'Medicine',
                'description' => 'Knowledge of medical procedures and treatments.',
                'attribute_id' => 4,
            ],
            [
                'name' => 'Engineering',
                'description' => 'Ability to design, build, and repair mechanical and electronic devices.',
                'attribute_id' => 4,
            ],
            
            // Wisdom-based skills (attribute_id: 5)
            [
                'name' => 'Perception',
                'description' => 'Awareness of surroundings and ability to notice details.',
                'attribute_id' => 5,
            ],
            [
                'name' => 'Force Sensitivity',
                'description' => 'Ability to sense and connect with the Force.',
                'attribute_id' => 5,
            ],
            
            // Charisma-based skills (attribute_id: 6)
            [
                'name' => 'Persuasion',
                'description' => 'Ability to influence others through logical argument or personal charm.',
                'attribute_id' => 6,
            ],
            [
                'name' => 'Leadership',
                'description' => 'Ability to inspire and direct others effectively.',
                'attribute_id' => 6,
            ],
            [
                'name' => 'Deception',
                'description' => 'Ability to lie convincingly and conceal true intentions.',
                'attribute_id' => 6,
            ],
        ];

        foreach ($skills as $skillData) {
            $skillData['code'] = strtolower(str_replace(' ', '_', $skillData['name']));
            Skill::create($skillData);
        }
    }
}
