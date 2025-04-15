<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class CharacterController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the user's characters.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $characters = Auth::user()->characters;
        return view('characters.index', compact('characters'));
    }

    /**
     * Show the form for creating a new character.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $species = \App\Models\Species::all();
        $attributes = \App\Models\Attribute::all();
        $skills = \App\Models\Skill::all();
        return view('characters.create', compact('species', 'attributes', 'skills'));
    }

    /**
     * Store a newly created character in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Base validation
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'species_id' => ['required', 'exists:species,id'],
            'biography' => ['nullable', 'string'],
            'attributes' => ['required', 'array'],
            'skills' => ['required', 'array'],
        ]);

        // Additional validation for attribute IDs and values
        foreach ($request->input('attributes') as $attributeId => $value) {
            $request->validate([
                "attributes" => [function ($attribute, $value, $fail) use ($attributeId) {
                    if (!is_numeric($attributeId) || !\App\Models\Attribute::where('id', $attributeId)->exists()) {
                        $fail("Invalid attribute ID submitted: {$attributeId}.");
                    }
                }],
                "attributes.{$attributeId}" => ['required', 'numeric', 'min:1', 'max:5'],
            ], [
                "attributes.{$attributeId}.required" => "Attribute value for ID {$attributeId} is required.",
                "attributes.{$attributeId}.numeric" => "Attribute value for ID {$attributeId} must be a number.",
                "attributes.{$attributeId}.min" => "Attribute value must be at least 1.",
                "attributes.{$attributeId}.max" => "Attribute value for ID {$attributeId} cannot exceed 5.",
            ]);
        }

        // Additional validation for skill IDs and values
        foreach ($request->input('skills') as $skillId => $value) {
            $request->validate([
                "skills" => [function ($attribute, $value, $fail) use ($skillId) {
                    if (!is_numeric($skillId) || !\App\Models\Skill::where('id', $skillId)->exists()) {
                        $fail("Invalid skill ID submitted: {$skillId}.");
                    }
                }],
                "skills.{$skillId}" => ['required', 'numeric', 'min:0', 'max:3'],
            ], [
                "skills.{$skillId}.required" => "Skill value for ID {$skillId} is required.",
                "skills.{$skillId}.numeric" => "Skill value for ID {$skillId} must be a number.",
                "skills.{$skillId}.min" => "Skill value must be at least 0.",
                "skills.{$skillId}.max" => "Skill value for ID {$skillId} cannot exceed 3.",
            ]);
        }

        \Log::info('Attempting to create character with attributes and skills:', [
            'attributes' => $request->input('attributes'),
            'skills' => $request->input('skills')
        ]);

        $character = null;

        try {
            DB::transaction(function () use ($request, &$character) {
                // Create the character
                $character = Character::create([
                    'name' => $request->name,
                    'user_id' => Auth::id(),
                    'species_id' => $request->species_id,
                    'biography' => $request->biography,
                    'status' => Character::STATUS_INPROGRESS,
                    'xp' => 0,
                    'level' => 1
                ]);

                if (!$character) {
                    throw new \Exception("Character creation failed.");
                }

                $attributeInserts = [];
                $skillInserts = [];
                $now = now()->toDateTimeString();

                foreach ($request->input('attributes') as $attributeId => $value) {
                    if (is_numeric($attributeId) && is_numeric($value) && $value > 0) {
                        $attributeInserts[] = [
                            'character_id' => $character->id,
                            'attribute_id' => $attributeId,
                            'value' => $value,
                            'xp_spent' => 0,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }

                foreach ($request->input('skills') as $skillId => $value) {
                    if (is_numeric($skillId) && is_numeric($value)) {
                        $skillInserts[] = [
                            'character_id' => $character->id,
                            'skill_id' => $skillId,
                            'value' => $value,
                            'xp_spent' => 0,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }

                if (!empty($attributeInserts)) {
                    DB::table('character_attributes')->insert($attributeInserts);
                }
                if (!empty($skillInserts)) {
                    DB::table('character_skills')->insert($skillInserts);
                }

                \Log::info('Character and pivot data inserted successfully.', [
                    'character_id' => $character->id
                ]);
            });

        } catch (\Throwable $e) {
            \Log::error('Character creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()->with('error', 'Failed to create character due to a database error. Please try again.');
        }

        if (!$character) {
            \Log::error('Character object is null after transaction.');
            return back()->withInput()->with('error', 'An unexpected error occurred during character creation.');
        }

        return redirect()->route('characters.show', $character)
            ->with('success', 'Character created successfully.');
    }


    /**
     * Display the specified character.
     *
     * @param  \App\Models\Character  $character
     * @return \Illuminate\View\View
     */
    public function show(Character $character)
    {
        $this->authorize('view', $character);
        return view('characters.show', compact('character'));
    }

    /**
     * Show the form for editing the specified character.
     *
     * @param  \App\Models\Character  $character
     * @return \Illuminate\View\View
     */
    public function edit(Character $character)
    {
        $species = \App\Models\Species::all();
        $attributes = \App\Models\Attribute::all();
        $skills = \App\Models\Skill::all();
        $this->authorize('update', $character);
        return view('characters.edit', compact('character', 'species', 'attributes', 'skills'));
    }

    /**
     * Update the specified character in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Character  $character
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Character $character)
    {
        $this->authorize('update', $character);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'species_id' => ['required', 'exists:species,id'],
            'biography' => ['nullable', 'string'],
            'attributes' => ['required', 'array'],
            'skills' => ['required', 'array'],
        ]);

        // Additional validation for attribute values
        foreach ($request->attributes as $attributeId => $value) {
            $request->validate([
                "attributes.{$attributeId}" => ['required', 'numeric', 'min:1', 'max:5'],
            ], [
                "attributes.{$attributeId}.required" => "Attribute value for ID {$attributeId} is required.",
                "attributes.{$attributeId}.numeric" => "Attribute value for ID {$attributeId} must be a number.",
                "attributes.{$attributeId}.min" => "Attribute value must be at least 1.",
                "attributes.{$attributeId}.max" => "Attribute value cannot exceed 5.",
            ]);
        }

        // Additional validation for skill values
        foreach ($request->skills as $skillId => $value) {
            $request->validate([
                "skills.{$skillId}" => ['required', 'numeric', 'min:0', 'max:3'],
            ], [
                "skills.{$skillId}.required" => "Skill value is required.",
                "skills.{$skillId}.numeric" => "Skill value must be a number.",
                "skills.{$skillId}.min" => "Skill value must be at least 0.",
                "skills.{$skillId}.max" => "Skill value cannot exceed 3.",
            ]);
        }

        $character->update([
            'name' => $request->name,
            'species_id' => $request->species_id,
            'biography' => $request->biography,
        ]);

        // Update character attributes
        $character->attributes()->detach();
        foreach ($request->attributes as $attributeId => $value) {
            if (is_numeric($attributeId) && is_numeric($value) && $value > 0) {
                $character->attributes()->attach($attributeId, [
                    'value' => $value,
                    'xp_spent' => 0
                ]);
            }
        }

        // Update character skills
        $character->skills()->detach();
        foreach ($request->skills as $skillId => $value) {
            if (is_numeric($skillId) && is_numeric($value)) {
                $character->skills()->attach($skillId, [
                    'value' => $value,
                    'xp_spent' => 0
                ]);
            }
        }

        return redirect()->route('characters.show', $character)
            ->with('success', 'Character updated successfully.');
    }

    /**
     * Submit the character for approval.
     *
     * @param  \App\Models\Character  $character
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit(Character $character)
    {
        $this->authorize('submit', $character);

        if ($character->submit()) {
            // TODO: Create a job for admin review

            return redirect()->route('characters.show', $character)
                ->with('success', 'Character submitted for approval.');
        }

        return back()->with('error', 'Character could not be submitted. It may already be pending or approved.');
    }

    /**
     * Approve the character (admin only).
     *
     * @param  \App\Models\Character  $character
     * @return \Illuminate\View\View
     */
    public function approve(Character $character)
    {
        $this->authorize('approve', $character);

        if ($character->approve()) {
            // TODO: Update the related job

            return redirect()->route('characters.show', $character)
                ->with('success', 'Character approved successfully.');
        }

        return back()->with('error', 'Character could not be approved. It may not be in pending status.');
    }

    /**
     * Reject the character (admin only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Character  $character
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, Character $character)
    {
        $this->authorize('approve', $character); // Use the same authorization as approve

        $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10'],
        ]);

        if ($character->status !== Character::STATUS_PENDING) {
            return back()->with('error', 'Character cannot be rejected. It is not in pending status.');
        }

        // Option 1: Delete the character
        // $character->delete();
        // return redirect()->route('dashboard.admin')->with('success', 'Character rejected and deleted.');

        // Option 2: Set status back to in-progress (allowing user to edit and resubmit)
        $character->status = Character::STATUS_INPROGRESS;
        $character->submitted_at = null;
        // TODO: Notify the user with the rejection reason
        // $character->user->notify(new CharacterRejectedNotification($character, $request->rejection_reason));
        $character->save();

        // TODO: Update the related job if applicable

        return redirect()->route('dashboard.admin')
            ->with('success', 'Character rejected and returned to player for edits.');
    }

    /**
     * Spend XP to increase an attribute value.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Character  $character
     * @return \Illuminate\Http\RedirectResponse
     */
    public function spendXP(Request $request, Character $character)
    {
        $this->authorize('update', $character); // Ensure user owns the character

        $request->validate([
            'type' => 'required|in:attribute,skill,specialization,trait',
            'target_id' => ['required', 'integer'],
            // Add validation for other fields based on type if necessary
        ]);

        $type = $request->input('type');
        $targetId = $request->input('target_id');
        $newValue = null; // Will store the intended new value
        $xpCost = 0;
        $changeDetails = ['type' => $type, 'target_id' => $targetId];
        $oldValue = null;

        // Calculate cost and validate based on type
        switch ($type) {
            case 'attribute':
                $attribute = $character->attributes()->where('attribute_id', $targetId)->first();
                if (!$attribute) return back()->with('error', 'Invalid attribute selected.');
                $oldValue = $attribute->pivot->value;
                $newValue = $oldValue + 1; // Assuming 1 point increase
                $xpCost = $oldValue * 5; // Cost based on current value
                $changeDetails['new_value'] = $newValue;
                break;
            case 'skill':
                $skill = $character->skills()->where('skill_id', $targetId)->first();
                if (!$skill) return back()->with('error', 'Invalid skill selected.');
                $oldValue = $skill->pivot->value;
                $newValue = $oldValue + 1;
                $skillModel = \App\Models\Skill::find($targetId);
                $multiplier = $skillModel->difficulty + 1;
                $xpCost = $oldValue * $multiplier;
                $changeDetails['new_value'] = $newValue;
                break;
            case 'specialization':
                $specialization = \App\Models\Specialization::find($targetId);
                if (!$specialization) return back()->with('error', 'Invalid specialization selected.');
                if ($character->specializations()->where('specialization_id', $targetId)->exists()) {
                    return back()->with('error', 'Specialization already learned.');
                }
                $xpCost = $specialization->xp_cost;
                break;
            case 'trait':
                $trait = \App\Models\CharacterTrait::find($targetId);
                if (!$trait) return back()->with('error', 'Invalid trait selected.');
                $hasTrait = $character->traits()->where('character_trait_id', $targetId)->exists();
                if ($hasTrait) {
                    return back()->with('error', 'Trait already has.');
                }
                $xpCost = $trait->xp_cost;
                break;
            default:
                return back()->with('error', 'Invalid upgrade type.');
        }

        // Check if character has enough XP
        if ($character->xp < $xpCost) {
            return back()->with('error', "Not enough XP available. Requires {$xpCost} XP.");
        }

        // Create a Character Change Log entry
        $character->changeLogs()->create([
            'changes' => json_encode($changeDetails),
            'xp_cost' => $xpCost,
            'status' => \App\Models\CharacterChangeLog::STATUS_PENDING, // Use constant from model
            'job_id' => null, // Link to a job later if needed
        ]);

        return back()->with('success', "Upgrade request submitted for approval. Cost: {$xpCost} XP.");
    }

    /**
     * Approve a character change request (admin only).
     *
     * @param  \App\Models\CharacterChangeLog  $changeLog
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveChange(App\Models\CharacterChangeLog $changeLog)
    {
        $this->authorize('approve', $changeLog->character); // Use character policy

        if ($changeLog->status !== App\Models\CharacterChangeLog::STATUS_PENDING) {
            return back()->with('error', 'This change request is not pending approval.');
        }

        $character = $changeLog->character;
        $changes = $changeLog->changes; // Already cast to array/object by model
        $xpCost = $changeLog->xp_cost;

        // Check again if character has enough XP (in case it changed since request)
        if ($character->xp < $xpCost) {
            // Reject automatically if not enough XP
            $changeLog->status = App\Models\CharacterChangeLog::STATUS_REJECTED;
            $changeLog->save();
            // TODO: Add rejection reason/note to log?
            // TODO: Notify user
            return back()->with('error', "Character no longer has enough XP ({$character->xp}) for this change (cost {$xpCost}). Request rejected.");
        }

        // Apply the change based on type
        DB::transaction(function () use ($character, $changes, $xpCost, $changeLog) {
            $type = $changes['type'];
            $targetId = $changes['target_id'];
            $newValue = $changes['new_value'] ?? null;

            switch ($type) {
                case 'attribute':
                    $character->attributes()->updateExistingPivot($targetId, [
                        'value' => $newValue,
                        'xp_spent' => DB::raw("xp_spent + {$xpCost}") // Track XP spent on the attribute itself
                    ]);
                    break;
                case 'skill':
                    $character->skills()->updateExistingPivot($targetId, [
                        'value' => $newValue,
                        'xp_spent' => DB::raw("xp_spent + {$xpCost}")
                    ]);
                    break;
                case 'specialization':
                    $character->specializations()->attach($targetId, [
                        'xp_spent' => $xpCost
                    ]);
                    break;
                case 'trait':
                    $character->traits()->attach($targetId);
                    break;
            }

            // Deduct XP from character's bank
            $character->decrement('xp', $xpCost);
            $character->updateLevel(); // Recalculate level after XP change

            // Mark the log as approved
            $changeLog->status = App\Models\CharacterChangeLog::STATUS_APPROVED;
            $changeLog->save();

            // TODO: Notify user of approval
        });

        return back()->with('success', 'Character change request approved.');
    }

    /**
     * Reject a character change request (admin only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CharacterChangeLog  $changeLog
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectChange(Request $request, App\Models\CharacterChangeLog $changeLog)
    {
        $this->authorize('approve', $changeLog->character); // Use character policy

        if ($changeLog->status !== App\Models\CharacterChangeLog::STATUS_PENDING) {
            return back()->with('error', 'This change request is not pending approval.');
        }

        // TODO: Add validation for rejection reason if needed

        $changeLog->status = App\Models\CharacterChangeLog::STATUS_REJECTED;
        // TODO: Store rejection reason if provided in request
        // $changeLog->notes = $request->input('rejection_reason');
        $changeLog->save();

        // TODO: Notify user of rejection

        return back()->with('success', 'Character change request rejected.');
    }
}
