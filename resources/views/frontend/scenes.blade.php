@extends('layouts.frontend')

@section('content')
<section>
    <div class="container">
        <h2 class="text-center my-5">{{ $pagetitle }}</h2> {{-- Use dynamic title --}}

        {{-- Display search query if present --}}
        @isset($searchQuery)
            <div class="alert alert-info" role="alert">
                Showing results for: <strong>"{{ $searchQuery }}"</strong> in category: <strong>{{ $slug }}</strong>
            </div>
        @endisset

        <div class="row g-4">
            @if($data->isEmpty())
                <div class="col-12 text-center">
                    {{-- Adjust message based on whether it was a search --}}
                    @isset($searchQuery)
                        <p>No scenes found matching your search criteria.</p>
                    @else
                        <p>No scenes found in this category yet.</p>
                    @endisset
                </div>
            @else {{-- Added else to wrap the loop --}}
                 @foreach($data as $item)
                <div class="col-md-4">
                    <a href="{{ route('category.item', [$slug, $item->id]) }}" class="text-decoration-none">
                        <div class="category-card p-4 text-center">
                            <h3>{{ $item->title }}</h3>
                            <p class="mb-0">Location: {{ $item->location->name ?? 'Unknown' }}</p>
                            <p class="mb-0">Status: {{ $item->status }}</p>
                            <p class="mb-0">Started At: {{ $item->started_at->format('Y-m-d H:i') ?? 'Not Started' }}</p>
                            <p class="mb-0">Ended At: {{ $item->ended_at->format('Y-m-d H:i') ?? 'Not Ended' }}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            @endif {{-- End else --}}
        </div>
    </div>
</section>
@endsection
