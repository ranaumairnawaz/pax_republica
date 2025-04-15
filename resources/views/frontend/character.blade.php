@extends('layouts.frontend')

@section('content')
<section>
    <div class="container">
        <h2 class="text-center my-5">{{ $pagetitle }}</h2>

        <div class="row">
            <div class="col-md-12">
                @if($character)
                    <h3>{{ $character->name }}</h3>
                    <p><strong>Specie:</strong> {{ $character->specie->name ?? 'N/A' }}</p>
                    <p><strong>Faction:</strong> {{ $character->faction->name ?? 'N/A' }}</p>
                    <p><strong>Faction Rank:</strong> {{ $character->factionRank->name ?? 'N/A' }}</p>
                    <p><strong>Status:</strong> {{ $character->status }}</p>
                    <p><strong>Occupation:</strong> {{ $character->occupation }}</p>
                    <p><strong>Biography:</strong> {{ $character->biography }}</p>
                    <p><strong>XP:</strong> {{ $character->xp }}</p>
                    <p><strong>Credits:</strong> {{ $character->credits }}</p>
                    <p><strong>Location:</strong> {{ $character->location->name ?? 'N/A' }}</p>
                    <p><strong>Archetype:</strong> {{ $character->archetype->name ?? 'N/A' }}</p>
                    <p><strong>Created At:</strong> {{ $character->created_at }}</p>
                    <p><strong>Updated At:</strong> {{ $character->updated_at }}</p>
                    {{-- Add relationships if needed, e.g., attributes, skills, traits --}}
                @else
                    <p>Character not found.</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
