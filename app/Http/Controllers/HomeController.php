<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Character;
use App\Models\Scene;
use App\Models\Location;
use App\Models\Species;
use App\Models\Faction;
use App\Models\Vehicle;
use App\Models\Attribute;
use App\Models\Skill;
use App\Models\Archetype;
use App\Models\FactionRank;
use App\Models\User; // Added for players search

class HomeController extends Controller
{
    public function index()
    {
        $pagetitle = 'Home';
        $slug = '';
        return view('frontend.index', compact('pagetitle', 'slug'));
    }

    // Inject Request object to get query parameters
    public function category(Request $request)
    {
        $searchQuery = $request->input('query'); // Get search query
        $slug = $request->input('slug'); // Get category slug
        $model = null;
        $viewName = '';
        $pagetitle = '';
        $searchField = 'name'; // Default search field

        switch ($slug) {
            case 'characters':
                $model = Character::query();
                $pagetitle = 'Characters';
                $viewName = 'frontend.characters';
                $searchField = 'name';

                // Apply search filter if query exists
                if (!empty($searchQuery)) {
                    $model->where($searchField, 'LIKE', "%{$searchQuery}%");
                }

                $data = $model->get();
                // Pass data and search query to the view
                return view($viewName, compact('data', 'pagetitle', 'slug', 'searchQuery'));
                break;
            case 'scenes':
                $model = Scene::query();
                $pagetitle = 'Scenes';
                $viewName = 'frontend.scenes';
                $searchField = 'title'; // Search by title for scenes

                // Apply search filter if query exists
                if (!empty($searchQuery)) {
                    $model->where($searchField, 'LIKE', "%{$searchQuery}%");
                }

                $data = $model->get();
                // Pass data and search query to the view
                return view($viewName, compact('data', 'pagetitle', 'slug', 'searchQuery'));
                break;
            case 'locations':
                $model = Location::query();
                $pagetitle = 'Locations';
                $viewName = 'frontend.locations';
                $searchField = 'name';

                // Apply search filter if query exists
                if (!empty($searchQuery)) {
                    $model->where($searchField, 'LIKE', "%{$searchQuery}%");
                }

                $data = $model->get();
                // Pass data and search query to the view
                return view($viewName, compact('data', 'pagetitle', 'slug', 'searchQuery'));
                break;
            case 'species':
                $model = Species::query();
                $pagetitle = 'Species';
                $viewName = 'frontend.species';
                $searchField = 'name';

                // Apply search filter if query exists
                if (!empty($searchQuery)) {
                    $model->where($searchField, 'LIKE', "%{$searchQuery}%");
                }

                $data = $model->get();
                // Pass data and search query to the view
                return view($viewName, compact('data', 'pagetitle', 'slug', 'searchQuery'));
                break;
            case 'factions':
                $model = Faction::query();
                $pagetitle = 'Factions';
                $viewName = 'frontend.factions';
                $searchField = 'name';

                // Apply search filter if query exists
                if (!empty($searchQuery)) {
                    $model->where($searchField, 'LIKE', "%{$searchQuery}%");
                }

                $data = $model->get();
                // Pass data and search query to the view
                return view($viewName, compact('data', 'pagetitle', 'slug', 'searchQuery'));
                break;
            case 'vehicles':
                $model = Vehicle::query();
                $pagetitle = 'Vehicles';
                $viewName = 'frontend.vehicles';
                $searchField = 'name'; // Assuming Vehicle model has 'name'

                // Apply search filter if query exists
                if (!empty($searchQuery)) {
                    $model->where($searchField, 'LIKE', "%{$searchQuery}%");
                }

                $data = $model->get();
                // Pass data and search query to the view
                return view($viewName, compact('data', 'pagetitle', 'slug', 'searchQuery'));
                break;
            case 'attributes':
                $model = Attribute::query();
                $pagetitle = 'Attributes';
                $viewName = 'frontend.attributes';
                $searchField = 'name';

                // Apply search filter if query exists
                if (!empty($searchQuery)) {
                    $model->where($searchField, 'LIKE', "%{$searchQuery}%");
                }

                $data = $model->get();
                // Pass data and search query to the view
                return view($viewName, compact('data', 'pagetitle', 'slug', 'searchQuery'));
                break;
            case 'skills':
                $model = Skill::query();
                $pagetitle = 'Skills';
                $viewName = 'frontend.skills';
                $searchField = 'name';

                // Apply search filter if query exists
                if (!empty($searchQuery)) {
                    $model->where($searchField, 'LIKE', "%{$searchQuery}%");
                }

                $data = $model->get();
                // Pass data and search query to the view
                return view($viewName, compact('data', 'pagetitle', 'slug', 'searchQuery'));
                break;
            case 'archetypes':
                $model = Archetype::query();
                $pagetitle = 'Archetypes';
                $viewName = 'frontend.archetypes';
                $searchField = 'name';

                // Apply search filter if query exists
                if (!empty($searchQuery)) {
                    $model->where($searchField, 'LIKE', "%{$searchQuery}%");
                }

                $data = $model->get();
                // Pass data and search query to the view
                return view($viewName, compact('data', 'pagetitle', 'slug', 'searchQuery'));
                break;
            case 'faction-ranks':
                $model = FactionRank::query();
                $pagetitle = 'Faction Ranks';
                $viewName = 'frontend.faction-ranks';
                $searchField = 'name';

                // Apply search filter if query exists
                if (!empty($searchQuery)) {
                    $model->where($searchField, 'LIKE', "%{$searchQuery}%");
                }

                $data = $model->get();
                // Pass data and search query to the view
                return view($viewName, compact('data', 'pagetitle', 'slug', 'searchQuery'));
                break;
            case 'players': // Added players search
                $model = User::query()->where('is_admin', 0); // Search only non-admin users
                $pagetitle = 'Players';
                $viewName = 'frontend.players'; // Assuming a view exists or will be created
                $searchField = 'name'; // Search by user name

                // Apply search filter if query exists
                if (!empty($searchQuery)) {
                    $model->where($searchField, 'LIKE', "%{$searchQuery}%");
                }

                $data = $model->get();
                // Pass data and search query to the view
                return view($viewName, compact('data', 'pagetitle', 'slug', 'searchQuery'));
                break;
            default:
                abort(404); // Category not found
        }

        // Pass data and search query to the view
        return view($viewName, compact('data', 'pagetitle', 'slug', 'searchQuery'));
    }

