<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
        /* Keep frontend specific styles if needed, or move to custom.css */
        .hero-section {
            background: var(--primary-gradient);
            padding: 0rem;
            padding-bottom: 6rem;
            color: white;
        }
        .search-container { /* Example: Keep if specific to frontend */
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
        }
         .category-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            transition: transform 0.3s ease;
            border: 1px solid #9d4edd;
            height: 100%;
        }
        .category-card:hover {
            transform: translateY(-5px);
            background: var(--primary-gradient);
        }
        .category-card i{
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .category-card h3{
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .category-card:hover i{
            color: white;
            -webkit-text-fill-color: white;
        }
        .category-card:hover h3{
            color: white;
            -webkit-text-fill-color: transparent;
        }
        .category-card:hover p{
            color: white;
        }
        .navbar{
            margin-bottom: 6rem;
        }
        /* Add toggle button styles here or ensure they are in custom.css */
        .theme-toggle-button .bi-sun-fill { display: none; }
        html.dark-mode .theme-toggle-button .bi-moon-stars-fill { display: none; }
        html.dark-mode .theme-toggle-button .bi-sun-fill { display: inline-block; }
    </style>
</head>
<body>
    <section class="hero-section text-center">
        <nav class="navbar navbar-expand-lg navbar-dark" style="background: transparent;">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <i class="bi bi-stars me-2"></i>Pax Republica
                </a>
                
                @if (Route::has('login'))
                    <div class="d-flex align-items-center">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-light me-2">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-light me-2">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-outline-light me-2">Register</a>
                            @endif
                        @endauth
                        {{-- Updated Theme Toggle Button --}}
                        <button class="btn btn-outline-light theme-toggle-button" id="theme-toggle-button">
                            <i class="bi bi-moon-stars-fill"></i> {{-- Moon icon for light mode --}}
                            <i class="bi bi-sun-fill"></i>       {{-- Sun icon for dark mode --}}
                        </button>
                    </div>
                @endif
            </div>
        </nav>
        <div class="container text-center">
            @if($pagetitle != 'Home')
            <h1 class="display-4 mb-4">{{ $pagetitle }}</h1>
            <p class="d-flex justify-content-center align-items-center" >
                <a href="{{ route('home') }}" class="text-white text-decoration-none text-bold mx-2 me-2">Home</a>/
                @if(request()->routeIs('category'))
                <a href="#" class="text-white text-decoration-none text-bold mx-2 me-2">Category</a>/
                @endif
                @if(request()->routeIs('category.item'))
                <a href="{{ route('category') }}?slug={{$slug}}" class="text-white text-decoration-none text-bold mx-2 me-2">{{ deSlug($slug) }}</a>/
                @endif

                <a href="" class="text-white text-decoration-none text-bold mx-2 me-2">{{ $pagetitle }}</a>
            </p>
            @else
            <h1 class="display-4 mb-4">Welcome to Our World</h1>
            <p class="lead mb-5">Explore characters, scenes, locations, and more in our immersive universe</p>
            @endif
        </div>
        
        @if(!request()->routeIs('category.item'))
        {{-- Add search form here --}}
        <form id="searchForm" method="GET" action="{{ route('category') }}"> {{-- Default action, will be updated by JS --}}
            <div class="search-container mx-auto" style="max-width: 700px;">
                <div class="row g-3">
                    <div class="col-md-4">
                        {{-- Add name attribute --}}
                        <select class="form-select form-select-lg" id="searchCategory" name="slug">
                            <option @if($slug == 'characters') selected @endif value="characters" data-route="{{ route('category', 'characters') }}">Characters</option>
                            <option @if($slug == 'scenes') selected @endif value="scenes" data-route="{{ route('category', 'scenes') }}">Scenes</option>
                            <option @if($slug == 'locations') selected @endif value="locations" data-route="{{ route('category', 'locations') }}">Locations</option>
                            <option @if($slug == 'players') selected @endif value="players" data-route="{{ route('category', 'players') }}">Players</option>
                            <option @if($slug == 'species') selected @endif value="species" data-route="{{ route('category', 'species') }}">Species</option>
                            <option @if($slug == 'factions') selected @endif value="factions" data-route="{{ route('category', 'factions') }}">Factions</option>
                            <option @if($slug == 'archetypes') selected @endif value="archetypes" data-route="{{ route('category', 'archetypes') }}">Archetypes</option>
                            <option @if($slug == 'vehicles') selected @endif value="vehicles" data-route="{{ route('category', 'vehicles') }}">Vehicles</option>
                            <option @if($slug == 'attributes') selected @endif value="attributes" data-route="{{ route('category', 'attributes') }}">Attributes</option>
                            <option @if($slug == 'skills') selected @endif value="skills" data-route="{{ route('category', 'skills') }}">Skills</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <div class="input-group input-group-lg">
                            <input type="text" class="form-control" name="query" placeholder="Search..." aria-label="Search query"
                            @if($pagetitle != 'Home')
                                @if($searchQuery)
                                    value="{{ $searchQuery }}"
                                @endif
                            @endif
                            >
                            <button class="btn btn-light" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @endif
    </section>

    @yield('content')

    <!-- Footer -->
    <footer class="py-4 mt-5" style="background: rgba(0, 0, 0, 0.2)">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-decoration-none text-light">Home</a></li>
                        <li><a href="#" class="text-decoration-none text-light">About</a></li>
                        <li><a href="#" class="text-decoration-none text-light">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Connect With Us</h5>
                    <div class="d-flex gap-3 fs-4">
                        <a href="#" class="text-light"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <h5>Newsletter</h5>
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Enter your email">
                        <button class="btn btn-light" type="button">Subscribe</button>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <p class="text-center mb-0">&copy; 2024 Your Company. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Add the persistent theme toggle script --}}
    <script>
        const themeToggleButton = document.getElementById('theme-toggle-button');
        const htmlElement = document.documentElement;

        // Function to apply the theme
        const applyTheme = (theme) => {
            if (theme === 'dark') {
                htmlElement.classList.add('dark-mode');
            } else {
                htmlElement.classList.remove('dark-mode');
            }
            // Ensure body class matches if needed by specific styles (though targeting html is preferred)
            // document.body.classList.toggle('dark', theme === 'dark');
        };

        // Check localStorage on page load
        const savedTheme = localStorage.getItem('theme') || 'dark'; // Default to dark for frontend? Or 'light'? Let's stick to light default for consistency.
        // const savedTheme = localStorage.getItem('theme') || 'light';
        applyTheme(savedTheme);

        // Toggle theme on button click
        if (themeToggleButton) { // Check if button exists
             themeToggleButton.addEventListener('click', () => {
                const currentTheme = htmlElement.classList.contains('dark-mode') ? 'dark' : 'light';
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                applyTheme(newTheme);
                localStorage.setItem('theme', newTheme); // Save preference
            });
        } else {
            console.error("Theme toggle button not found");
        }

    </script>

    <script>
        // Update form action based on category selection
        const searchCategorySelect = document.getElementById('searchCategory');
        const searchForm = document.getElementById('searchForm');

        if (searchCategorySelect && searchForm) {
            searchCategorySelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const route = selectedOption.getAttribute('data-route');
                if (route) {
                    // We only need the base route, query params will be added by GET
                    searchForm.action = route.split('?'
