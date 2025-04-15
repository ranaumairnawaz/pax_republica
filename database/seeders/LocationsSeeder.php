<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Coruscant - Senate District',
                'description' => 'The political heart of the Republic, home to the Senate building and many diplomatic residences.',
                'picture_url' => null,
            ],
            [
                'name' => 'Coruscant - Jedi Temple',
                'description' => 'The central headquarters of the Jedi Order, a place of learning and meditation.',
                'picture_url' => null,
            ],
            [
                'name' => 'Nar Shaddaa - Smugglers\' Moon',
                'description' => 'A lawless moon orbiting Nal Hutta, filled with criminals, bounty hunters, and those looking to disappear.',
                'picture_url' => null,
            ],
            [
                'name' => 'Korriban - Valley of the Dark Lords',
                'description' => 'The ancient homeworld of the Sith, filled with tombs and dark side energy.',
                'picture_url' => null,
            ],
            [
                'name' => 'Alderaan - Royal Palace',
                'description' => 'The beautiful seat of the royal family of Alderaan, known for its art and culture.',
                'picture_url' => null,
            ],
            [
                'name' => 'Tatooine - Mos Eisley Cantina',
                'description' => 'A wretched hive of scum and villainy, where spacers gather to drink and make deals.',
                'picture_url' => null,
            ],
        ];

        foreach ($locations as $location) {
            Location::updateOrCreate(
                ['name' => $location['name']],
                $location
            );
        }
    }
}
