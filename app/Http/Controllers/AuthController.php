<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Auth\Events\Registered;
use App\Models\BannedIp;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'account_name' => ['required', 'string', 'max:8', 'alpha_num', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'timezone' => ['required', 'string', 'timezone'],
            'real_name' => ['nullable', 'string', 'max:255'],
            'sex' => ['nullable', Rule::in(['M', 'F'])],
            'age' => ['nullable', 'integer', 'min:13'],
            'profile' => ['nullable', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Check if IP is banned
        $ipAddress = $request->ip();
        $bannedIp = BannedIp::where('ip_address', $ipAddress)->first();

        if ($bannedIp) {
            return back()->withErrors([
                'ip_address' => 'Your IP address is banned from registering.',
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'account_name' => $request->account_name,
            'email' => $request->email,
            'timezone' => $request->timezone,
            'user_type' => 'player', // Default to player
            'real_name' => $request->real_name,
            'sex' => $request->sex,
            'age' => $request->age,
            'profile' => $request->profile,
            'password' => Hash::make($request->password),
            'ip_address' => $ipAddress,
            'is_active' => true,
            'last_login_at' => now(),
        ]);

        event(new Registered($user));

        $user->sendEmailVerificationNotification();

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Update last login timestamp and set online status
            $user = Auth::user();
            $user->last_login_at = now();
            $user->last_activity_at = now();
            $user->is_online = true;
            $user->save();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Update user's online status
        if (Auth::check()) {
            $user = Auth::user();
            $user->is_online = false;
            $user->save();
        }
        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Show the profile edit form.
     *
     * @return \Illuminate\View\View
     */
    public function showProfileEdit()
    {
        return view('profile.edit');
    }

    /**
     * Update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'account_name' => ['required', 'string', 'max:8', 'alpha_num', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'timezone' => ['nullable', 'string', 'timezone'],
            'real_name' => ['nullable', 'string', 'max:255'],
            'sex' => ['nullable', Rule::in(['M', 'F'])],
            'age' => ['nullable', 'integer', 'min:13'],
            'profile' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->name = $request->name;
        $user->account_name = $request->account_name;
        $user->email = $request->email;
        $user->timezone = $request->timezone;
        $user->real_name = $request->real_name;
        $user->sex = $request->sex;
        $user->age = $request->age;
        $user->profile = $request->profile;
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');
    }
}
