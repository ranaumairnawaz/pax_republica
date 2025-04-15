@extends('layouts.app')

@section('title', 'My Characters - Pax Republica')

@section('content')
<div class="container mt-4">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Your Characters</h5>
            <a href="{{ route('characters.create') }}" class="btn btn-sm btn-light">
                <i class="bi bi-plus-circle me-1"></i> Create New Character
            </a>
        </div>
        <div class="card-body">
            @if ($characters->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Species</th>
                                <th>Status</th>
                                <th>XP</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($characters as $character)
                                <tr>
                                    <td>{{ $character->name }}</td>
                                    <td>{{ $character->species ? $character->species->name : 'Not Selected' }}</td>
                                    <td>
                                        @if ($character->isInProgress())
                                            <span class="badge bg-warning text-dark">In Progress</span>
                                        @elseif ($character->isPending())
                                            <span class="badge bg-info">Pending Approval</span>
                                        @elseif ($character->isActive())
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    </td>
                                    <td>{{ $character->xp }}</td>
                                    <td>{{ $character->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('characters.show', $character) }}" class="btn btn-outline-primary">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                            @if ($character->isInProgress())
                                                <a href="{{ route('characters.edit', $character) }}" class="btn btn-outline-secondary">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                            @endif
                                            @if ($character->isInProgress())
                                                <form action="{{ route('characters.submit', $character) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success">
                                                        <i class="bi bi-check-circle"></i> Submit
                                                    </button>
                                                </form>
                                            @endif
                                            @if ($character->isActive())
                                                <a href="#" class="btn btn-outline-info">
                                                <i class="bi bi-arrow-up-circle"></i> Spend XP
                                            </a>
                                        @endif
                                        {{-- Add Delete Button --}}
                                        <form action="{{ route('characters.destroy', $character) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this character? This action cannot be undone.')">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-person-badge" style="font-size: 3rem; color: #6a0dad;"></i>
                    <h4 class="mt-3">No Characters Yet</h4>
                    <p class="text-muted mb-4">You haven't created any characters yet. Start your journey in the Star Wars universe!</p>
                    <a href="{{ route('characters.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i> Create Your First Character
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
