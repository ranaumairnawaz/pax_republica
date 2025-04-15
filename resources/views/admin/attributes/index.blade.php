@extends('layouts.app')

@section('title', 'Admin: Manage Attributes')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Manage Attributes</h1>
            <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary">Add New Attribute</a>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attributes as $attribute)
                    <tr>
                        <td>{{ $attribute->id }}</td>
                        <td>{{ $attribute->name }}</td>
                        <td>{{ Str::limit($attribute->description, 80) }}</td>
                        <td>
                            <a href="{{ route('admin.attributes.edit', $attribute->id) }}" class="btn btn-sm btn-warning me-1" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('admin.attributes.destroy', $attribute->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this attribute? This might affect skills.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No attributes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($attributes->hasPages())
        <div class="card-footer">
            {{ $attributes->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
