<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TraitModel;
use Illuminate\Http\Request;

class TraitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $traitModels = TraitModel::orderBy('name')->paginate(15);
        return view('admin.traits.index', compact('traitModels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.traits.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:traitmodels,name',
            'code' => 'required|string|max:255|unique:traitmodels,code|regex:/^[A-Z_]+$/',
            'description' => 'nullable|string',
            'modifiers' => 'nullable|json',
        ]);

        TraitModel::create($validated);

        return redirect()->route('admin.traits.index')->with('success', 'Trait created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TraitModel $trait)
    {
        // Usually not needed for admin CRUD, often redirect to edit
        return redirect()->route('admin.traits.edit', $trait);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TraitModel $trait)
    {
        return view('admin.traits.edit', compact('trait'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TraitModel $trait)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:traitmodels,name,' . $trait->id,
            'code' => 'required|string|max:255|unique:traitmodels,code,' . $trait->id . '|regex:/^[A-Z_]+$/',
            'description' => 'nullable|string',
            'modifiers' => 'nullable|json',
        ]);

        $trait->update($validated);

        return redirect()->route('admin.traits.index')->with('success', 'Trait updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TraitModel $trait)
    {
        // Add check for dependencies before deleting if necessary
        $trait->delete();
        return redirect()->route('admin.traits.index')->with('success', 'Trait deleted successfully.');
    }
}
