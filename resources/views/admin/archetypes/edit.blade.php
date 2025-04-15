@extends('layouts.app')

@section('title', 'Admin: Edit Archetype')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h1>Edit Archetype: {{ $archetype->name }}</h1>
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
            <form action="{{ route('admin.archetypes.update', $archetype->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Archetype Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $archetype->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                 <div class="mb-3">
                    <label for="code" class="form-label">Archetype Code</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $archetype->code) }}" required placeholder="e.g., JEDI_GUARDIAN">
                     <small class="form-text text-muted">A unique code for the archetype (uppercase, underscores).</small>
                     @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $archetype->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="build_data" class="form-label">Build Data (JSON)</label>
                    <textarea class="form-control @error('build_data') is-invalid @enderror" id="build_data" name="build_data" rows="5">{{ old('build_data', $archetype->build_data) }}</textarea>
                    <small class="form-text text-muted">Enter build data as a valid JSON object.</small>
                    @error('build_data')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <a href="{{ route('admin.archetypes.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Archetype</button>
            </form>
        </div>
    </div>
</div>
@endsection
