@extends('layouts.app')

@section('title', 'Admin: Add New Faction Rank')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h1>Add New Rank for {{ $faction->name }}</h1>
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
            <form action="{{ route('admin.factions.ranks.store', $faction) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Rank Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="level" class="form-label">Rank Level</label>
                    <input type="number" class="form-control @error('level') is-invalid @enderror" id="level" name="level" value="{{ old('level') }}" required>
                    @error('level')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <a href="{{ route('admin.factions.ranks.index', $faction) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Add Rank</button>
            </form>
        </div>
    </div>
</div>
@endsection
