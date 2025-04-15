<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VehicleTemplate;
use Illuminate\Http\Request;

class VehicleTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicleTemplates = VehicleTemplate::orderBy('name')->paginate(15);
        return view('admin.vehicle-templates.index', compact('vehicleTemplates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.vehicle-templates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manufacturer' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'type' => 'required|string|in:starfighter,transport,capital,speeder,walker,other',
            'size' => 'required|string|in:tiny,small,medium,large,huge,gargantuan',
            'crew_min' => 'required|integer|min:1',
            'crew_max' => 'nullable|integer|min:1',
            'passengers' => 'required|integer|min:0',
            'cargo_capacity' => 'required|integer|min:0',
            'consumables' => 'required|integer|min:0',
            'speed' => 'nullable|string|max:255',
            'hyperspace_speed' => 'nullable|string|max:255',
            'hull_points' => 'required|integer|min:1',
            'shield_points' => 'nullable|integer|min:0',
            'armor' => 'nullable|integer|min:0',
            'weapons' => 'nullable|string',
            'base_cost' => 'required|integer|min:0',
            'is_restricted' => 'boolean',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        VehicleTemplate::create($validated);

        return redirect()->route('admin.vehicle-templates.index')->with('success', 'Vehicle Template created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleTemplate $vehicleTemplate)
    {
        // Usually not needed for admin CRUD, often redirect to edit
        return redirect()->route('admin.vehicle-templates.edit', $vehicleTemplate);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VehicleTemplate $vehicleTemplate)
    {
        return view('admin.vehicle-templates.edit', compact('vehicleTemplate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VehicleTemplate $vehicleTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manufacturer' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'type' => 'required|string|in:starfighter,transport,capital,speeder,walker,other',
            'size' => 'required|string|in:tiny,small,medium,large,huge,gargantuan',
            'crew_min' => 'required|integer|min:1',
            'crew_max' => 'nullable|integer|min:1',
            'passengers' => 'required|integer|min:0',
            'cargo_capacity' => 'required|integer|min:0',
            'consumables' => 'required|integer|min:0',
            'speed' => 'nullable|string|max:255',
            'hyperspace_speed' => 'nullable|string|max:255',
            'hull_points' => 'required|integer|min:1',
            'shield_points' => 'nullable|integer|min:0',
            'armor' => 'nullable|integer|min:0',
            'weapons' => 'nullable|string',
            'base_cost' => 'required|integer|min:0',
            'is_restricted' => 'boolean',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $vehicleTemplate->update($validated);

        return redirect()->route('admin.vehicle-templates.index')->with('success', 'Vehicle Template updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleTemplate $vehicleTemplate)
    {
        // Add check for dependencies before deleting if necessary
        $vehicleTemplate->delete();
        return redirect()->route('admin.vehicle-templates.index')->with('success', 'Vehicle Template deleted successfully.');
    }
}
