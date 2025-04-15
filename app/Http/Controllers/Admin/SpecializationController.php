<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialization;
use App\Models\Skill; // Assuming Skill model exists for dropdown
use Illuminate\Http\Request;

class SpecializationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $specializations = Specialization::with('skill')->orderBy('name')->paginate(15); // Eager load skill, order by name, paginate
        return view('admin.specializations.index', compact('specializations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $skills = Skill::orderBy('name')->get(); // Get skills for dropdown
        return view('admin.specializations.create', compact('skills'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:specializations,name',
            'code' => 'required|string|max:255|unique:specializations,code|regex:/^[A-Z_]+$/',
            'description' => 'nullable|string',
            'skill_id' => 'required|exists:skills,id',
            'xp_cost' => 'required|integer|min:0',
            'restricted' => 'sometimes|boolean',
        ]);

        Specialization::create($validated);

        return redirect()->route('admin.specializations.index')->with('success', 'Specialization created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Specialization $specialization)
    {
        // Usually not needed for admin CRUD, often redirect to edit
        return redirect()->route('admin.specializations.edit', $specialization);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Specialization $specialization)
    {
        $skills = Skill::orderBy('name')->get(); // Get skills for dropdown
        return view('admin.specializations.edit', compact('specialization', 'skills'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Specialization $specialization)
    {
         $validated = $request->validate([
            'name' => 'required|string|max:255|unique:specializations,name,' . $specialization->id, // Ignore current specialization ID for unique check
            'code' => 'required|string|max:255|unique:specializations,code,' . $specialization->id . '|regex:/^[A-Z_]+$/',
            'description' => 'nullable|string',
            'skill_id' => 'required|exists:skills,id',
            'xp_cost' => 'required|integer|min:0',
            'restricted' => 'sometimes|boolean',
        ]);

        $specialization->update($validated);

        return redirect()->route('admin.specializations.index')->with('success', 'Specialization updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialization $specialization)
    {
        // Add check for dependencies before deleting if necessary
        $specialization->delete();
        return redirect()->route('admin.specializations.index')->with('success', 'Specialization deleted successfully.');
    }
}
