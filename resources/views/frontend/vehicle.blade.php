@extends('layouts.frontend')

@section('content')
<section>
    <div class="container">
        <h2 class="text-center my-5">{{ $pagetitle }}</h2>

        <div class="row">
            <div class="col-md-12">
                @if($data)
                @if($vehicle)
                    <h3>{{ $vehicle->name }}</h3>
                    <p><strong>Description:</strong> {{ $vehicle->description ?? 'N/A' }}</p>
                    <p><strong>Model:</strong> {{ $vehicle->vehicleTemplate->name ?? 'N/A' }}</p>
                    <p><strong>Faction:</strong> {{ $vehicle->faction->name ?? 'N/A' }}</p>
                    <p><strong>Location:</strong> {{ $vehicle->location->name ?? 'N/A' }}</p>
                    <p><strong>Created At:</strong> {{ $vehicle->created_at }}</p>
                    <p><strong>Updated At:</strong> {{ $vehicle->updated_at }}</p>
                    {{-- Add relationships if needed, e.g., mods --}}
                @else
                    <p>Vehicle not found.</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
