<?php

namespace App\Http\Controllers;

use App\Models\Scene;
use App\Models\Location;
use App\Models\Character;
use App\Models\Plot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\DiceRollingService;

class SceneController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the scenes.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get active scenes
        $activeScenes = Scene::active()
            ->orderBy('started_at', 'desc')
            ->get();
        
        // Get planning scenes
        $planningScenes = Scene::planning()
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get completed scenes
        $completedScenes = Scene::completed()
            ->orderBy('ended_at', 'desc')
            ->limit(10)
            ->get();

        $scenes = Scene::orderBy('created_at', 'desc')->get();
            
        return view('scenes.index', compact('scenes', 'activeScenes', 'planningScenes', 'completedScenes'));
    }

    /**
     * Show the form for creating a new scene.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Get all locations
        $locations = Location::orderBy('name')->get();
        
        // Get user's active characters for scene creation
        $characters = Auth::user()->characters()->active()->get();

        // Get all plots
        $plots = Plot::orderBy('title')->get();

        if ($characters->isEmpty()) {
            return redirect()->route('characters.index')
                ->with('warning', 'You need an active character to create a scene.');
        }

        return view('scenes.create', compact('locations', 'characters', 'plots'));
    }

    /**
     * Store a newly created scene in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'plot_id' => ['nullable', 'exists:plots,id'],
            'location_id' => ['required', 'exists:locations,id'],
            'character_id' => ['required', 'exists:characters,id'], // Creator's character
            'participant_ids' => ['required', 'array', 'min:1'], // At least one other participant
            'participant_ids.*' => ['required', 'exists:characters,id'], // Each participant must exist
            'is_private' => ['boolean'],
            'synopsis' => ['required', 'string'], // Use synopsis field
        ]);

        // Verify the creator's character belongs to the user
        $creatorCharacter = Character::findOrFail($request->character_id);
        if ($creatorCharacter->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Verify selected participants are active and not the creator's character
        $participantIds = collect($request->participant_ids)->unique()->filter(function ($id) use ($creatorCharacter) {
            return $id != $creatorCharacter->id; // Ensure creator is not in participants list
        })->all();

        if (empty($participantIds)) {
             return back()->withErrors(['participant_ids' => 'You must select at least one other participant.'])->withInput();
        }

        $participants = Character::whereIn('id', $participantIds)->where('status', 'ACTIVE')->get();
        if ($participants->count() !== count($participantIds)) {
            return back()->withErrors(['participant_ids' => 'One or more selected participants are invalid or not active.'])->withInput();
        }

        // Create the scene
        $scene = Scene::create([
            'title' => $request->title,
            'synopsis' => $request->synopsis, // Use synopsis
            'plot_id' => $request->plot_id,
            'location_id' => $request->location_id,
            'creator_id' => Auth::id(),
            'creator_character_id' => $creatorCharacter->id,
            'is_private' => $request->has('is_private'),
            'status' => Scene::STATUS_ACTIVE, // Set status to ACTIVE
            'last_activity_at' => now(),
            'started_at' => now(), // Set started_at time
        ]);

        // Add the creator as a participant
        $scene->participants()->attach($creatorCharacter->id, ['joined_at' => now()]);
        // Add other selected participants
        $scene->participants()->attach($participantIds, ['joined_at' => now()]);


        return redirect()->route('scenes.show', $scene)
            ->with('success', 'Scene created successfully and is now active.');
    }

    /**
     * Display the specified scene.
     *
     * @param  \App\Models\Scene  $scene
     * @return \Illuminate\View\View
     */
    public function show(Scene $scene)
    {
        // Eager load relationships
        $scene->load(['location', 'participants.user', 'posts.character']);
        
        // Get the user's active characters for posting
        $userCharacters = Auth::user()->characters()->active()->get();

        // Check if the user has a character participating in the scene
        $isParticipating = $scene->participants()
            ->whereIn('character_id', $userCharacters->pluck('id'))
            ->exists();

        $userHasJoinedScene = false;
        if (auth()->check()) {
            $userHasJoinedScene = $scene->participants()->where('user_id', auth()->user()->id)->exists();
        }

        return view('scenes.show', compact('scene', 'userCharacters', 'isParticipating', 'userHasJoinedScene'));
    }

    /**
     * Show the form for editing the specified scene.
     *
     * @param  \App\Models\Scene  $scene
     * @return \Illuminate\View\View
     */
    public function edit(Scene $scene)
    {
        // Only allow editing if the scene is in planning stage
        if (!$scene->isPlanning()) {
            return redirect()->route('scenes.show', $scene)
                ->with('error', 'Only scenes in planning stage can be edited.');
        }

        // Get all locations
        $locations = Location::orderBy('name')->get();
        $plots = Plot::orderBy('title')->get();

        return view('scenes.edit', compact('scene', 'locations', 'plots'));
    }

    /**
     * Roll dice for a character in a scene.
     *
     * @param Request $request
     * @param Scene $scene
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rollDice(Request $request, Scene $scene)
    {
        $request->validate([
            'character_id' => 'required|exists:characters,id',
            'rollable_type' => 'required|in:attributes,skills,specializations',
            'rollable_id' => 'required|integer',
            'modifier' => 'nullable|integer',
        ]);

        $character = Character::findOrFail($request->character_id);

        // Verify the character belongs to the user and is participating in the scene
        if ($character->user_id !== Auth::id() || !$scene->participants()->where('character_id', $character->id)->exists()) {
            abort(403, 'Unauthorized action.');
        }

        $diceRollingService = new DiceRollingService();

        try {
            $rollResult = $diceRollingService->roll(
                $character,
                $request->rollable_type,
                $request->rollable_id,
                $request->modifier ?? 0
            );

            $message = "{$character->name} rolled {$rollResult['rollable']->name}: {$rollResult['roll']} + {$rollResult['modifier']} = {$rollResult['total']}";
            session()->flash('success', $message);

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }

        return redirect()->route('scenes.show', $scene);
    }

    /**
     * Update the specified scene in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Scene  $scene
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Scene $scene)
    {
        $this->authorize('update', $scene);

        // Only allow updating if the scene is in planning stage
        if (!$scene->isPlanning()) {
            return redirect()->route('scenes.show', $scene)
                ->with('error', 'Only scenes in planning stage can be updated.');
        }
        
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'plot_id' => ['nullable', 'exists:plots,id'],
            'location_id' => ['required', 'exists:locations,id'],
            'is_private' => ['boolean'],
        ]);

        $scene->update([
            'title' => $request->title,
            'description' => $request->description,
            'synopsis' => $request->description, // For backward compatibility
            'plot_id' => $request->plot_id,
            'location_id' => $request->location_id,
            'is_private' => $request->has('is_private'),
            'last_activity_at' => now(),
        ]);

        return redirect()->route('scenes.show', $scene)
            ->with('success', 'Scene updated successfully.');
    }

    /**
     * Start the scene.
     *
     * @param  \App\Models\Scene  $scene
     * @return \Illuminate\Http\RedirectResponse
     */
    public function start(Scene $scene)
    {
        // Check if the scene can be started
        if (!$scene->isPlanning()) {
            return redirect()->route('scenes.show', $scene)
                ->with('error', 'Only scenes in planning stage can be started.');
        }
        
        if ($scene->start()) {
            return redirect()->route('scenes.show', $scene)
                ->with('success', 'Scene started successfully.');
        }
        
        return redirect()->route('scenes.show', $scene)
            ->with('error', 'Unable to start the scene.');
    }

    /**
     * Complete the scene.
     *
     * @param  \App\Models\Scene  $scene

    /**
     * Join the scene with a character.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Scene  $scene
     * @return \Illuminate\Http\RedirectResponse
     */
    public function join(Request $request, Scene $scene)
    {
        $request->validate([
            'character_id' => ['required', 'exists:characters,id'],
        ]);
        
        // Verify the character belongs to the user
        $character = Character::findOrFail($request->character_id);
        $this->authorize('use', $character);
        
        // Check if the character is already participating
        if ($scene->participants()->where('character_id', $character->id)->exists()) {
            return redirect()->route('scenes.show', $scene)
                ->with('info', 'This character is already participating in the scene.');
        }
        
        // Add the character as a participant
        $scene->participants()->attach($character->id, ['joined_at' => now()]);
        
        return redirect()->route('scenes.show', $scene)
            ->with('success', 'Joined the scene successfully.');
    }

    /**
     * Leave the scene with a character.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Scene  $scene
     * @return \Illuminate\Http\RedirectResponse
     */
    public function leave(Request $request, Scene $scene)
    {
        $request->validate([
            'character_id' => ['required', 'exists:characters,id'],
        ]);
        
        // Verify the character belongs to the user
        $character = Character::findOrFail($request->character_id);
        $this->authorize('use', $character);
        
        // Remove the character from participants
        $scene->participants()->detach($character->id);
        
        return redirect()->route('scenes.show', $scene)
            ->with('success', 'Left the scene successfully.');
    }

    /**
     * Vote for a character in the scene.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Scene  $scene
     * @return \Illuminate\Http\RedirectResponse
     */
    public function vote(Request $request, Scene $scene)
    {
        $request->validate([
            'voted_character_id' => ['required', 'exists:characters,id'],
        ]);

        // Verify the user is participating in the scene
        $character = Auth::user()->characters()->find($request->character_id);
        if (!$character || !$scene->participants()->where('character_id', $character->id)->exists()) {
            abort(403, 'Unauthorized action.');
        }

        // Verify the voted character is participating in the scene
        if (!$scene->participants()->where('character_id', $request->voted_character_id)->exists()) {
            return redirect()->route('scenes.show', $scene)
                ->with('error', 'Invalid character to vote for.');
        }

        // Create the vote
        $vote = new \App\Models\Vote();
        $vote->scene_id = $scene->id;
        $vote->voter_character_id = $character->id;
        $vote->voted_character_id = $request->voted_character_id;
        $vote->save();

        return redirect()->route('scenes.show', $scene)
            ->with('success', 'Vote cast successfully.');
    }

    /**
     * Complete the scene.
     *
     * @param  \App\Models\Scene  $scene
     * @return \Illuminate\Http\RedirectResponse
     */
    public function complete(Scene $scene)
    {
        $this->authorize('manage', $scene);

        if (!$scene->isActive()) {
            return redirect()->route('scenes.show', $scene)
                ->with('error', 'Only active scenes can be completed.');
        }

        if ($scene->complete()) {
            return redirect()->route('scenes.show', $scene)
                ->with('success', 'Scene completed successfully.');
        }

        return redirect()->route('scenes.show', $scene)
            ->with('error', 'Unable to complete the scene.');
    }
}
