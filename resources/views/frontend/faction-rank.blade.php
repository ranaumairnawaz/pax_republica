@extends('layouts.frontend')

@section('content')
<section>
    <div class="container">
        <h2 class="text-center my-5">{{ $pagetitle }}</h2>

        <div class="row">
            <div class="col-md-12">
                @if($data)
                @if($factionRank)
                    <h3>{{ $factionRank->name }}</h3>
                    <p><strong>Description:</strong> {{ $factionRank->description ?? 'N/A' }}</p>
                    <p><strong>Faction:</strong> {{ $factionRank->faction->name ?? 'N/A' }}</p>
                    <p><strong>Created At:</strong> {{ $factionRank->created_at }}</p>
                    <p><strong>Updated At:</strong> {{ $factionRank->updated_at }}</p>
                    {{-- Add relationships if needed, e.g., characters --}}
                @else
                    <p>Faction Rank not found.</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
