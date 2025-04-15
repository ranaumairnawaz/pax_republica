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
                        <p>No characters found matching your search criteria.</p>
                    @else
                        <p>No characters found in this category yet.</p>
                    @endisset
                </div>
            @else {{-- Added else to wrap the loop --}}
                @foreach($data as $item)
                <div class="col-md-4">
                    <a href="{{ route('category.item', [$slug, $item->id]) }}" class="text-decoration-none">
                        <div class="category-card p-4 text-center">
                            <h3>{{ $item->name }}</h3>
                            <p class="mb-0">Specie: {{ $item->specie->name }}</p>
                            <p class="mb-0">Faction: {{ $item->faction->name }}</p>
                            <p class="mb-0">Faction Rank: {{ $item->factionRank->name }}</p>
                            <p class="mb-0">Status: {{ $item->status }}</p>
                            <p class="mb-0">Occupation: {{ $item->occupation }}</p>
                            <p class="mb-0">Biography: {{ $item->biography }}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            @endif {{-- End else --}}
        </div>
    </div>
</section>
@endsection
