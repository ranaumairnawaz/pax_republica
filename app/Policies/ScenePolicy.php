<?php

namespace App\Policies;

use App\Models\Scene;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ScenePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view the scenes list
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Scene $scene): bool
    {
        // All authenticated users can view non-private scenes
        // Private scenes can only be viewed by participants or admins
        if (!$scene->is_private) {
            return true;
        }
        
        // Check if user has a character participating in the scene
        $userCharacterIds = $user->characters->pluck('id');
        $isParticipating = $scene->participants()->whereIn('character_id', $userCharacterIds)->exists();
        
        return $isParticipating || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // All authenticated users can create scenes if they have at least one active character
        return $user->characters()->where('status', 'active')->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Scene $scene): bool
    {
        // Scene can only be updated if it's in planning stage
        if (!$scene->isPlanning()) {
            return false;
        }
        
        // Check if user has a character that created the scene (first participant)
        $firstParticipant = $scene->participants()->orderBy('joined_at')->first();
        if (!$firstParticipant) {
            return false;
        }
        
        $character = $firstParticipant->character;
        return $character && $character->user_id === $user->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can start or complete the model.
     */
    public function manage(User $user, Scene $scene): bool
    {
        // Check if user has a character that created the scene (first participant)
        $firstParticipant = $scene->participants()->orderBy('joined_at')->first();
        if (!$firstParticipant) {
            return false;
        }
        
        $character = $firstParticipant->character;
        return $character && $character->user_id === $user->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Scene $scene): bool
    {
        // Only admins can delete scenes
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Scene $scene): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Scene $scene): bool
    {
        return false;
    }
}
