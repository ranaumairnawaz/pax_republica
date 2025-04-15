@extends('layouts.app')

@section('title', 'Admin: Add New Specialization')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h1>Add New Specialization</h1>
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
            <form action="{{ route('admin.specializations.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Specialization Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="skill_id" class="form-label">Base Skill</label>
                    <select class="form-select @error('skill_id') is-invalid @enderror" id="skill_id" name="skill_id" required>
                        <option value="" disabled selected>Select Skill</option>
                        @foreach ($skills as $skill)
                        <option value="{{ $skill->id }}" {{ old('skill_id') == $skill->id ? 'selected' : '' }}>{{ $skill->name }}</option>
                        @endforeach
                    </select>
                    @error('skill_id')
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
                    <label for="code" class="form-label">Specialization Code</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required placeholder="e.g., PILOTING_SPACE_ADVANCED">
                    <small class="form-text text-muted">A unique code for the specialization (uppercase, underscores).</small>
                     @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="xp_cost" class="form-label">XP Cost</label>
                    <input type="number" class="form-control @error('xp_cost') is-invalid @enderror" id="xp_cost" name="xp_cost" value="{{ old('xp_cost', 0) }}" required>
                     @error('xp_cost')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="restricted" name="restricted" {{ old('restricted') ? 'checked' : '' }}>
                    <label class="form-check-label" for="restricted">Restricted</label>
                </div>

                <a href="{{ route('admin.specializations.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Add Specialization</button>
            </form>
        </div>
    </div>
</div>
@endsection
