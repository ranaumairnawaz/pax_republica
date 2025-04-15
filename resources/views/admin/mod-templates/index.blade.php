@extends('layouts.app')

@section('title', 'Admin: Manage Mod Templates')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Manage Mod Templates</h1>
            <a href="{{ route('admin.mod-templates.create') }}" class="btn btn-primary">Add New Mod Template</a>
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
                        <th>Type</th>
                        <th>Cost</th>
                        <th>Installation Difficulty</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($modTemplates as $modTemplate)
                    <tr>
                        <td>{{ $modTemplate->id }}</td>
                        <td>{{ $modTemplate->name }}</td>
                        <td>{{ $modTemplate->type }}</td>
                        <td>{{ $modTemplate->cost }}</td>
                        <td>{{ $modTemplate->installation_difficulty }}</td>
                        <td>
                            <a href="{{ route('admin.mod-templates.edit', $modTemplate->id) }}" class="btn btn-sm btn-warning me-1" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('admin.mod-templates.destroy', $modTemplate->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this mod template?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No mod templates found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($modTemplates->hasPages())
        <div class="card-footer">
            {{ $modTemplates->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
