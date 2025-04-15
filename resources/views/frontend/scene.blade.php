@extends('layouts.frontend')

@section('content')
<section>
    <div class="container">
        <h2 class="text-center my-5">{{ $pagetitle }}</h2>

        <div class="row">
            <div class="col-md-12">
                @if($scene)
                <h3>{{ $scene->title }}</h3>
                <p><strong>Location:</strong> {{ $scene->location->name ?? 'N/A' }}</p>
                <p><strong>Status:</strong> {{ $scene->status }}</p>
                <p><strong>Started At:</strong> {{ $scene->started_at ? $scene->started_at->format('Y-m-d H:i') : 'N/A' }}</p>
                <p><strong>Ended At:</strong> {{ $scene->ended_at ? $scene->ended_at->format('Y-m-d H:i') : 'N/A' }}</p>
                <p><strong>Description:</strong> {{ $scene->description ?? 'N/A' }}</p>
                <p><strong>Plot:</strong> {{ $scene->plot->name ?? 'N/A' }}</p>
                <p><strong>Created At:</strong> {{ $scene->created_at }}</p>
                <p><strong>Updated At:</strong> {{ $scene->updated_at }}</p>
                {{-- Add relationships if needed, e.g., participants, posts --}}
                @else
                <p>Scene not found.</p>
                @endif
            </div>
        </div>
    </div>

    @if($isParticipating)
    <div class="card mt-3">
        <div class="card-header">
            Roll Dice
        </div>
        <div class="card-body">
            <form action="{{ route('scenes.rollDice', $scene) }}" method="POST">
                @csrf
                <input type="hidden" name="character_id" value="{{ $userCharacters->first()->id }}">

                <div class="mb-3">
                    <label for="rollable_type" class="form-label">Rollable Type</label>
                    <select class="form-control" id="rollable_type" name="rollable_type">
                        <option value="attributes">Attribute</option>
                        <option value="skills">Skill</option>
                        <option value="specializations">Specialization</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="rollable_id" class="form-label">Rollable</label>
                    <select class="form-control" id="rollable_id" name="rollable_id">
                        @if($userCharacters->isNotEmpty())
                            @foreach($userCharacters->first()->attributes as $attribute)
                            <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="mb-3">
                    <label for="modifier" class="form-label">Modifier</label>
                    <input type="number" class="form-control" id="modifier" name="modifier">
                </div>

                <button type="submit" class="btn btn-primary">Roll</button>
            </form>
        </div>
    </div>
    @endif
</section>
@endsection
