<?php

namespace App\Http\Controllers;

use App\Models\Npc;
use App\Models\Species;
use App\Models\Faction;
use App\Models\FactionRank;
use App\Models\Attribute;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NpcController extends Controller
{
    /**
     * Display a listing of the NPCs.
     */
    public function index()
    {
        // Get all public NPCs and the user's own NPCs
        $npcs = Npc::where('is_public', true)
            ->orWhere('user_id', Auth::id())
            ->with(['species', 'faction', 'factionRank'])
            ->orderBy('name')
            ->paginate(20);

        return view('npcs.index', compact('npcs'));
    }

    /**
     * Show the form for creating a new NPC.
     */
    public function create()
    {
        $species = Species::orderBy('name')->get();
        $factions = Faction::orderBy('name')->get();
        $attributes = Attribute::orderBy('name')->get();
        $skills = Skill::with('attribute')->orderBy('name')->get();
        
        return view('npcs.create', compact('species', 'factions', 'attributes', 'skills'));
    }

    /**
     * Store a newly created NPC in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'species_id' => 'nullable|exists:species,id',
            'faction_id' => 'nullable|exists:factions,id',
            'faction_rank_id' => 'nullable|exists:faction_ranks,id',
            'occupation' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'appearance' => 'nullable|string',
            'current_location' => 'nullable|string|max:255',
            'importance' => 'required|in:minor,supporting,major',
            'is_public' => 'boolean',
            'image_url' => 'nullable|url',
            'attributes' => 'nullable|array',
            'attributes.*' => 'integer|min:1|max:10',
            'skills' => 'nullable|array',
            'skills.*' => 'integer|min:0|max:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create the NPC
        $npc = new Npc($request->except(['attributes', 'skills']));
        $npc->user_id = Auth::id();
        $npc->is_public = $request->has('is_public');
        $npc->save();

        // Sync attributes
        if ($request->has('attributes')) {
            foreach ($request->attributes as $attributeId => $value) {
                $npc->setAttribute($attributeId, $value);
            }
        }

        // Sync skills
        if ($request->has('skills')) {
            foreach ($request->skills as $skillId => $value) {
                $npc->setSkill($skillId, $value);
            }
        }

        return redirect()->route('npcs.show', $npc)
            ->with('success', 'NPC created successfully.');
    }

    /**
     * Display the specified NPC.
     */
    public function show(Npc $npc)
    {
        // Check if user can view this NPC
        if (!$npc->is_public && $npc->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $npc->load(['species', 'faction', 'factionRank', 'attributes', 'skills', 'scenes']);
        
        // Group skills by attribute
        $skillsByAttribute = [];
        foreach ($npc->skills as $skill) {
            $attributeName = $skill->attribute->name;
            if (!isset($skillsByAttribute[$attributeName])) {
                $skillsByAttribute[$attributeName] = [];
            }
            $skillsByAttribute[$attributeName][] = $skill;
        }

        return view('npcs.show', compact('npc', 'skillsByAttribute'));
    }

    /**
     * Show the form for editing the specified NPC.
     */
    public function edit(Npc $npc)
    {
        // Check if user can edit this NPC
        if ($npc->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $species = Species::orderBy('name')->get();
        $factions = Faction::orderBy('name')->get();
        $factionRanks = $npc->faction_id ? 
            FactionRank::where('faction_id', $npc->faction_id)->orderBy('level')->get() : 
            collect();
        $attributes = Attribute::orderBy('name')->get();
        $skills = Skill::with('attribute')->orderBy('name')->get();
        
        // Get attribute and skill values
        $attributeValues = [];
        foreach ($npc->attributes as $attribute) {
            $attributeValues[$attribute->id] = $attribute->pivot->value;
        }
        
        $skillValues = [];
        foreach ($npc->skills as $skill) {
            $skillValues[$skill->id] = $skill->pivot->value;
        }

        return view('npcs.edit', compact(
            'npc', 'species', 'factions', 'factionRanks', 
            'attributes', 'skills', 'attributeValues', 'skillValues'
        ));
    }

    /**
     * Update the specified NPC in storage.
     */
    public function update(Request $request, Npc $npc)
    {
        // Check if user can update this NPC
        if ($npc->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'species_id' => 'nullable|exists:species,id',
            'faction_id' => 'nullable|exists:factions,id',
            'faction_rank_id' => 'nullable|exists:faction_ranks,id',
            'occupation' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'appearance' => 'nullable|string',
            'current_location' => 'nullable|string|max:255',
            'importance' => 'required|in:minor,supporting,major',
            'is_public' => 'boolean',
            'image_url' => 'nullable|url',
            'attributes' => 'nullable|array',
            'attributes.*' => 'integer|min:1|max:10',
            'skills' => 'nullable|array',
            'skills.*' => 'integer|min:0|max:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update the NPC
        $npc->fill($request->except(['attributes', 'skills']));
        $npc->is_public = $request->has('is_public');
        $npc->save();

        // Sync attributes
        if ($request->has('attributes')) {
            foreach ($request->attributes as $attributeId => $value) {
                $npc->setAttribute($attributeId, $value);
            }
        }

        // Sync skills
        if ($request->has('skills')) {
            foreach ($request->skills as $skillId => $value) {
                $npc->setSkill($skillId, $value);
            }
        }

        return redirect()->route('npcs.show', $npc)
            ->with('success', 'NPC updated successfully.');
    }

    /**
     * Remove the specified NPC from storage.
     */
    public function destroy(Npc $npc)
    {
        // Check if user can delete this NPC
        if ($npc->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $npc->delete();

        return redirect()->route('npcs.index')
            ->with('success', 'NPC deleted successfully.');
    }

    /**
     * Get faction ranks for a specific faction (AJAX).
     */
    public function getFactionRanks(Request $request)
    {
        $factionId = $request->input('faction_id');
        $ranks = FactionRank::where('faction_id', $factionId)
            ->orderBy('level')
            ->get(['id', 'name', 'level']);
        
        return response()->json($ranks);
    }
}
