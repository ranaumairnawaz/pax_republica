@extends('layouts.app')

@section('title', 'Admin: Add New Attribute')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h1>Add New Attribute</h1>
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
            <form action="{{ route('admin.attributes.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Attribute Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                 <div class="mb-3">
                    <label for="code" class="form-label">Attribute Code</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required placeholder="e.g., STRENGTH">
                    <small class="form-text text-muted">A unique code for the attribute (uppercase, underscores).</small>
                     @error('code')
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
                    <label for="is_primary" class="form-label">Is Primary</label>
                    <select class="form-select @error('is_primary') is-invalid @enderror" id="is_primary" name="is_primary">
                        <option value="0" {{ old('is_primary') === 0 ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('is_primary') === 1 ? 'selected' : '' }}>Yes</option>
                    </select>
                     @error('is_primary')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="default_value" class="form-label">Default Value</label>
                    <input type="number" class="form-control @error('default_value') is-invalid @enderror" id="default_value" name="default_value" value="{{ old('default_value', 1) }}" required>
                     @error('default_value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="min_value" class="form-label">Min Value</label>
                    <input type="number" class="form-control @error('min_value') is-invalid @enderror" id="min_value" name="min_value" value="{{ old('min_value', 1) }}" required>
                     @error('min_value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="max_value" class="form-label">Max Value</label>
                    <input type="number" class="form-control @error('max_value') is-invalid @enderror" id="max_value" name="max_value" value="{{ old('max_value', 10) }}" required>
                     @error('max_value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Add Attribute</button>
            </form>
        </div>
    </div>
</div>
@endsection
