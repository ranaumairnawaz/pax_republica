<?php

namespace Database\Seeders;

use App\Models\FactionRank;
use Illuminate\Database\Seeder;

class FactionRankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Galactic Republic Ranks (faction_id: 1)
        $republicRanks = [
            ['faction_id' => 1, 'name' => 'Chancellor', 'level' => 5],
            ['faction_id' => 1, 'name' => 'Senator', 'level' => 4],
            ['faction_id' => 1, 'name' => 'Diplomat', 'level' => 3],
            ['faction_id' => 1, 'name' => 'Officer', 'level' => 2],
            ['faction_id' => 1, 'name' => 'Citizen', 'level' => 1],
        ];

        // Confederacy of Independent Systems Ranks (faction_id: 2)
        $cisRanks = [
            ['faction_id' => 2, 'name' => 'Head of State', 'level' => 5],
            ['faction_id' => 2, 'name' => 'Council Member', 'level' => 4],
            ['faction_id' => 2, 'name' => 'General', 'level' => 3],
            ['faction_id' => 2, 'name' => 'Commander', 'level' => 2],
            ['faction_id' => 2, 'name' => 'Separatist', 'level' => 1],
        ];

        // Jedi Order Ranks (faction_id: 3)
        $jediRanks = [
            ['faction_id' => 3, 'name' => 'Grand Master', 'level' => 5],
            ['faction_id' => 3, 'name' => 'Jedi Master', 'level' => 4],
            ['faction_id' => 3, 'name' => 'Jedi Knight', 'level' => 3],
            ['faction_id' => 3, 'name' => 'Jedi Padawan', 'level' => 2],
            ['faction_id' => 3, 'name' => 'Jedi Initiate', 'level' => 1],
        ];

        // Sith Empire Ranks (faction_id: 4)
        $sithRanks = [
            ['faction_id' => 4, 'name' => 'Sith Emperor', 'level' => 5],
            ['faction_id' => 4, 'name' => 'Sith Lord', 'level' => 4],
            ['faction_id' => 4, 'name' => 'Sith Warrior', 'level' => 3],
            ['faction_id' => 4, 'name' => 'Sith Apprentice', 'level' => 2],
            ['faction_id' => 4, 'name' => 'Sith Acolyte', 'level' => 1],
        ];

        // Hutt Cartel Ranks (faction_id: 5)
        $huttRanks = [
            ['faction_id' => 5, 'name' => 'Hutt Lord', 'level' => 5],
            ['faction_id' => 5, 'name' => 'Cartel Boss', 'level' => 4],
            ['faction_id' => 5, 'name' => 'Enforcer', 'level' => 3],
            ['faction_id' => 5, 'name' => 'Smuggler', 'level' => 2],
            ['faction_id' => 5, 'name' => 'Associate', 'level' => 1],
        ];

        $allRanks = array_merge($republicRanks, $cisRanks, $jediRanks, $sithRanks, $huttRanks);

        foreach ($allRanks as $rankData) {
            FactionRank::create($rankData);
        }
    }
}