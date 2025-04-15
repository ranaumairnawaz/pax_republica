<?php

namespace Database\Seeders;

use App\Models\Specialization;
use Illuminate\Database\Seeder;

class SpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specializations = [
            // Melee Combat specializations (skill_id: 1)
            [
                'name' => 'Lightsaber Combat',
                'description' => 'Specialized training in the use of lightsabers.',
                'skill_id' => 1,
            ],
            [
                'name' => 'Martial Arts',
                'description' => 'Advanced hand-to-hand combat techniques.',
                'skill_id' => 1,
            ],
            
            // Blaster specializations (skill_id: 3)
            [
                'name' => 'Sniper',
                'description' => 'Specialized in long-range precision shooting.',
                'skill_id' => 3,
            ],
            [
                'name' => 'Dual Wielding',
                'description' => 'Proficiency in using two blasters simultaneously.',
                'skill_id' => 3,
            ],
            
            // Piloting specializations (skill_id: 4)
            [
                'name' => 'Starfighter Pilot',
                'description' => 'Specialized in piloting small combat spacecraft.',
                'skill_id' => 4,
            ],
            [
                'name' => 'Capital Ship Navigation',
                'description' => 'Expertise in navigating and piloting large vessels.',
                'skill_id' => 4,
            ],
            
            // Engineering specializations (skill_id: 10)
            [
                'name' => 'Droid Programming',
                'description' => 'Specialized knowledge in programming and modifying droids.',
                'skill_id' => 10,
            ],
            [
                'name' => 'Weapons Modification',
                'description' => 'Expertise in modifying and enhancing weapons systems.',
                'skill_id' => 10,
            ],
            
            // Force Sensitivity specializations (skill_id: 12)
            [
                'name' => 'Force Healing',
                'description' => 'Ability to use the Force to heal injuries and cure ailments.',
                'skill_id' => 12,
            ],
            [
                'name' => 'Telekinesis',
                'description' => 'Ability to move objects with the Force.',
                'skill_id' => 12,
            ],
        ];

        foreach ($specializations as $specializationData) {
            Specialization::create($specializationData);
        }
    }
}