<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faction;
use Illuminate\Http\Request;

class FactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $factions = Faction::orderBy('name')->paginate(15);
        return view('admin.factions.index', compact('factions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.factions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:factions,name',
            'code' => 'required|string|max:255|unique:factions,code|regex:/^[A-Z_]+$/',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7|regex:/^#([0-9a-f]{3}){1,2}$/i',
            '' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'wiki_url' => 'nullable|url',
        ]);

        Faction::create($validated);

        return redirect()->route('admin.factions.index')->with('success', 'Faction created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Faction $faction)
    {
        // Usually not needed for admin CRUD, often redirect to edit
        return redirect()->route('admin.factions.edit', $faction);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faction $faction)
    {
        return view('admin.factions.edit', compact('faction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faction $faction)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:factions,name,' . $faction->id,
            'code' => 'required|string|max:255|unique:factions,code,' . $faction->id . '|regex:/^[A-Z_]+$/',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7|regex:/^#([0-9a-f]{3}){1,2}$/i',
            '' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'wiki_url' => 'nullable|url',
        ]);

        $faction->update($validated);

        return redirect()->route('admin.factions.index')->with('success', 'Faction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faction $faction)
    {
        // Add check for dependencies before deleting if necessary
        $faction->delete();
        return redirect()->route('admin.factions.index')->with('success', 'Faction deleted successfully.');
    }
}
