@extends('layouts.app')

@section('title', 'Admin: Edit Attribute')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h1>Edit Attribute: {{ $attribute->name }}</h1>
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
            <form action="{{ route('admin.attributes.update', $attribute->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Attribute Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $attribute->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                 <div class="mb-3">
                    <label for="code" class="form-label">Attribute Code</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $attribute->code) }}" required placeholder="e.g., STRENGTH">
                     <small class="form-text text-muted">A unique code for the attribute (uppercase, underscores).</small>
                     @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $attribute->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="is_primary" class="form-label">Is Primary</label>
                    <select class="form-select @error('is_primary') is-invalid @enderror" id="is_primary" name="is_primary">
                        <option value="0" {{ old('is_primary', $attribute->is_primary) == 0 ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('is_primary', $attribute->is_primary) == 1 ? 'selected' : '' }}>Yes</option>
                    </select>
                     @error('is_primary')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="default_value" class="form-label">Default Value</label>
                    <input type="number" class="form-control @error('default_value') is-invalid @enderror" id="default_value" name="default_value" value="{{ old('default_value', $attribute->default_value) }}" required>
                     @error('default_value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="min_value" class="form-label">Min Value</label>
                    <input type="number" class="form-control @error('min_value') is-invalid @enderror" id="min_value" name="min_value" value="{{ old('min_value', $attribute->min_value) }}" required>
                     @error('min_value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="max_value" class="form-label">Max Value</label>
                    <input type="number" class="form-control @error('max_value') is-invalid @enderror" id="max_value" name="max_value" value="{{ old('max_value', $attribute->max_value) }}" required>
                     @error('max_value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Attribute</button>
            </form>
        </div>
    </div>
</div>
@endsection
