<?php

namespace App\Http\Controllers;

use App\Models\Faction;
use Illuminate\Http\Request;

class FactionController extends Controller
{
    /**
     * Display a listing of the factions.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $factions = Faction::orderBy('name')->get();
        return view('factions.index', compact('factions'));
    }

    /**
     * Display the specified faction.
     *
     * @param  \App\Models\Faction  $faction
     * @return \Illuminate\View\View
     */
    public function show(Faction $faction)
    {
        // Eager load ranks and members (characters)
        $faction->load(['ranks' => function ($query) {
            $query->orderBy('level');
        }, 'members' => function ($query) {
            $query->where('status', \App\Models\Character::STATUS_ACTIVE)->orderBy('name');
        }]);

        return view('factions.show', compact('faction'));
    }

    // --- Admin Methods (Placeholders) ---

    /**
     * Show the form for creating a new faction.
     */
    public function create()
    {
        return view('factions.create');
    }

    /**
     * Store a newly created faction in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $faction = Faction::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('factions.show', $faction)
            ->with('success', 'Faction created successfully.');
    }

    /**
     * Show the form for editing the specified faction. (Admin only)
     */
    public function edit(Faction $faction)
    {
        // TODO: Implement admin authorization
        abort(501); // Not Implemented
    }

    /**
     * Update the specified faction in storage. (Admin only)
     */
    public function update(Request $request, Faction $faction)
    {
        // TODO: Implement admin authorization and validation/update
        abort(501); // Not Implemented
    }

    /**
     * Remove the specified faction from storage. (Admin only)
     */
    public function destroy(Faction $faction)
    {
        // TODO: Implement admin authorization and deletion
        abort(501); // Not Implemented
    }
}
