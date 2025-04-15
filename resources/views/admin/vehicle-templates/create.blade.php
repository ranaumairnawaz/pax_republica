@extends('layouts.app')

@section('title', 'Admin: Add New Vehicle Template')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h1>Add New Vehicle Template</h1>
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
            <form action="{{ route('admin.vehicle-templates.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
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
                    <label for="manufacturer" class="form-label">Manufacturer</label>
                    <input type="text" class="form-control @error('manufacturer') is-invalid @enderror" id="manufacturer" name="manufacturer" value="{{ old('manufacturer') }}">
                    @error('manufacturer')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="model" class="form-label">Model</label>
                    <input type="text" class="form-control @error('model') is-invalid @enderror" id="model" name="model" value="{{ old('model') }}">
                    @error('model')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="">Select Type</option>
                        <option value="starfighter" {{ old('type') == 'starfighter' ? 'selected' : '' }}>Starfighter</option>
                        <option value="transport" {{ old('type') == 'transport' ? 'selected' : '' }}>Transport</option>
                        <option value="capital" {{ old('type') == 'capital' ? 'selected' : '' }}>Capital</option>
                        <option value="speeder" {{ old('type') == 'speeder' ? 'selected' : '' }}>Speeder</option>
                        <option value="walker" {{ old('type') == 'walker' ? 'selected' : '' }}>Walker</option>
                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="size" class="form-label">Size</label>
                    <select class="form-select @error('size') is-invalid @enderror" id="size" name="size" required>
                        <option value="">Select Size</option>
                        <option value="tiny" {{ old('size') == 'tiny' ? 'selected' : '' }}>Tiny</option>
                        <option value="small" {{ old('size') == 'small' ? 'selected' : '' }}>Small</option>
                        <option value="medium" {{ old('size') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="large" {{ old('size') == 'large' ? 'selected' : '' }}>Large</option>
                        <option value="huge" {{ old('size') == 'huge' ? 'selected' : '' }}>Huge</option>
                        <option value="gargantuan" {{ old('size') == 'gargantuan' ? 'selected' : '' }}>Gargantuan</option>
                    </select>
                    @error('size')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="crew_min" class="form-label">Crew Min</label>
                    <input type="number" class="form-control @error('crew_min') is-invalid @enderror" id="crew_min" name="crew_min" value="{{ old('crew_min', 1) }}" required>
                    @error('crew_min')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="crew_max" class="form-label">Crew Max</label>
                    <input type="number" class="form-control @error('crew_max') is-invalid @enderror" id="crew_max" name="crew_max" value="{{ old('crew_max') }}">
                    @error('crew_max')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="passengers" class="form-label">Passengers</label>
                    <input type="number" class="form-control @error('passengers') is-invalid @enderror" id="passengers" name="passengers" value="{{ old('passengers', 0) }}" required>
                    @error('passengers')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="cargo_capacity" class="form-label">Cargo Capacity (kg)</label>
                    <input type="number" class="form-control @error('cargo_capacity') is-invalid @enderror" id="cargo_capacity" name="cargo_capacity" value="{{ old('cargo_capacity', 0) }}" required>
                    @error('cargo_capacity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="consumables" class="form-label">Consumables (days)</label>
                    <input type="number" class="form-control @error('consumables') is-invalid @enderror" id="consumables" name="consumables" value="{{ old('consumables', 0) }}" required>
                    @error('consumables')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="speed" class="form-label">Speed</label>
                    <input type="text" class="form-control @error('speed') is-invalid @enderror" id="speed" name="speed" value="{{ old('speed') }}">
                    @error('speed')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="hyperspace_speed" class="form-label">Hyperspace Speed</label>
                    <input type="text" class="form-control @error('hyperspace_speed') is-invalid @enderror" id="hyperspace_speed" name="hyperspace_speed" value="{{ old('hyperspace_speed') }}">
                    @error('hyperspace_speed')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="hull_points" class="form-label">Hull Points</label>
                    <input type="number" class="form-control @error('hull_points') is-invalid @enderror" id="hull_points" name="hull_points" value="{{ old('hull_points') }}" required>
                    @error('hull_points')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="shield_points" class="form-label">Shield Points</label>
                    <input type="number" class="form-control @error('shield_points') is-invalid @enderror" id="shield_points" name="shield_points" value="{{ old('shield_points', 0) }}">
                    @error('shield_points')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="armor" class="form-label">Armor</label>
                    <input type="number" class="form-control @error('armor') is-invalid @enderror" id="armor" name="armor" value="{{ old('armor', 0) }}">
                    @error('armor')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="weapons" class="form-label">Weapons</label>
                    <textarea class="form-control @error('weapons') is-invalid @enderror" id="weapons" name="weapons" rows="3">{{ old('weapons') }}</textarea>
                    @error('weapons')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="base_cost" class="form-label">Base Cost</label>
                    <input type="number" class="form-control @error('base_cost') is-invalid @enderror" id="base_cost" name="base_cost" value="{{ old('base_cost') }}" required>
                    @error('base_cost')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input @error('is_restricted') is-invalid @enderror" id="is_restricted" name="is_restricted" {{ old('is_restricted') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_restricted">Restricted</label>
                    @error('is_restricted')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="image_url" class="form-label">Image</label>
                    <input type="file" class="form-control @error('image_url') is-invalid @enderror" id="image_url" name="image_url" accept="image/*">
                    @error('image_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <a href="{{ route('admin.vehicle-templates.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Add Vehicle Template</button>
            </form>
        </div>
    </div>
</div>
@endsection
