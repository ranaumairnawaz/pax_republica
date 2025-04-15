<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pax Republica')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/custom.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Basic Dark Mode Styles - Will be expanded in custom.css */
        html.dark-mode {
            filter: invert(1) hue-rotate(180deg);
        }
        /* Prevent images and specific elements from being inverted */
        html.dark-mode img,
        html.dark-mode .no-invert {
             filter: invert(1) hue-rotate(180deg);
        }
        /* Style for the toggle button */
        .theme-toggle {
            cursor: pointer;
            display: flex;
            align-items: center;
            text-decoration: none;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
        }
        .theme-toggle:hover {
            color: #fff; /* Match nav-link hover */
            background-color: rgba(255, 255, 255, 0.1); /* Match nav-link hover */
        }
        .theme-toggle .bi-sun-fill { display: none; }
        html.dark-mode .theme-toggle .bi-moon-stars-fill { display: none; }
        html.dark-mode .theme-toggle .bi-sun-fill { display: inline-block; }

        /* Switch Styles */
        .theme-switch {
            position: relative;
            display: inline-block;
            width: 50px; /* Width of the switch */
            height: 26px; /* Height of the switch */
            margin-left: auto; /* Push switch to the right */
        }
        .theme-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .theme-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 26px; /* Make it rounded */
        }
        .theme-slider:before {
            position: absolute;
            content: "";
            height: 18px; /* Size of the handle */
            width: 18px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%; /* Make handle circular */
        }
        /* Style the switch when checked (dark mode) */
        html.dark-mode .theme-slider {
            background: var(--primary-gradient); /* Use gradient for active state */
        }
        html.dark-mode .theme-slider:before {
            transform: translateX(24px); /* Move handle to the right */
        }
        /* Hide default button appearance */
        #theme-toggle-button {
            background: none;
            border: none;
            padding: 0; /* Remove padding */
            display: flex;
            align-items: center;
            width: 100%;
        }
        #theme-toggle-button:hover {
             background-color: transparent !important; /* Prevent hover background */
        }
        #theme-toggle-button .theme-label {
             margin-right: 10px; /* Space between label and switch */
        }

    </style>
