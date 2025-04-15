<?php

namespace App\Policies;

use App\Models\Character;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CharacterPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view their characters
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Character  $character
     * @return bool
     */
    public function view(User $user, Character $character): bool
    {
        // Users can view their own characters or admins can view any character
        return $user->id === $character->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create characters
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Character  $character
     * @return bool
     */
    public function update(User $user, Character $character): bool
    {
        // Users can only update their own characters that are in progress
        return $user->id === $character->user_id && $character->isInProgress();
    }

    /**
     * Determine whether the user can submit the character for approval.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Character  $character
     * @return bool
     */
    public function submit(User $user, Character $character): bool
    {
        // Users can only submit their own characters that are in progress
        return $user->id === $character->user_id && $character->isInProgress();
    }

    /**
     * Determine whether the user can approve the character.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Character  $character
     * @return bool
     */
    public function approve(User $user, Character $character): bool
    {
        // Only admins can approve characters
        return $user->isAdmin() && $character->isPending();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Character  $character
     * @return bool
     */
    public function delete(User $user, Character $character): bool
    {
        // Users can delete their own characters that are not active, or admins can delete any
        return ($user->id === $character->user_id && !$character->isActive()) || $user->isAdmin();
    }

    /**
     * Determine whether the user can use this character in scenes and posts.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Character  $character
     * @return bool
     */
    public function use(User $user, Character $character): bool
    {
        // Users can only use their own active characters
        return $user->id === $character->user_id && $character->isActive();
    }
}