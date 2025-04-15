@extends('layouts.frontend')
@section('content')
<!-- Browse Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Browse Categories</h2>
        <div class="row g-4">
            @php
                $categories = [
                    'Characters' => ['route' => 'characters', 'icon' => 'bi-people-fill'],
                    'Scenes' => ['route' => 'scenes', 'icon' => 'bi-camera-reels-fill'],
                    'Locations' => ['route' => 'locations', 'icon' => 'bi-geo-alt-fill'],
                    'Species' => ['route' => 'species', 'icon' => 'bi-tree-fill'],
                    'Factions' => ['route' => 'factions', 'icon' => 'bi-flag-fill'],
                    'Vehicles' => ['route' => 'vehicles', 'icon' => 'bi-truck']
                ];
            @endphp
            @foreach($categories as $categoryName => $categoryData)
                <div class="col-md-4">
                    <a href="{{ route('category') }}?slug={{$categoryData['route']}}" class="text-decoration-none">
                        <div class="category-card p-4 text-center">
                            <i class="bi {{ $categoryData['icon'] }} mb-3 fs-1"></i>
                            <h3>{{ $categoryName }}</h3>
                            <p class="mb-0">Explore {{ strtolower($categoryName) }} in our universe</p>
                        </div>
                    </a>
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

@endsection

@push('scripts')
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
                searchForm.action = route.split('?')[0]; 
                // Remove category_slug from being submitted as it's part of the route now
                // searchCategorySelect.name = ''; // Or keep it if controller needs it explicitly
            }
        });

        // Trigger change on load to set initial action correctly
        searchCategorySelect.dispatchEvent(new Event('change'));
    }
</script>
@endpush