</head>
<body>
    <div class="sidebar" style="overflow-x: scroll">
        <div class="sidebar-brand">
            <i class="bi bi-stars me-2"></i>Pax Republica
        </div>
        <div class="sidebar-nav">
            @auth
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                </div>
                @if(auth()->user()->is_admin == 0)
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('characters*') ? 'active' : '' }}" href="{{ route('characters.index') }}">
                        <i class="bi bi-person-badge me-2"></i>Characters
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('scenes*') ? 'active' : '' }}" href="{{ route('scenes.index') }}">
                        <i class="bi bi-camera-reels me-2"></i>Scenes
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('chat*') ? 'active' : '' }}" href="{{ route('chat.index') }}">
                        <i class="bi bi-chat-dots me-2"></i>Chat
                        @if(auth()->user()->unreadMessagesCount() > 0)
                            <span class="badge bg-danger rounded-pill ms-1">{{ auth()->user()->unreadMessagesCount() }}</span>
                        @endif
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('factions*') ? 'active' : '' }}" href="{{ route('factions.index') }}">
                        <i class="bi bi-flag me-2"></i>Factions
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('jobs*') ? 'active' : '' }}" href="{{ route('jobs.index') }}">
                        <i class="bi bi-briefcase me-2"></i>Jobs
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('vehicles*') ? 'active' : '' }}" href="{{ route('vehicles.index') }}">
                        <i class="bi bi-truck me-2"></i>Vehicles
                    </a>
                </div>
                @endif

                {{-- Admin Links --}}
                @if(auth()->user()->is_admin)
                    <div class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="bi bi-people-fill me-2"></i>User Management
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.characters.pending*') ? 'active' : '' }}" href="{{ route('admin.characters.pending') }}">
                            <i class="bi bi-person-check-fill me-2"></i>Character Approvals
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.jobs*') ? 'active' : '' }}" href="{{ route('admin.jobs.index') }}">
                            <i class="bi bi-briefcase-fill me-2"></i>Job Management
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.skills*') || request()->routeIs('admin.attributes*') || request()->routeIs('admin.specializations*') || request()->routeIs('admin.traits*') || request()->routeIs('admin.species*') || request()->routeIs('admin.archetypes*') || request()->routeIs('admin.factions*') || request()->routeIs('admin.locations*') || request()->routeIs('admin.vehicle-templates*') || request()->routeIs('admin.mod-templates*') ? 'active' : '' }}" href="#contentSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="contentSubmenu">
                            <i class="bi bi-database-fill-gear me-2"></i>Content Management
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.skills*') || request()->routeIs('admin.attributes*') || request()->routeIs('admin.specializations*') || request()->routeIs('admin.traits*') || request()->routeIs('admin.species*') || request()->routeIs('admin.archetypes*') || request()->routeIs('admin.factions*') || request()->routeIs('admin.locations*') || request()->routeIs('admin.vehicle-templates*') || request()->routeIs('admin.mod-templates*') ? 'show' : '' }}" id="contentSubmenu">
                            <ul class="list-unstyled ms-4">
                                <li><a class="nav-link sub-link {{ request()->routeIs('admin.skills*') ? 'active' : '' }}" href="{{ route('admin.skills.index') }}">Skills</a></li>
                                <li><a class="nav-link sub-link {{ request()->routeIs('admin.attributes*') ? 'active' : '' }}" href="{{ route('admin.attributes.index') }}">Attributes</a></li>
                                <li><a class="nav-link sub-link {{ request()->routeIs('admin.specializations*') ? 'active' : '' }}" href="{{ route('admin.specializations.index') }}">Specializations</a></li>
                                <li><a class="nav-link sub-link {{ request()->routeIs('admin.traits*') ? 'active' : '' }}" href="{{ route('admin.traits.index') }}">Traits</a></li>
                                <li><a class="nav-link sub-link {{ request()->routeIs('admin.species*') ? 'active' : '' }}" href="{{ route('admin.species.index') }}">Species</a></li>
                                <li><a class="nav-link sub-link {{ request()->routeIs('admin.archetypes*') ? 'active' : '' }}" href="{{ route('admin.archetypes.index') }}">Archetypes</a></li>
                                <li><a class="nav-link sub-link {{ request()->routeIs('admin.factions*') ? 'active' : '' }}" href="{{ route('admin.factions.index') }}">Factions</a></li>
                                <li><a class="nav-link sub-link {{ request()->routeIs('admin.locations*') ? 'active' : '' }}" href="{{ route('admin.locations.index') }}">Locations</a></li>
                                <li><a class="nav-link sub-link {{ request()->routeIs('admin.vehicle-templates*') ? 'active' : '' }}" href="{{ route('admin.vehicle-templates.index') }}">Vehicle Templates</a></li>
                                <li><a class="nav-link sub-link {{ request()->routeIs('admin.mod-templates*') ? 'active' : '' }}" href="{{ route('admin.mod-templates.index') }}">Mod Templates</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                            <i class="bi bi-gear-fill me-2"></i>Settings
                        </a>
                    </div>
                    <div class="nav-item-divider"></div>
                @endif
                {{-- End Admin Links --}}

                <div class="nav-item-divider"></div>

                <div class="nav-item">
                    {{-- Modified button to contain the switch --}}
                    <button class="nav-link theme-toggle" id="theme-toggle-button" aria-label="Toggle theme">
                        <i class="bi bi-moon-stars-fill me-2"></i>
                        <i class="bi bi-sun-fill me-2"></i>
                        <span class="theme-label">Theme</span> {{-- Simplified label --}}
                        <label class="theme-switch">
                            {{-- Input is visually hidden but helps with state --}}
                            <input type="checkbox" aria-hidden="true" tabindex="-1">
                            <span class="theme-slider"></span>
                        </label>
                    </button>
                </div>

                <div class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link w-100 text-start">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </div>

    <div class="main-content">
        @yield('content')
    </div>

    @auth
    <div class="bottom-navbar d-md-none">
        <a href="{{ route('dashboard') }}" class="bottom-nav-item {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <div>Dashboard</div>
        </a>
        <a href="{{ route('characters.index') }}" class="bottom-nav-item {{ request()->routeIs('characters*') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i>
            <div>Characters</div>
        </a>
        <a href="{{ route('scenes.index') }}" class="bottom-nav-item {{ request()->routeIs('scenes*') ? 'active' : '' }}">
            <i class="bi bi-camera-reels"></i>
            <div>Scenes</div>
        </a>
        <a href="{{ route('chat.index') }}" class="bottom-nav-item {{ request()->routeIs('chat*') ? 'active' : '' }}">
            <i class="bi bi-chat-dots"></i>
            <div>Chat</div>
            @if(auth()->user()->unreadMessagesCount() > 0)
                <span class="badge bg-danger rounded-pill position-absolute top-0 end-0 translate-middle-y">{{ auth()->user()->unreadMessagesCount() }}</span>
            @endif
        </a>
        <a href="{{ route('factions.index') }}" class="bottom-nav-item {{ request()->routeIs('factions*') ? 'active' : '' }}">
            <i class="bi bi-flag"></i>
            <div>Factions</div>
        </a>
        <a href="{{ route('jobs.index') }}" class="bottom-nav-item {{ request()->routeIs('jobs*') ? 'active' : '' }}">
            <i class="bi bi-briefcase"></i>
            <div>Jobs</div>
        </a>
        <a href="{{ route('vehicles.index') }}" class="bottom-nav-item {{ request()->routeIs('vehicles*') ? 'active' : '' }}">
            <i class="bi bi-truck"></i>
            <div>Vehicles</div>
        </a>
        {{-- Admin Link for Bottom Bar --}}
        @if(auth()->user()->is_admin)
        <a href="{{ route('admin.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('admin*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock-fill"></i>
            <div>Admin</div>
        </a>
        @else
        {{-- Show Menu toggle only for non-admins on bottom bar to save space --}}
        <a href="#" class="bottom-nav-item" onclick="document.querySelector('.sidebar').classList.toggle('show')">
            <i class="bi bi-list"></i>
            <div>Menu</div>
        </a>
        @endif
        {{-- End Admin Link --}}
    </div>
    @endauth

    <footer class="bg-light py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0 text-muted">Pax Republica &copy; {{ date('Y') }} | A Star Wars Roleplaying Game</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const themeToggleButton = document.getElementById('theme-toggle-button');
        const htmlElement = document.documentElement; // Target <html> instead of <body> for better compatibility

        const switchInput = themeToggleButton.querySelector('input[type="checkbox"]');

        // Function to apply the theme and update switch state
        const applyTheme = (theme) => {
            if (theme === 'dark') {
                htmlElement.classList.add('dark-mode');
                if(switchInput) switchInput.checked = true;
            } else {
                htmlElement.classList.remove('dark-mode');
                if(switchInput) switchInput.checked = false;
            }
        };

        // Check localStorage on page load
        const savedTheme = localStorage.getItem('theme') || 'light'; // Default to light
        applyTheme(savedTheme);

        // Toggle theme on button click
        themeToggleButton.addEventListener('click', () => {
            const currentTheme = htmlElement.classList.contains('dark-mode') ? 'dark' : 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            applyTheme(newTheme);
            localStorage.setItem('theme', newTheme); // Save preference
        });
    </script>

    @stack('scripts')
</body>
</html>
