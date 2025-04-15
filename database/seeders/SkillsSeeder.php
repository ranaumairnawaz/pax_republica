<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Skill;
use App\Models\Attribute;

class SkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get attributes for linking
        $strength = Attribute::where('name', 'Strength')->first();
        $dexterity = Attribute::where('name', 'Dexterity')->first();
        $constitution = Attribute::where('name', 'Constitution')->first();
        $intelligence = Attribute::where('name', 'Intelligence')->first();
        $wisdom = Attribute::where('name', 'Wisdom')->first();
        $charisma = Attribute::where('name', 'Charisma')->first();

        // Define skills with their linked attributes
        $skills = [
            [
                'name' => 'Melee Combat',
                'description' => 'Proficiency with melee weapons and hand-to-hand combat.',
                'attribute_id' => $strength ? $strength->id : null
            ],
            [
                'name' => 'Athletics',
                'description' => 'Physical prowess in activities like running, jumping, and climbing.',
                'attribute_id' => $strength ? $strength->id : null
            ],
            [
                'name' => 'Stealth',
                'description' => 'Ability to move quietly and remain undetected.',
                'attribute_id' => $dexterity ? $dexterity->id : null
            ],
            [
                'name' => 'Survival',
                'description' => 'Knowledge of how to survive in hostile environments.',
                'attribute_id' => $wisdom ? $wisdom->id : null
            ],
            [
                'name' => 'Engineering',
                'description' => 'Ability to design, build, and repair mechanical and electronic devices.',
                'attribute_id' => $intelligence ? $intelligence->id : null
            ],
            [
                'name' => 'Force Sensitivity',
                'description' => 'Ability to sense and connect with the Force.',
                'attribute_id' => $wisdom ? $wisdom->id : null
            ],
            [
                'name' => 'Leadership',
                'description' => 'Ability to inspire and direct others effectively.',
                'attribute_id' => $charisma ? $charisma->id : null
            ],
        ];

        foreach ($skills as $skill) {
            Skill::updateOrCreate(
                ['name' => $skill['name']],
                $skill
            );
        }
    }
}
