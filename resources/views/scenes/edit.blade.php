@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Scene</h1>
        <form action="{{ route('scenes.update', $scene->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $scene->title }}">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description">{{ $scene->description }}</textarea>
            </div>
            <div class="form-group">
                <label for="location_id">Location</label>
                <select class="form-control" id="location_id" name="location_id">
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}" {{ $scene->location_id == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="plot_id">Plot (Optional)</label>
                <select class="form-control" id="plot_id" name="plot_id">
                    <option value="">No Plot</option>
                    @foreach($plots as $plot)
                        <option value="{{ $plot->id }}" {{ $scene->plot_id == $plot->id ? 'selected' : '' }}>{{ $plot->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="is_private" name="is_private" {{ $scene->is_private ? 'checked' : '' }}>
                <label class="form-check-label" for="is_private">Is Private</label>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
