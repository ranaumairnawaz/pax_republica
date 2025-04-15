@extends('layouts.app')

@section('title', 'Admin: Add New Skill')

@section('content')
<div class="container mt-4">
    <div class="card">
         <div class="card-header">
            <h1>Add New Skill</h1>
         </div>
         <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.skills.store') }}" method="POST">
                 @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Skill Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                     @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                 <div class="mb-3">
                    <label for="code" class="form-label">Skill Code</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required placeholder="e.g., PILOTING_SPACE">
                    <small class="form-text text-muted">A unique code for the skill (uppercase, underscores).</small>
                     @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="attribute_id" class="form-label">Base Attribute</label>
                    <select class="form-select @error('attribute_id') is-invalid @enderror" id="attribute_id" name="attribute_id" required>
                        <option value="" disabled {{ old('attribute_id') ? '' : 'selected' }}>Select Attribute</option>
                        @foreach ($attributes as $attribute)
                        <option value="{{ $attribute->id }}" {{ old('attribute_id') == $attribute->id ? 'selected' : '' }}>{{ $attribute->name }}</option>
                        @endforeach
                    </select>
                     @error('attribute_id')
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

                <a href="{{ route('admin.skills.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Add Skill</button>
            </form>
        </div>
    </div>
</div>
@endsection
