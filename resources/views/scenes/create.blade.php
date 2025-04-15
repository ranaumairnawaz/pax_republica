@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Scene</h1>
        <form action="{{ route('scenes.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>
           <div class="form-group">
                <label for="location_id">Location</label>
                <select class="form-control" id="location_id" name="location_id">
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="plot_id">Plot (Optional)</label>
                <select class="form-control" id="plot_id" name="plot_id">
                    <option value="">No Plot</option>
                    @foreach($plots as $plot)
                        <option value="{{ $plot->id }}">{{ $plot->title }}</option>
                    @endforeach
                </select>
            </div>
           <div class="form-group">
                <label for="character_id">Character</label>
                 <select class="form-control" id="character_id" name="character_id">
                    @foreach($characters as $character)
                        <option value="{{ $character->id }}">{{ $character->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="is_private" name="is_private">
                <label class="form-check-label" for="is_private">Is Private</label>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
