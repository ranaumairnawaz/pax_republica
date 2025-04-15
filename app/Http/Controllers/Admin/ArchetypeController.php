<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Archetype;
use Illuminate\Http\Request;

class ArchetypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $archetypes = Archetype::orderBy('name')->paginate(15);
        return view('admin.archetypes.index', compact('archetypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.archetypes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:archetypes,name',
            'code' => 'required|string|max:255|unique:archetypes,code|regex:/^[A-Z_]+$/',
            'description' => 'nullable|string',
            'build_data' => 'nullable|json',
        ]);

        Archetype::create($validated);

        return redirect()->route('admin.archetypes.index')->with('success', 'Archetype created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Archetype $archetype)
    {
        // Usually not needed for admin CRUD, often redirect to edit
        return redirect()->route('admin.archetypes.edit', $archetype);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Archetype $archetype)
    {
        return view('admin.archetypes.edit', compact('archetype'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Archetype $archetype)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:archetypes,name,' . $archetype->id,
            'code' => 'required|string|max:255|unique:archetypes,code,' . $archetype->id . '|regex:/^[A-Z_]+$/',
            'description' => 'nullable|string',
            'build_data' => 'nullable|json',
        ]);

        $archetype->update($validated);

        return redirect()->route('admin.archetypes.index')->with('success', 'Archetype updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Archetype $archetype)
    {
        // Add check for dependencies before deleting if necessary
        $archetype->delete();
        return redirect()->route('admin.archetypes.index')->with('success', 'Archetype deleted successfully.');
    }
}
