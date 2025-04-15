<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Models\Attribute; // Assuming Attribute model exists for dropdown
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $skills = Skill::with('attribute')->orderBy('name')->paginate(15); // Eager load attribute, order by name, paginate
        return view('admin.skills.index', compact('skills'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $attributes = Attribute::orderBy('name')->get(); // Get attributes for dropdown
        return view('admin.skills.create', compact('attributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:skills,name',
            'code' => 'required|string|max:255|unique:skills,code|regex:/^[A-Z_]+$/', // Added code validation (unique, uppercase, underscores)
            'description' => 'nullable|string',
            'attribute_id' => 'required|exists:attributes,id',
        ]);

        Skill::create($validated);

        return redirect()->route('admin.skills.index')->with('success', 'Skill created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Skill $skill)
    {
        // Usually not needed for admin CRUD, often redirect to edit
        return redirect()->route('admin.skills.edit', $skill);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Skill $skill)
    {
        $attributes = Attribute::orderBy('name')->get(); // Get attributes for dropdown
        return view('admin.skills.edit', compact('skill', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Skill $skill)
    {
         $validated = $request->validate([
            'name' => 'required|string|max:255|unique:skills,name,' . $skill->id, // Ignore current skill ID for unique check
            'code' => 'required|string|max:255|unique:skills,code,' . $skill->id . '|regex:/^[A-Z_]+$/', // Added code validation
            'description' => 'nullable|string',
            'attribute_id' => 'required|exists:attributes,id',
        ]);

        $skill->update($validated);

        return redirect()->route('admin.skills.index')->with('success', 'Skill updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Skill $skill)
    {
        // Add check for dependencies (e.g., specializations, character skills) before deleting if necessary
        try {
            $skill->delete();
            return redirect()->route('admin.skills.index')->with('success', 'Skill deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle potential foreign key constraint violation
             return redirect()->route('admin.skills.index')->with('error', 'Could not delete skill. It might be in use.');
        }
    }
}
