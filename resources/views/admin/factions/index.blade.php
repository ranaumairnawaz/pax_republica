@extends('layouts.app')

@section('title', 'Admin: Manage Factions')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Manage Factions</h1>
            <a href="{{ route('admin.factions.create') }}" class="btn btn-primary">Add New Faction</a>
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
                        <th>Code</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($factions as $faction)
                    <tr>
                        <td>{{ $faction->id }}</td>
                        <td>{{ $faction->name }}</td>
                        <td>{{ $faction->code }}</td>
                        <td>{{ Str::limit($faction->description, 80) }}</td>
                        <td>
                            <a href="{{ route('admin.factions.edit', $faction->id) }}" class="btn btn-sm btn-warning me-1" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('admin.factions.destroy', $faction->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this faction? This might affect characters.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No factions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($factions->hasPages())
        <div class="card-footer">
            {{ $factions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
