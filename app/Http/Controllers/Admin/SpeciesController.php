<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Species;
use Illuminate\Http\Request;

class SpeciesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $species = Species::orderBy('name')->paginate(15);
        return view('admin.species.index', compact('species'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.species.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:species,name',
            'code' => 'required|string|max:255|unique:species,code|regex:/^[A-Z_]+$/',
            'description' => 'nullable|string',
            'modifiers' => 'nullable|json',
            'wiki_url' => 'nullable|url',
        ]);

        Species::create($validated);

        return redirect()->route('admin.species.index')->with('success', 'Species created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Species $species)
    {
        // Usually not needed for admin CRUD, often redirect to edit
        return redirect()->route('admin.species.edit', $species);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Species $species)
    {
        return view('admin.species.edit', compact('species'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Species $species)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:species,name,' . $species->id,
            'code' => 'required|string|max:255|unique:species,code,' . $species->id . '|regex:/^[A-Z_]+$/',
            'description' => 'nullable|string',
            'modifiers' => 'nullable|json',
            'wiki_url' => 'nullable|url',
        ]);

        $species->update($validated);

        return redirect()->route('admin.species.index')->with('success', 'Species updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Species $species)
    {
        // Add check for dependencies before deleting if necessary
        $species->delete();
        return redirect()->route('admin.species.index')->with('success', 'Species deleted successfully.');
    }
}
