<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\SceneController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\DiceController;
use App\Http\Controllers\FactionController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\NpcController;
use App\Http\Controllers\ModTemplateController;
use App\Http\Controllers\VehicleTemplateController;
// Assuming Admin controllers exist in App\Http\Controllers\Admin
use App\Http\Controllers\Admin\SkillController as AdminSkillController;
use App\Http\Controllers\Admin\AttributeController as AdminAttributeController;
use App\Http\Controllers\Admin\ArchetypeController as AdminArchetypeController;
use App\Http\Controllers\Admin\SpeciesController as AdminSpeciesController;
use App\Http\Controllers\Admin\FactionController as AdminFactionController;
use App\Http\Controllers\Admin\FactionRankController as AdminFactionRankController;
use App\Http\Controllers\Admin\SpecializationController as AdminSpecializationController;
use App\Http\Controllers\Admin\TraitController as AdminTraitController;
use App\Http\Controllers\Admin\VehicleTemplateController as AdminVehicleTemplateController;
use App\Http\Controllers\Admin\ModTemplateController as AdminModTemplateController;
use App\Http\Controllers\Admin\LocationController as AdminLocationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\JobController as AdminJobController;
use App\Http\Controllers\Admin\CharacterController as AdminCharacterController;
use App\Http\Controllers\Admin\VehicleController as AdminVehicleController;
use App\Http\Controllers\Admin\NpcController as AdminNpcController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');

// Content Routes
Route::get('/categories', [HomeController::class, 'category'])->name('category');
Route::get('/categories/{slug}/{id}', [HomeController::class, 'categoryItem'])->name('category.item');



// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard Routes
    Route::get('/dashboard', function () {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard'); // Corrected route name
        }
        return redirect()->route('dashboard.player');
    })->name('dashboard');

    Route::middleware(['verified'])->group(function () {
        Route::get('/dashboard/player', [App\Http\Controllers\DashboardController::class, 'player'])->name('dashboard.player');
        Route::resource('characters', CharacterController::class);
        Route::delete('/characters/{character}', [CharacterController::class, 'destroy'])->name('characters.destroy'); // Add this line
        Route::post('/characters/{character}/submit', [CharacterController::class, 'submit'])->name('characters.submit');
        Route::post('/characters/{character}/spend-xp', [CharacterController::class, 'spendXp'])->name('characters.spend-xp');
        Route::resource('scenes', SceneController::class);
        Route::post('/scenes/{scene}/start', [SceneController::class, 'start'])->name('scenes.start');
        Route::post('/scenes/{scene}/complete', [SceneController::class, 'complete'])->name('scenes.complete');
        Route::post('/scenes/{scene}/join', [SceneController::class, 'join'])->name('scenes.join');
        Route::post('/scenes/{scene}/leave', [SceneController::class, 'leave'])->name('scenes.leave');
        Route::post('/scenes/{scene}/roll-dice', [SceneController::class, 'rollDice'])->name('scenes.rollDice');
        Route::post('/scenes/{scene}/vote', [SceneController::class, 'vote'])->name('scenes.vote');
        Route::post('/scenes/{scene}/posts', [PostController::class, 'store'])->name('posts.store');
        Route::post('/scenes/{scene}/complete', [SceneController::class, 'complete'])->name('scenes.complete');
        Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
        Route::get('/chat', [MessageController::class, 'index'])->name('chat.index');
        Route::get('/chat/new', [MessageController::class, 'create'])->name('chat.new');
        Route::post('/chat/send', [MessageController::class, 'send'])->name('chat.send');
        Route::post('/chat/fetch', [MessageController::class, 'fetchMessages'])->name('chat.fetch');
        Route::get('/chat/conversations', [MessageController::class, 'getUpdatedConversations'])->name('chat.conversations');
        Route::post('/chat/user-status', [MessageController::class, 'checkUserStatus'])->name('chat.user-status');
        Route::delete('/chat/{message}', [MessageController::class, 'destroy'])->name('chat.destroy');
        Route::get('/profile/edit', [AuthController::class, 'showProfileEdit'])->name('profile.edit');
        Route::put('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
        Route::post('/dice/roll', [DiceController::class, 'roll'])->name('dice.roll');
        Route::get('/factions', [FactionController::class, 'index'])->name('factions.index');
        Route::get('/factions/{faction}', [FactionController::class, 'show'])->name('factions.show');

        // Job Routes (Player accessible)
        Route::resource('jobs', JobController::class)->except(['edit', 'update', 'destroy']);
        Route::post('/jobs/{job}/comments', [JobController::class, 'storeComment'])->name('jobs.comments.store');
        // Add routes for player editing/deleting comments if needed later

        // Vehicle Routes (Player accessible parts)
        Route::resource('vehicles', VehicleController::class)->only(['index', 'show']);

        // Dice Rolling Route
        Route::post('/dice/roll', [DiceController::class, 'roll'])->name('dice.roll');

        // Debug route for character creation
        Route::post('/debug/character-create', function (\Illuminate\Http\Request $request) {
            return response()->json([
                'attributes' => $request->attributes,
                'skills' => $request->skills,
                'form_data' => $request->all()
            ]);
        })->name('debug.character-create');

        Route::get('/roll-dice', [\App\Http\Controllers\DiceRollingController::class, 'rollDice']);
    });

    // Admin Routes
    Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'admin'])->name('dashboard');

        // --- User & Character Management ---
        Route::resource('users', AdminUserController::class); // User Management
        Route::post('/characters/{character}/approve', [AdminCharacterController::class, 'approve'])->name('characters.approve');
        Route::post('/characters/{character}/reject', [AdminCharacterController::class, 'reject'])->name('characters.reject');
        Route::get('/characters/pending', [AdminCharacterController::class, 'pending'])->name('characters.pending');
        Route::resource('characters', AdminCharacterController::class)->except(['create', 'store', 'show']); // Manage existing characters

        // Character Change Log Management
        Route::post('/character-changes/{changeLog}/approve', [AdminCharacterController::class, 'approveChange'])->name('changes.approve');
        Route::post('/character-changes/{changeLog}/reject', [AdminCharacterController::class, 'rejectChange'])->name('changes.reject');
        Route::get('/character-changes', [AdminCharacterController::class, 'listChanges'])->name('changes.index');

        // --- Job Management ---
        Route::resource('jobs', AdminJobController::class); // Full CRUD for Admin

        // --- Content Management (as per spec) ---
        Route::resource('skills', AdminSkillController::class);
        Route::resource('attributes', AdminAttributeController::class);
        Route::resource('archetypes', AdminArchetypeController::class);
        Route::resource('species', AdminSpeciesController::class); // Renamed from Races
        Route::resource('specializations', AdminSpecializationController::class);
        Route::resource('traits', AdminTraitController::class);
        Route::resource('locations', AdminLocationController::class);

        // Faction Management (includes nested ranks)
        Route::resource('factions', AdminFactionController::class);
        Route::resource('factions.ranks', AdminFactionRankController::class)->shallow(); // Nested ranks

        // Vehicle/Mod Management
        Route::resource('vehicles', AdminVehicleController::class); // Full CRUD for Admin
        Route::resource('vehicle-templates', AdminVehicleTemplateController::class);
        Route::resource('mod-templates', AdminModTemplateController::class); // Renamed from vehicle mods

        // NPC Management
        Route::resource('npcs', AdminNpcController::class);

        // --- Content Management (as per spec) ---
        Route::get('/content', function () {
            return view('admin.content.index');
        })->name('content.index');

        // --- Other Admin Actions ---
        // Add routes for sitebans, system config etc. later if needed
        Route::resource('banned-ips', \App\Http\Controllers\Admin\BannedIpController::class);
        Route::resource('settings', \App\Http\Controllers\Admin\SettingController::class);
    });
    

    Route::resource('plots', \App\Http\Controllers\PlotController::class);
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Http\Request $request, $id, $hash) {
    if (! hash_equals((string) $id, (string) $request->route('id'))) {
        return redirect()->route('verification.notice')->with('error', 'Invalid verification ID.');
    }

    if (! hash_equals((string) $hash, sha1(Auth::user()->getEmailForVerification()))) {
        return redirect()->route('verification.notice')->with('error', 'Invalid verification hash.');
    }

    if ($request->user()->hasVerifiedEmail()) {
        return redirect()->route('dashboard');
    }

    $request->user()->markEmailAsVerified();

    event(new \Illuminate\Auth\Events\Verified($request->user()));

    return redirect()->route('dashboard')->with('success', 'Your email address has been verified!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
