@extends('layouts.frontend')

@section('content')
<section>
    <div class="container">
        <h2 class="text-center my-5">{{ $pagetitle }}</h2>

        {{-- Display search query if present --}}
        @isset($searchQuery)
            <div class="alert alert-info" role="alert">
                Showing results for: <strong>"{{ $searchQuery }}"</strong> in category: <strong>{{ $slug }}</strong>
            </div>
        @endisset

        <div class="row g-4">
            @if($data->isEmpty())
                <div class="col-12 text-center">
                    @isset($searchQuery)
                        <p>No vehicles found matching your search criteria.</p>
                    @else
                        <p>No vehicles found in this category yet.</p>
                    @endisset
                </div>
            @else
                @foreach($data as $item)
                <div class="col-md-4">
                    <a href="{{ route('category.item', [$slug, $item->id]) }}" class="text-decoration-none">
                        <div class="category-card p-4 text-center">
                            <h3>{{ $item->name }}</h3>
                            @if($item->description)
                            <p class="mb-0">{{ Str::limit($item->description, 100) }}</p>
                            @endif
                        </div>
                    </a>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</section>
@endsection
