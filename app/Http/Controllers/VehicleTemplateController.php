<?php

namespace App\Http\Controllers;

use App\Models\VehicleTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VehicleTemplateController extends Controller
{
    /**
     * Display a listing of the vehicle templates.
     */
    public function index()
    {
        $templates = VehicleTemplate::orderBy('name')
            ->paginate(20);

        return view('vehicle-templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new vehicle template.
     */
    public function create()
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        return view('vehicle-templates.create');
    }

    /**
     * Store a newly created vehicle template in storage.
     */
    public function store(Request $request)
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manufacturer' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'type' => 'required|string|in:speeder,starfighter,transport,capital,walker,other',
            'size' => 'required|string|in:tiny,small,medium,large,huge,gargantuan',
            'crew_min' => 'required|integer|min:0',
            'crew_max' => 'nullable|integer|min:0',
            'passengers' => 'required|integer|min:0',
            'cargo_capacity' => 'required|integer|min:0',
            'consumables' => 'required|integer|min:0',
            'speed' => 'nullable|string|max:255',
            'hyperspace_speed' => 'nullable|string|max:255',
            'hull_points' => 'required|integer|min:1',
            'shield_points' => 'required|integer|min:0',
            'armor' => 'required|integer|min:0',
            'weapons' => 'nullable|string',
            'base_cost' => 'required|integer|min:0',
            'is_restricted' => 'boolean',
            'image_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create the vehicle template
        $template = new VehicleTemplate($request->all());
        $template->is_restricted = $request->has('is_restricted');
        $template->save();

        return redirect()->route('vehicle-templates.show', $template)
            ->with('success', 'Vehicle template created successfully.');
    }

    /**
     * Display the specified vehicle template.
     */
    public function show(VehicleTemplate $vehicleTemplate)
    {
        // If it's restricted, only admins can view it
        if ($vehicleTemplate->is_restricted && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        return view('vehicle-templates.show', compact('vehicleTemplate'));
    }

    /**
     * Show the form for editing the specified vehicle template.
     */
    public function edit(VehicleTemplate $vehicleTemplate)
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        return view('vehicle-templates.edit', compact('vehicleTemplate'));
    }

    /**
     * Update the specified vehicle template in storage.
     */
    public function update(Request $request, VehicleTemplate $vehicleTemplate)
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manufacturer' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'type' => 'required|string|in:speeder,starfighter,transport,capital,walker,other',
            'size' => 'required|string|in:tiny,small,medium,large,huge,gargantuan',
            'crew_min' => 'required|integer|min:0',
            'crew_max' => 'nullable|integer|min:0',
            'passengers' => 'required|integer|min:0',
            'cargo_capacity' => 'required|integer|min:0',
            'consumables' => 'required|integer|min:0',
            'speed' => 'nullable|string|max:255',
            'hyperspace_speed' => 'nullable|string|max:255',
            'hull_points' => 'required|integer|min:1',
            'shield_points' => 'required|integer|min:0',
            'armor' => 'required|integer|min:0',
            'weapons' => 'nullable|string',
            'base_cost' => 'required|integer|min:0',
            'is_restricted' => 'boolean',
            'image_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update the vehicle template
        $vehicleTemplate->fill($request->all());
        $vehicleTemplate->is_restricted = $request->has('is_restricted');
        $vehicleTemplate->save();

        return redirect()->route('vehicle-templates.show', $vehicleTemplate)
            ->with('success', 'Vehicle template updated successfully.');
    }

    /**
     * Remove the specified vehicle template from storage.
     */
    public function destroy(VehicleTemplate $vehicleTemplate)
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if there are vehicles using this template
        if ($vehicleTemplate->vehicles()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete template that is in use by vehicles.');
        }

        $vehicleTemplate->delete();

        return redirect()->route('vehicle-templates.index')
            ->with('success', 'Vehicle template deleted successfully.');
    }
}
