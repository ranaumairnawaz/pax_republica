@extends('layouts.app')

@section('title', 'Admin: Manage Characters')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Manage Characters</h1>
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
                        <th>User</th>
                        <th>Status</th>
                        <th>XP</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($characters as $character)
                    <tr>
                        <td>{{ $character->id }}</td>
                        <td>{{ $character->name }}</td>
                        <td>{{ $character->user->name }}</td>
                        <td>{{ $character->status }}</td>
                        <td>{{ $character->xp }}</td>
                        <td>
                            <a href="{{ route('admin.characters.edit', $character->id) }}" class="btn btn-sm btn-warning me-1" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('admin.characters.destroy', $character->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this character?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No characters found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($characters->hasPages())
        <div class="card-footer">
            {{ $characters->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
