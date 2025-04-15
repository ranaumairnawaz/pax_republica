@extends('layouts.app')

@section('title', 'Admin: Edit Faction')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h1>Edit Faction: {{ $faction->name }}</h1>
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
            <form action="{{ route('admin.factions.update', $faction) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Faction Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $faction->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                 <div class="mb-3">
                    <label for="code" class="form-label">Faction Code</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $faction->code) }}" required placeholder="e.g., REPUBLIC">
                     <small class="form-text text-muted">A unique code for the faction (uppercase, underscores).</small>
                     @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $faction->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="color" class="form-label">Color</label>
                    <input type="color" class="form-control @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color', $faction->color) }}">
                    <small class="form-text text-muted">Hex color code (e.g., #007bff)</small>
                    @error('color')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                 <div class="mb-3">
                    <label for="picture_url" class="form-label">Picture</label>
                    <input type="file" class="form-control @error('picture_url') is-invalid @enderror" id="picture_url" name="picture_url">
                    @error('picture_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="wiki_url" class="form-label">Wiki URL</label>
                    <input type="url" class="form-control @error('wiki_url') is-invalid @enderror" id="wiki_url" name="wiki_url" value="{{ old('wiki_url', $faction->wiki_url) }}" placeholder="https://example.com/wiki">
                    @error('wiki_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <a href="{{ route('admin.factions.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Faction</button>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h1>Manage Ranks</h1>
            <a href="{{ route('admin.factions.ranks.create', $faction) }}" class="btn btn-primary">Add New Rank</a>
        </div>
        <div class="card-body">
            @include('admin.factions.ranks.index', ['faction' => $faction, 'ranks' => $faction->ranks()->paginate(5)])
        </div>
    </div>
</div>
@endsection
