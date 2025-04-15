@extends('layouts.app')

@section('title', 'Admin: Edit Character')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h1>Edit Character: {{ $character->name }}</h1>
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
            <form action="{{ route('admin.characters.update', $character->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $character->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="user_id" class="form-label">User</label>
                    <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $character->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="inprogress" {{ old('status', $character->status) == 'inprogress' ? 'selected' : '' }}>In Progress</option>
                        <option value="pending" {{ old('status', $character->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ old('status', $character->status) == 'active' ? 'selected' : '' }}>Active</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="xp" class="form-label">XP</label>
                    <input type="number" class="form-control @error('xp') is-invalid @enderror" id="xp" name="xp" value="{{ old('xp', $character->xp) }}" required>
                    @error('xp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="details" class="form-label">Details (JSON)</label>
                    <textarea class="form-control @error('details') is-invalid @enderror" id="details" name="details" rows="5">{{ old('details', $character->details) }}</textarea>
                    <small class="text-muted">Enter a valid JSON object, e.g., {"strength": 10, "agility": 5}</small>
                    @error('details')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <a href="{{ route('admin.characters.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Character</button>
            </form>
        </div>
    </div>
</div>
@endsection
