<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faction;
use App\Models\FactionRank;
use Illuminate\Http\Request;

class FactionRankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Faction $faction)
    {
        $ranks = $faction->ranks()->orderBy('level')->paginate(15);
        return view('admin.factions.ranks.index', compact('faction', 'ranks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Faction $faction)
    {
        return view('admin.factions.ranks.create', compact('faction'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Faction $faction)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer|min:1',
        ]);

        $faction->ranks()->create($validated);

        return redirect()->route('admin.factions.ranks.index', $faction)->with('success', 'Faction Rank created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faction $faction, FactionRank $rank)
    {
        return view('admin.factions.ranks.edit', compact('faction', 'rank'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faction $faction, FactionRank $rank)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer|min:1',
        ]);

        $rank->update($validated);

        return redirect()->route('admin.factions.ranks.index', $faction)->with('success', 'Faction Rank updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faction $faction, FactionRank $rank)
    {
        // Add check for dependencies before deleting if necessary
        $rank->delete();
        return redirect()->route('admin.factions.ranks.index', $faction)->with('success', 'Faction Rank deleted successfully.');
    }
}
