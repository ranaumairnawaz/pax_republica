@extends('layouts.app')

@section('title', 'Admin: Pending Characters')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Pending Characters</h1>
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
                        <td>{{ $character->xp }}</td>
                        <td>
                            <form action="{{ route('admin.characters.approve', $character->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success me-1" title="Approve"><i class="bi bi-check-fill"></i></button>
                            </form>
                            <form action="{{ route('admin.characters.reject', $character->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to reject this character?');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger" title="Reject"><i class="bi bi-x-fill"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No pending characters found.</td>
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
