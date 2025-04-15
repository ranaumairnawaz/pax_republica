@extends('layouts.frontend')

@section('content')
<section>
    <div class="container">
        <h2 class="text-center my-5">{{ $pagetitle }}</h2>

        <div class="row">
            <div class="col-md-12">
                @if($data)
                @if($species)
                    <h3>{{ $species->name }}</h3>
                    <p><strong>Description:</strong> {{ $species->description ?? 'N/A' }}</p>
                    <p><strong>Modifiers:</strong> {{ $species->modifiers ?? 'N/A' }}</p>
                    <p><strong>Created At:</strong> {{ $species->created_at }}</p>
                    <p><strong>Updated At:</strong> {{ $species->updated_at }}</p>
                    {{-- Add relationships if needed, e.g., traits --}}
                @else
                    <p>Specie not found.</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
