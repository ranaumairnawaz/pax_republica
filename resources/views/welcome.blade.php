<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #9d4edd, #ff8c42);
        }
        .dark {
            background-color: #121212;
            color: #ffffff;
        }
        .hero-section {
            background: var(--primary-gradient);
            padding: 0rem;
            padding-bottom: 6rem;
            color: white;
        }
        .search-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
        }
        .category-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            transition: transform 0.3s ease;
        }
        .category-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.1);
        }
        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        .navbar{
            margin-bottom: 6rem;
        }
    </style>
</head>
<body class="dark">
    <section class="hero-section text-center">
        <nav class="navbar navbar-expand-lg navbar-dark" style="background: transparent;">
            <div class="container">
                <a class="navbar-brand" href="#">
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
                        <button class="btn btn-outline-light" onclick="toggleTheme()">
                            <i class="bi bi-sun-fill"></i>
                        </button>
                    </div>
                @endif
            </div>
        </nav>
        <div class="container">
            <h1 class="display-4 mb-4">Welcome to Our World</h1>
            <p class="lead mb-5">Explore characters, scenes, locations, and more in our immersive universe</p>
            
            <div class="search-container mx-auto" style="max-width: 700px;">
                <div class="row g-3">
                    <div class="col-md-4">
                        <select class="form-select form-select-lg" id="searchCategory">
                            <option value="characters">Characters</option>
                            <option value="scenes">Scenes</option>
                            <option value="locations">Locations</option>
                            <option value="players">Players</option>
                            <option value="species">Species</option>
                            <option value="factions">Factions</option>
                            <option value="archetypes">Archetypes</option>
                            <option value="vehicles">Vehicles</option>
                            <option value="attributes">Attributes</option>
                            <option value="skills">Skills</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <div class="input-group input-group-lg">
                            <input type="text" class="form-control" placeholder="Search...">
                            <button class="btn btn-light" type="button">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Browse Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Browse Categories</h2>
            <div class="row g-4">
                @foreach(['Characters', 'Scenes', 'Locations', 'Species', 'Factions', 'Vehicles'] as $category)
                    <div class="col-md-4">
                        <div class="category-card p-4 text-center">
                            <i class="bi bi-grid-3x3-gap-fill mb-3 fs-1"></i>
                            <h3>{{ $category }}</h3>
                            <p class="mb-0">Explore {{ strtolower($category) }} in our universe</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-5" style="background: rgba(157, 78, 221, 0.1)">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="mb-4">About Us</h2>
                    <p class="lead">We are dedicated to creating an immersive and engaging platform for role-playing enthusiasts.</p>
                    <p>Our platform provides a rich environment for character development, storytelling, and community interaction.</p>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="bi bi-people-fill" style="font-size: 8rem; opacity: 0.8;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Contact Us</h2>
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <form class="category-card p-4">
                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="Your Email">
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" rows="4" placeholder="Your Message"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" style="background: var(--primary-gradient)">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4" style="background: rgba(0, 0, 0, 0.2)">
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
    <script>
        function toggleTheme() {
            document.documentElement.classList.toggle('dark');
            document.body.classList.toggle('dark');
            const button = document.querySelector('.theme-toggle i');
            button.classList.toggle('bi-sun-fill');
            button.classList.toggle('bi-moon-fill');
        }
    </script>
</body>
</html>
