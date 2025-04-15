<?php

namespace Database\Seeders;

use App\Models\Faction;
use Illuminate\Database\Seeder;

class FactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $factions = [
            [
                'name' => 'Galactic Republic',
                'description' => 'The democratic union that governed the galaxy before the rise of the Empire.',
                'color' => '#2E64FE',
                'picture_url' => 'https://static.wikia.nocookie.net/starwars/images/e/e0/Republic_Emblem.svg',
                'wiki_url' => 'https://starwars.fandom.com/wiki/Galactic_Republic',
            ],
            [
                'name' => 'Confederacy of Independent Systems',
                'description' => 'A separatist movement led by Count Dooku that opposed the Republic during the Clone Wars.',
                'color' => '#0B3861',
                'picture_url' => 'https://static.wikia.nocookie.net/starwars/images/3/35/CIS_roundel.svg',
                'wiki_url' => 'https://starwars.fandom.com/wiki/Confederacy_of_Independent_Systems',
            ],
            [
                'name' => 'Jedi Order',
                'description' => 'An ancient order of Force-sensitive guardians who served as peacekeepers in the Republic.',
                'color' => '#5FB404',
                'picture_url' => 'https://static.wikia.nocookie.net/starwars/images/9/9d/Jedi_symbol.svg',
                'wiki_url' => 'https://starwars.fandom.com/wiki/Jedi_Order',
            ],
            [
                'name' => 'Sith Empire',
                'description' => 'The ancient enemy of the Jedi Order, followers of the dark side of the Force.',
                'color' => '#B40404',
                'picture_url' => 'https://static.wikia.nocookie.net/starwars/images/7/7e/SithEmpireLogo.svg',
                'wiki_url' => 'https://starwars.fandom.com/wiki/Sith_Empire',
            ],
            [
                'name' => 'Hutt Cartel',
                'description' => 'A powerful crime syndicate controlled by the Hutt species.',
                'color' => '#886A08',
                'picture_url' => 'https://static.wikia.nocookie.net/starwars/images/f/f9/Hutt_Ruling_Council_logo.png',
                'wiki_url' => 'https://starwars.fandom.com/wiki/Hutt_Cartel',
            ],
        ];

        foreach ($factions as $factionData) {
            Faction::create($factionData);
        }
    }
}