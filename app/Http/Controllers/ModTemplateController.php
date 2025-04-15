<?php

namespace App\Http\Controllers;

use App\Models\ModTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ModTemplateController extends Controller
{
    /**
     * Display a listing of the mod templates.
     */
    public function index()
    {
        $templates = ModTemplate::orderBy('name')
            ->paginate(20);

        return view('mod-templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new mod template.
     */
    public function create()
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        return view('mod-templates.create');
    }

    /**
     * Store a newly created mod template in storage.
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
            'type' => 'required|string|in:weapon,defense,propulsion,sensor,utility,special',
            'installation_difficulty' => 'required|integer|min:1|max:4',
            'cost' => 'required|integer|min:0',
            'is_restricted' => 'boolean',
            'hull_points' => 'nullable|integer',
            'shield_points' => 'nullable|integer',
            'armor' => 'nullable|integer',
            'speed' => 'nullable|integer',
            'hyperspace_speed' => 'nullable|integer',
            'damage' => 'nullable|integer',
            'sensor_range' => 'nullable|integer',
            'cargo_capacity' => 'nullable|integer',
            'other_effects' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Process effects into JSON
        $effects = $this->processEffects($request);

        // Create the mod template
        $template = new ModTemplate([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'effects' => $effects,
            'installation_difficulty' => $request->installation_difficulty,
            'cost' => $request->cost,
        ]);
        $template->is_restricted = $request->has('is_restricted');
        $template->save();

        return redirect()->route('mod-templates.show', $template)
            ->with('success', 'Mod template created successfully.');
    }

    /**
     * Display the specified mod template.
     */
    public function show(ModTemplate $modTemplate)
    {
        // If it's restricted, only admins can view it
        if ($modTemplate->is_restricted && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        return view('mod-templates.show', compact('modTemplate'));
    }

    /**
     * Show the form for editing the specified mod template.
     */
    public function edit(ModTemplate $modTemplate)
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        return view('mod-templates.edit', compact('modTemplate'));
    }

    /**
     * Update the specified mod template in storage.
     */
    public function update(Request $request, ModTemplate $modTemplate)
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:weapon,defense,propulsion,sensor,utility,special',
            'installation_difficulty' => 'required|integer|min:1|max:4',
            'cost' => 'required|integer|min:0',
            'is_restricted' => 'boolean',
            'hull_points' => 'nullable|integer',
            'shield_points' => 'nullable|integer',
            'armor' => 'nullable|integer',
            'speed' => 'nullable|integer',
            'hyperspace_speed' => 'nullable|integer',
            'damage' => 'nullable|integer',
            'sensor_range' => 'nullable|integer',
            'cargo_capacity' => 'nullable|integer',
            'other_effects' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Process effects into JSON
        $effects = $this->processEffects($request);

        // Update the mod template
        $modTemplate->fill([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'effects' => $effects,
            'installation_difficulty' => $request->installation_difficulty,
            'cost' => $request->cost,
        ]);
        $modTemplate->is_restricted = $request->has('is_restricted');
        $modTemplate->save();

        return redirect()->route('mod-templates.show', $modTemplate)
            ->with('success', 'Mod template updated successfully.');
    }

    /**
     * Remove the specified mod template from storage.
     */
    public function destroy(ModTemplate $modTemplate)
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if there are vehicle mods using this template
        if ($modTemplate->mods()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete template that is in use by vehicles.');
        }

        $modTemplate->delete();

        return redirect()->route('mod-templates.index')
            ->with('success', 'Mod template deleted successfully.');
    }

    /**
     * Process form data into effects JSON.
     */
    private function processEffects(Request $request): array
    {
        $effects = [];
        
        // Add numeric effects
        $numericFields = [
            'hull_points', 'shield_points', 'armor', 'speed', 
            'hyperspace_speed', 'damage', 'sensor_range', 'cargo_capacity'
        ];
        
        foreach ($numericFields as $field) {
            if ($request->has($field) && $request->$field !== null) {
                $effects[$field] = (int) $request->$field;
            }
        }
        
        // Add other effects as notes
        if ($request->has('other_effects') && !empty($request->other_effects)) {
            $effects['notes'] = $request->other_effects;
}
