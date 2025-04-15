@extends('layouts.app')

@section('title', 'Admin: Edit Location')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h1>Edit Location: {{ $location->name }}</h1>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('admin.locations.update', $location->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- Use PUT method for updates --}}

                <div class="mb-3">
                    <label for="name" class="form-label">Location Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $location->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $location->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="picture_url" class="form-label">Picture</label>
                    <input type="file" class="form-control @error('picture_url') is-invalid @enderror" id="picture_url" name="picture_url" accept="image/*">
                    @error('picture_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if($location->picture_url)
                        <div class="mt-2">
                            <small>Current Picture:</small><br>
                            <img src="{{ asset('storage/' . $location->picture_url) }}" alt="{{ $location->name }}" width="100">
                        </div>
                    @endif
                </div>

                <a href="{{ route('admin.locations.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Location</button>
            </form>
        </div>
    </div>
</div>
@endsection
