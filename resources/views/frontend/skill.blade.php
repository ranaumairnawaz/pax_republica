@extends('layouts.frontend')

@section('content')
<section>
    <div class="container">
        <h2 class="text-center my-5">{{ $pagetitle }}</h2>

        <div class="row">
            <div class="col-md-12">
                @if($data)
                @if($skill)
                    <h3>{{ $skill->name }}</h3>
                    <p><strong>Description:</strong> {{ $skill->description ?? 'N/A' }}</p>
                    <p><strong>Created At:</strong> {{ $skill->created_at }}</p>
                    <p><strong>Updated At:</strong> {{ $skill->updated_at }}</p>
                    {{-- Add relationships if needed, e.g., characters, npcs --}}
                @else
                    <p>Skill not found.</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
