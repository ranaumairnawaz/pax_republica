<?php

use App\Models\Faction;
use App\Models\FactionRank;
use App\Models\Specialization;
use App\Models\Vehicle;
use App\Models\Attribute;
use App\Models\Skill;
use App\Models\Archetype;
use App\Models\Species;
use App\Models\Character;
use App\Models\Plot;


function getFaction($id){
    return Faction::find($id);
}

function getRank($id){
    return FactionRank::find($id);
}
function getSpecie($id){
    return Species::find($id);
}

function formatJsonToText($json)
{
    $output = '';
    $data = json_decode($json, true);

    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $output .= ucfirst($key) . ': ' . $value . '<br>';
        }
    }

    return $output;
}

function deSlug($input) {
    // Replace underscores with spaces
    $input = str_replace('_', ' ', $input);
    // Capitalize each word
    return ucwords($input);
}

