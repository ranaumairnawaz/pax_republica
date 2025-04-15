<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attribute;

class AttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            [
                'name' => 'Strength',
                'description' => 'Physical power and muscle capability.'
            ],
            [
                'name' => 'Dexterity',
                'description' => 'Agility, reflexes, and balance.'
            ],
            [
                'name' => 'Constitution',
                'description' => 'Health, stamina, and endurance.'
            ],
            [
                'name' => 'Intelligence',
                'description' => 'Mental capacity, memory, and reasoning.'
            ],
            [
                'name' => 'Wisdom',
                'description' => 'Intuition, perception, and insight.'
            ],
            [
                'name' => 'Charisma',
                'description' => 'Force of personality and leadership ability.'
            ],
        ];

        foreach ($attributes as $attribute) {
            Attribute::updateOrCreate(
                ['name' => $attribute['name']],
                $attribute
            );
        }
    }
}
