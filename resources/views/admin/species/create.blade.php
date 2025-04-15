@extends('layouts.app')

@section('title', 'Admin: Add New Species')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h1>Add New Species</h1>
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
            <form action="{{ route('admin.species.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Species Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="modifiers" class="form-label">Modifiers (JSON)</label>
                    <textarea class="form-control @error('modifiers') is-invalid @enderror" id="modifiers" name="modifiers" rows="3">{{ old('modifiers') }}</textarea>
                    <small class="form-text text-muted">Enter modifiers as a valid JSON object.</small>
                    @error('modifiers')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="wiki_url" class="form-label">Wiki URL</label>
                    <input type="url" class="form-control @error('wiki_url') is-invalid @enderror" id="wiki_url" name="wiki_url" value="{{ old('wiki_url') }}" placeholder="https://example.com">
                    @error('wiki_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <a href="{{ route('admin.species.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Add Species</button>
            </form>
        </div>
    </div>
</div>
@endsection
