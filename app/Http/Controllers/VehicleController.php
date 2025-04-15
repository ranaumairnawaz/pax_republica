<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleTemplate;
use App\Models\ModTemplate;
use App\Models\Character;
use App\Models\VehicleMod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    /**
     * Display a listing of the vehicles.
     */
    public function index()
    {
        // Get all vehicles belonging to the user's characters
        $characterIds = Auth::user()->characters()->pluck('id');
        $vehicles = Vehicle::whereIn('character_id', $characterIds)
            ->with(['character', 'template'])
            ->orderBy('name')
            ->paginate(20);

        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new vehicle.
     */
    public function create()
    {
        $templates = VehicleTemplate::orderBy('name')->get();
        $characters = Auth::user()->characters()->where('status', 'active')->get();
        
        return view('vehicles.create', compact('templates', 'characters'));
    }

    /**
     * Store a newly created vehicle in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'character_id' => 'required|exists:characters,id',
            'vehicle_template_id' => 'required|exists:vehicle_templates,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'registration' => 'nullable|string|max:255',
            'current_location' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify the character belongs to the user
        $character = Character::findOrFail($request->character_id);
        if ($character->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Get template to set initial hull and shield points
        $template = VehicleTemplate::findOrFail($request->vehicle_template_id);

        // Create the vehicle
        $vehicle = new Vehicle($request->all());
        $vehicle->current_hull_points = $template->hull_points;
        $vehicle->current_shield_points = $template->shield_points;
        $vehicle->status = 'operational';
        $vehicle->save();

        return redirect()->route('vehicles.show', $vehicle)
            ->with('success', 'Vehicle created successfully.');
    }

    /**
     * Display the specified vehicle.
     */
    public function show(Vehicle $vehicle)
    {
        // Check if user can view this vehicle
        if (!$this->canAccessVehicle($vehicle)) {
            abort(403, 'Unauthorized action.');
        }

        $vehicle->load(['character.user', 'template', 'mods.template']);
        
        return view('vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified vehicle.
     */
    public function edit(Vehicle $vehicle)
    {
        // Check if user can edit this vehicle
        if (!$this->canAccessVehicle($vehicle)) {
            abort(403, 'Unauthorized action.');
        }

        $templates = VehicleTemplate::orderBy('name')->get();
        $characters = Auth::user()->characters()->where('status', 'active')->get();
        
        return view('vehicles.edit', compact('vehicle', 'templates', 'characters'));
    }

    /**
     * Update the specified vehicle in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        // Check if user can update this vehicle
        if (!$this->canAccessVehicle($vehicle)) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'character_id' => 'required|exists:characters,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'registration' => 'nullable|string|max:255',
            'current_location' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify the character belongs to the user
        $character = Character::findOrFail($request->character_id);
        if ($character->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Update the vehicle (don't allow changing template)
        $vehicle->fill($request->except('vehicle_template_id'));
        $vehicle->save();

        return redirect()->route('vehicles.show', $vehicle)
            ->with('success', 'Vehicle updated successfully.');
    }

    /**
     * Remove the specified vehicle from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        // Check if user can delete this vehicle
        if (!$this->canAccessVehicle($vehicle)) {
            abort(403, 'Unauthorized action.');
        }

        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }

    /**
     * Apply damage to the vehicle.
     */
    public function applyDamage(Request $request, Vehicle $vehicle)
    {
        // Check if user can modify this vehicle
        if (!$this->canAccessVehicle($vehicle)) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'damage' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $vehicle->applyDamage($request->damage);

        return redirect()->route('vehicles.show', $vehicle)
            ->with('success', 'Damage applied successfully.');
    }

    /**
     * Repair the vehicle.
     */
    public function repair(Request $request, Vehicle $vehicle)
    {
        // Check if user can modify this vehicle
        if (!$this->canAccessVehicle($vehicle)) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'hull_repair' => 'required|integer|min:0',
            'shield_repair' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $vehicle->repair($request->hull_repair, $request->shield_repair);

        return redirect()->route('vehicles.show', $vehicle)
            ->with('success', 'Repairs applied successfully.');
    }

    /**
     * Fully repair the vehicle.
     */
    public function fullRepair(Vehicle $vehicle)
    {
        // Check if user can modify this vehicle
        if (!$this->canAccessVehicle($vehicle)) {
            abort(403, 'Unauthorized action.');
        }

        $vehicle->fullRepair();

        return redirect()->route('vehicles.show', $vehicle)
            ->with('success', 'Vehicle fully repaired.');
    }

    /**
     * Show available mods to install.
     */
    public function showModsToInstall(Vehicle $vehicle)
    {
        // Check if user can modify this vehicle
        if (!$this->canAccessVehicle($vehicle)) {
            abort(403, 'Unauthorized action.');
        }

        $vehicle->load(['mods.template']);
        
        // Get all mods that aren't already installed on this vehicle
        $installedModIds = $vehicle->mods->pluck('mod_template_id')->toArray();
        $availableMods = ModTemplate::whereNotIn('id', $installedModIds)
            ->orderBy('name')
            ->get();

        return view('vehicles.mods', compact('vehicle', 'availableMods'));
    }

    /**
     * Install a mod on the vehicle.
     */
    public function installMod(Request $request, Vehicle $vehicle)
    {
        // Check if user can modify this vehicle
        if (!$this->canAccessVehicle($vehicle)) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'mod_template_id' => 'required|exists:mod_templates,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $mod = $vehicle->installMod($request->mod_template_id);

        if ($mod) {
            return redirect()->route('vehicles.show', $vehicle)
                ->with('success', 'Mod installed successfully.');
        } else {
            return redirect()->route('vehicles.mods', $vehicle)
                ->with('error', 'This mod is already installed on the vehicle.');
        }
    }

    /**
     * Remove a mod from the vehicle.
     */
    public function removeMod(Vehicle $vehicle, VehicleMod $mod)
    {
        // Check if user can modify this vehicle
        if (!$this->canAccessVehicle($vehicle)) {
            abort(403, 'Unauthorized action.');
        }

        // Check if the mod belongs to this vehicle
        if ($mod->vehicle_id !== $vehicle->id) {
            abort(400, 'Invalid request.');
        }

        $mod->remove();

        return redirect()->route('vehicles.show', $vehicle)
            ->with('success', 'Mod removed successfully.');
    }

    /**
     * Get vehicle template details (AJAX).
     */
    public function getTemplateDetails(Request $request)
    {
        $templateId = $request->input('template_id');
        $template = VehicleTemplate::findOrFail($templateId);
        
        return response()->json([
            'hull_points' => $template->hull_points,
            'shield_points' => $template->shield_points,
            'armor' => $template->armor,
            'speed' => $template->speed,
            'hyperspace_speed' => $template->hyperspace_speed,
            'crew' => $template->getFormattedCrew(),
            'passengers' => $template->passengers,
            'cargo_capacity' => $template->cargo_capacity,
            'base_cost' => $template->base_cost,
            'manufacturer' => $template->manufacturer,
            'model' => $template->model,
            'type' => $template->getFormattedType(),
            'size' => $template->getFormattedSize(),
        ]);
    }

    /**
     * Check if the authenticated user can access the vehicle.
     */
    private function canAccessVehicle(Vehicle $vehicle): bool
    {
        // Load relationship if not already loaded
        if (!$vehicle->relationLoaded('character')) {
            $vehicle->load('character');
        }

        // Check if the vehicle belongs to one of the user's characters
        return $vehicle->character && 
               $vehicle->character->user_id === Auth::id() || 
               Auth::user()->isAdmin();
    }
}
