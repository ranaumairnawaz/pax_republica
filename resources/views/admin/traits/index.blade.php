@extends('layouts.app')

@section('title', 'Admin: Manage Traits')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Manage Traits</h1>
            <a href="{{ route('admin.traits.create') }}" class="btn btn-primary">Add New Trait</a>
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
                        <th>Modifiers</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($traitModels as $trait)
                    <tr>
                        <td>{{ $trait->id }}</td>
                        <td>{{ $trait->name }}</td>
                        <td>{{ $trait->code }}</td>
                        <td>{{ Str::limit($trait->description, 80) }}</td>
                        <td>{{ Str::limit($trait->modifiers, 80) }}</td>
                        <td>
                            <a href="{{ route('admin.traits.edit', $trait->id) }}" class="btn btn-sm btn-warning me-1" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('admin.traits.destroy', $trait->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this trait?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No traits found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($traitModels->hasPages())
        <div class="card-footer">
            {{ $traitModels->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
