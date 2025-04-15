<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            [
                'name' => 'Strength',
                'description' => 'Physical power and muscle. Affects melee damage, carrying capacity, and physical feats.',
            ],
            [
                'name' => 'Dexterity',
                'description' => 'Agility, reflexes, and balance. Affects accuracy, dodging, and fine motor skills.',
            ],
            [
                'name' => 'Constitution',
                'description' => 'Health, stamina, and vital force. Affects hit points, endurance, and resistance to disease.',
            ],
            [
                'name' => 'Intelligence',
                'description' => 'Mental acuity, information recall, and analytical skill. Affects technical abilities and knowledge.',
            ],
            [
                'name' => 'Wisdom',
                'description' => 'Awareness, intuition, and insight. Affects perception and Force sensitivity.',
            ],
            [
                'name' => 'Charisma',
                'description' => 'Force of personality, persuasiveness, and leadership. Affects social interactions and influence.',
            ],
        ];

        foreach ($attributes as $attributeData) {
            $attributeData['code'] = strtolower(str_replace(' ', '_', $attributeData['name']));
            Attribute::create($attributeData);
        }
    }
}
