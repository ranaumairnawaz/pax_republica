<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModTemplate;
use Illuminate\Http\Request;

class ModTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modTemplates = ModTemplate::orderBy('name')->paginate(15);
        return view('admin.mod-templates.index', compact('modTemplates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.mod-templates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:weapon,defense,propulsion,sensor,utility,special',
            'effects' => 'required|json',
            'is_restricted' => 'boolean',
            'cost' => 'required|integer|min:0',
            'installation_difficulty' => 'required|integer|min:1|max:4',
        ]);

        ModTemplate::create($validated);

        return redirect()->route('admin.mod-templates.index')->with('success', 'Mod Template created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ModTemplate $modTemplate)
    {
        // Usually not needed for admin CRUD, often redirect to edit
        return redirect()->route('admin.mod-templates.edit', $modTemplate);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ModTemplate $modTemplate)
    {
        return view('admin.mod-templates.edit', compact('modTemplate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ModTemplate $modTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:weapon,defense,propulsion,sensor,utility,special',
            'effects' => 'required|json',
            'is_restricted' => 'boolean',
            'cost' => 'required|integer|min:0',
            'installation_difficulty' => 'required|integer|min:1|max:4',
        ]);

        $modTemplate->update($validated);

        return redirect()->route('admin.mod-templates.index')->with('success', 'Mod Template updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ModTemplate $modTemplate)
    {
        // Add check for dependencies before deleting if necessary
        $modTemplate->delete();
        return redirect()->route('admin.mod-templates.index')->with('success', 'Mod Template deleted successfully.');
    }
}
