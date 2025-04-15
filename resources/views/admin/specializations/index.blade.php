@extends('layouts.app')

@section('title', 'Admin: Manage Specializations')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Manage Specializations</h1>
            <a href="{{ route('admin.specializations.create') }}" class="btn btn-primary">Add New Specialization</a>
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
                        <th>Skill</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($specializations as $specialization)
                    <tr>
                        <td>{{ $specialization->id }}</td>
                        <td>{{ $specialization->name }}</td>
                        <td>{{ $specialization->skill->name ?? 'N/A' }}</td>
                        <td>{{ Str::limit($specialization->description, 80) }}</td>
                        <td>
                            <a href="{{ route('admin.specializations.edit', $specialization->id) }}" class="btn btn-sm btn-warning me-1" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('admin.specializations.destroy', $specialization->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this specialization?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No specializations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($specializations->hasPages())
        <div class="card-footer">
            {{ $specializations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
