<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Character;
use App\Models\Job; // Import the Job model
use App\Models\Notification;
use App\Models\Scene;

class DashboardController extends Controller
{
    /**
     * Display the player dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function player()
    {
        $user = Auth::user();
        $characters = $user->characters;
        $notifications = \App\Models\Notification::where('user_id', $user->id)->latest()->take(5)->get();
        $recentScenes = Scene::whereHas('participants', function ($query) use ($user) {
            $query->whereIn('character_id', $user->characters->pluck('id'));
        })->latest()->take(5)->get();

        return view('dashboard.player', compact('user', 'characters', 'notifications', 'recentScenes'));
    }

    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function admin()
    {
        $user = Auth::user();

        // Get pending character approvals count
        // Assuming Character model has constants for status
        $pendingCharactersCount = Character::where('status', 'PENDING')->count(); // Use constant if available

        // Get open jobs count
        // Assuming Job model has constants for status
        $openJobsCount = Job::where('status', 'OPEN')->count(); // Use constant if available

        // Pass counts to the main dashboard view
        return view('dashboard', compact('user', 'pendingCharactersCount', 'openJobsCount'));
    }
}