    public function categoryItem($slug, $id)
    {
         if ($slug == 'characters') {
            $character = Character::findOrFail($id);
            $pagetitle = $character->name;
            return view('frontend.character')->with(compact('character', 'pagetitle', 'slug'));
        } elseif ($slug == 'scenes') {
            $scene = Scene::findOrFail($id);
            $pagetitle = $scene->title;
            return view('frontend.scene')->with(compact('scene', 'pagetitle', 'slug'));
        } elseif ($slug == 'locations') {
            $location = Location::findOrFail($id);
            $pagetitle = $location->name;
            return view('frontend.location')->with(compact('location', 'pagetitle', 'slug'));
        } elseif ($slug == 'species') {
            $species = Species::findOrFail($id);
            $pagetitle = $species->name;
            return view('frontend.specie')->with(compact('species', 'pagetitle', 'slug'));
        } elseif ($slug == 'factions') {
            $faction = Faction::findOrFail($id);
            $pagetitle = $faction->name;
            return view('frontend.faction')->with(compact('faction', 'pagetitle', 'slug'));
        } elseif ($slug == 'vehicles') {
            $vehicle = Vehicle::findOrFail($id);
            $pagetitle = $vehicle->name;
            return view('frontend.vehicle')->with(compact('vehicle', 'pagetitle', 'slug'));
        } elseif ($slug == 'attributes') {
            $attribute = Attribute::findOrFail($id);
            $pagetitle = $attribute->name;
            return view('frontend.attribute')->with(compact('attribute', 'pagetitle', 'slug'));
        } elseif ($slug == 'skills') {
            $skill = Skill::findOrFail($id);
            $pagetitle = $skill->name;
            return view('frontend.skill')->with(compact('skill', 'pagetitle', 'slug'));
        } elseif ($slug == 'archetypes') {
            $archetype = Archetype::findOrFail($id);
            $pagetitle = $archetype->name;
            return view('frontend.archetype')->with(compact('archetype', 'pagetitle', 'slug'));
        } elseif ($slug == 'faction-ranks') {
            $factionRank = FactionRank::findOrFail($id);
            $pagetitle = $factionRank->name;
            return view('frontend.faction-rank')->with(compact('factionRank', 'pagetitle', 'slug'));
        }
    }
}