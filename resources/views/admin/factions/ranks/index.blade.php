@extends('layouts.app')

@section('title', 'Admin: Manage Faction Ranks')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Manage Faction Ranks for {{ $faction->name }}</h1>
            <div>
                <a href="{{ route('admin.factions.edit', $faction) }}" class="btn btn-secondary me-2">Back to Faction</a>
                <a href="{{ route('admin.factions.ranks.create', $faction) }}" class="btn btn-primary">Add New Rank</a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Level</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ranks as $rank)
                    <tr>
                        <td>{{ $rank->id }}</td>
                        <td>{{ $rank->name }}</td>
                        <td>{{ $rank->level }}</td>
                        <td>
                            <a href="{{ route('admin.ranks.edit', $rank) }}" class="btn btn-sm btn-warning me-1" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('admin.ranks.destroy', $rank) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this rank?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No ranks found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($ranks->hasPages())
        <div class="card-footer">
            {{ $ranks->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
