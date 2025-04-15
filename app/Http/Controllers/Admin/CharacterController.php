<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Character;
use Illuminate\Http\Request;

class CharacterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $characters = Character::orderBy('name')->paginate(15);
        return view('admin.characters.index', compact('characters'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Character $character)
    {
        return view('admin.characters.edit', compact('character'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Character $character)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|string|in:inprogress,pending,active',
            'xp' => 'required|integer|min:0',
            'details' => 'nullable|json',
        ]);

        $character->update($validated);

        return redirect()->route('admin.characters.index')->with('success', 'Character updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Character $character)
    {
        $character->delete();
        return redirect()->route('admin.characters.index')->with('success', 'Character deleted successfully.');
    }

    /**
     * Approve the specified character.
     */
    public function approve(Character $character)
    {
        $character->status = 'active';
        $character->approved_at = now();
        $character->save();

        return redirect()->route('admin.characters.index')->with('success', 'Character approved successfully.');
    }

    /**
     * Reject the specified character.
     */
    public function reject(Character $character)
    {
        $character->status = 'inprogress';
        $character->approved_at = null;
        $character->save();

        return redirect()->route('admin.characters.index')->with('success', 'Character rejected successfully.');
    }

    /**
     * Display a list of pending characters.
     */
    public function pending()
    {
        $characters = Character::where('status', 'pending')->orderBy('name')->paginate(15);
        return view('admin.characters.pending', compact('characters'));
    }

    /**
     * Approve the specified character change.
     */
    public function approveChange($id)
    {
        // Placeholder for character change approval logic
    }

    /**
     * Reject the specified character change.
     */
    public function rejectChange($id)
    {
        // Placeholder for character change rejection logic
    }

    /**
     * Display a list of character changes.
     */
    public function listChanges()
    {
        // Placeholder for character change listing logic
    }
}
