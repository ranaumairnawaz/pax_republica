@extends('layouts.frontend')

@section('content')
<section>
    <div class="container">
        <h2 class="text-center my-5">{{ $pagetitle }}</h2>

        <div class="row">
            <div class="col-md-12">
                @if($data)
                @if($faction)
                    <h3>{{ $faction->name }}</h3>
                    <p><strong>Description:</strong> {{ $faction->description ?? 'N/A' }}</p>
                    <p><strong>Created At:</strong> {{ $faction->created_at }}</p>
                    <p><strong>Updated At:</strong> {{ $faction->updated_at }}</p>
                    {{-- Add relationships if needed, e.g., ranks, characters --}}
                @else
                    <p>Faction not found.</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
