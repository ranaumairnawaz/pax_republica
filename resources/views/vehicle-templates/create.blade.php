@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Create Vehicle Template</h2>
                    <a href="{{ route('vehicle-templates.index') }}" class="btn btn-secondary">Back to Templates</a>
                </div>

                <div class="card-body">
                    <form action="{{ route('vehicle-templates.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="type" class="form-label">Type</label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="fighter" {{ old('type') === 'fighter' ? 'selected' : '' }}>Fighter</option>
                                        <option value="bomber" {{ old('type') === 'bomber' ? 'selected' : '' }}>Bomber</option>
                                        <option value="transport" {{ old('type') === 'transport' ? 'selected' : '' }}>Transport</option>
                                        <option value="freighter" {{ old('type') === 'freighter' ? 'selected' : '' }}>Freighter</option>
                                        <option value="corvette" {{ old('type') === 'corvette' ? 'selected' : '' }}>Corvette</option>
                                        <option value="frigate" {{ old('type') === 'frigate' ? 'selected' : '' }}>Frigate</option>
                                        <option value="cruiser" {{ old('type') === 'cruiser' ? 'selected' : '' }}>Cruiser</option>
                                        <option value="capital" {{ old('type') === 'capital' ? 'selected' : '' }}>Capital Ship</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="size" class="form-label">Size</label>
                                    <select class="form-select @error('size') is-invalid @enderror" id="size" name="size" required>
                                        <option value="">Select Size</option>
                                        <option value="small" {{ old('size') === 'small' ? 'selected' : '' }}>Small</option>
                                        <option value="medium" {{ old('size') === 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="large" {{ old('size') === 'large' ? 'selected' : '' }}>Large</option>
                                        <option value="huge" {{ old('size') === 'huge' ? 'selected' : '' }}>Huge</option>
                                    </select>
                                    @error('size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="manufacturer" class="form-label">Manufacturer</label>
                                    <input type="text" class="form-control @error('manufacturer') is-invalid @enderror" id="manufacturer" name="manufacturer" value="{{ old('manufacturer') }}" required>
                                    @error('manufacturer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="model" class="form-label">Model</label>
                                    <input type="text" class="form-control @error('model') is-invalid @enderror" id="model" name="model" value="{{ old('model') }}" required>
                                    @error('model')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="crew_min" class="form-label">Minimum Crew</label>
                                    <input type="number" class="form-control @error('crew_min') is-invalid @enderror" id="crew_min" name="crew_min" value="{{ old('crew_min') }}" required min="1">
                                    @error('crew_min')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="crew_max" class="form-label">Maximum Crew</label>
                                    <input type="number" class="form-control @error('crew_max') is-invalid @enderror" id="crew_max" name="crew_max" value="{{ old('crew_max') }}" required min="1">
                                    @error('crew_max')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="passengers" class="form-label">Passenger Capacity</label>
                                    <input type="number" class="form-control @error('passengers') is-invalid @enderror" id="passengers" name="passengers" value="{{ old('passengers') }}" required min="0">
                                    @error('passengers')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="cargo_capacity" class="form-label">Cargo Capacity (tons)</label>
                                    <input type="number" class="form-control @error('cargo_capacity') is-invalid @enderror" id="cargo_capacity" name="cargo_capacity" value="{{ old('cargo_capacity') }}" required min="0">
                                    @error('cargo_capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="consumables" class="form-label">Consumables (days)</label>
                                    <input type="number" class="form-control @error('consumables') is-invalid @enderror" id="consumables" name="consumables" value="{{ old('consumables') }}" required min="1">
                                    @error('consumables')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="speed" class="form-label">Speed (MGLT)</label>
                                    <input type="number" class="form-control @error('speed') is-invalid @enderror" id="speed" name="speed" value="{{ old('speed') }}" required min="0">
                                    @error('speed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="hyperspace_speed" class="form-label">Hyperspace Speed (Class)</label>
                                    <input type="number" class="form-control @error('hyperspace_speed') is-invalid @enderror" id="hyperspace_speed" name="hyperspace_speed" value="{{ old('hyperspace_speed') }}" required min="1" step="0.1">
                                    @error('hyperspace_speed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="hull_points" class="form-label">Hull Points</label>
                                    <input type="number" class="form-control @error('hull_points') is-invalid @enderror" id="hull_points" name="hull_points" value="{{ old('hull_points') }}" required min="1">
                                    @error('hull_points')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="shield_points" class="form-label">Shield Points</label>
                                    <input type="number" class="form-control @error('shield_points') is-invalid @enderror" id="shield_points" name="shield_points" value="{{ old('shield_points') }}" required min="0">
                                    @error('shield_points')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="armor" class="form-label">Armor</label>
                                    <input type="number" class="form-control @error('armor') is-invalid @enderror" id="armor" name="armor" value="{{ old('armor') }}" required min="0">
                                    @error('armor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="base_cost" class="form-label">Base Cost (credits)</label>
                                    <input type="number" class="form-control @error('base_cost') is-invalid @enderror" id="base_cost" name="base_cost" value="{{ old('base_cost') }}" required min="0">
                                    @error('base_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="image_url" class="form-label">Image URL</label>
                                    <input type="url" class="form-control @error('image_url') is-invalid @enderror" id="image_url" name="image_url" value="{{ old('image_url') }}">
                                    @error('image_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input @error('is_restricted') is-invalid @enderror" id="is_restricted" name="is_restricted" value="1" {{ old('is_restricted') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_restricted">Restricted Access</label>
                                        @error('is_restricted')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="weapons" class="form-label">Weapons</label>
                            <textarea class="form-control @error('weapons') is-invalid @enderror" id="weapons" name="weapons" rows="3">{{ old('weapons') }}</textarea>
                            @error('weapons')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('vehicle-templates.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Template</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 