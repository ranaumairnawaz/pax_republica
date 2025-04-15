@extends('layouts.app')

@section('title', 'Admin: Manage Archetypes')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Manage Archetypes</h1>
            <a href="{{ route('admin.archetypes.create') }}" class="btn btn-primary">Add New Archetype</a>
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
                        <th>Build Data</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($archetypes as $archetype)
                    <tr>
                        <td>{{ $archetype->id }}</td>
                        <td>{{ $archetype->name }}</td>
                         <td>{{ $archetype->code }}</td>
                        <td>{{ Str::limit($archetype->description, 80) }}</td>
                        <td>{{ Str::limit($archetype->build_data, 80) }}</td>
                        <td>
                            <a href="{{ route('admin.archetypes.edit', $archetype->id) }}" class="btn btn-sm btn-warning me-1" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('admin.archetypes.destroy', $archetype->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this archetype?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No archetypes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($archetypes->hasPages())
        <div class="card-footer">
            {{ $archetypes->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
