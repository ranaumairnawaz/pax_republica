@extends('layouts.app')

@section('title', 'Factions - Pax Republica')

@section('content')
<div class="container mt-4">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Factions</h5>
            <a href="{{ route('factions.create') }}" class="btn btn-sm btn-light">
                <i class="bi bi-plus-circle me-1"></i> Create New Faction
            </a>
        </div>
        <div class="card-body">
            @if ($factions && count($factions) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($factions as $faction)
                                <tr>
                                    <td>{{ $faction->name }}</td>
                                    <td>{{ $faction->description }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('factions.show', $faction) }}" class="btn btn-outline-primary">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-flag" style="font-size: 3rem; color: #6a0dad;"></i>
                    <h4 class="mt-3">No Factions Yet</h4>
                    <p class="text-muted mb-4">You haven't created any factions yet. Join or create one now!</p>
                    <a href="{{ route('factions.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i> Create Your First Faction
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
