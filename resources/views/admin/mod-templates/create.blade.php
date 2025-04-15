@extends('layouts.app')

@section('title', 'Admin: Add New Mod Template')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h1>Add New Mod Template</h1>
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
            <form action="{{ route('admin.mod-templates.store') }}" method="POST">
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
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="">Select Type</option>
                        <option value="weapon" {{ old('type') == 'weapon' ? 'selected' : '' }}>Weapon</option>
                        <option value="defense" {{ old('type') == 'defense' ? 'selected' : '' }}>Defense</option>
                        <option value="propulsion" {{ old('type') == 'propulsion' ? 'selected' : '' }}>Propulsion</option>
                        <option value="sensor" {{ old('type') == 'sensor' ? 'selected' : '' }}>Sensor</option>
                        <option value="utility" {{ old('type') == 'utility' ? 'selected' : '' }}>Utility</option>
                        <option value="special" {{ old('type') == 'special' ? 'selected' : '' }}>Special</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="effects" class="form-label">Effects (JSON)</label>
                    <textarea class="form-control @error('effects') is-invalid @enderror" id="effects" name="effects" rows="5">{{ old('effects') }}</textarea>
                    <small class="text-muted">Enter a valid JSON object, e.g., {"damage": 10, "range": 5}</small>
                    @error('effects')
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
                    <label for="cost" class="form-label">Cost</label>
                    <input type="number" class="form-control @error('cost') is-invalid @enderror" id="cost" name="cost" value="{{ old('cost') }}" required>
                    @error('cost')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="installation_difficulty" class="form-label">Installation Difficulty</label>
                    <select class="form-select @error('installation_difficulty') is-invalid @enderror" id="installation_difficulty" name="installation_difficulty" required>
                        <option value="">Select Difficulty</option>
                        <option value="1" {{ old('installation_difficulty') == '1' ? 'selected' : '' }}>Easy</option>
                        <option value="2" {{ old('installation_difficulty') == '2' ? 'selected' : '' }}>Medium</option>
                        <option value="3" {{ old('installation_difficulty') == '3' ? 'selected' : '' }}>Hard</option>
                        <option value="4" {{ old('installation_difficulty') == '4' ? 'selected' : '' }}>Very Hard</option>
                    </select>
                    @error('installation_difficulty')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <a href="{{ route('admin.mod-templates.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Add Mod Template</button>
            </form>
        </div>
    </div>
</div>
@endsection
