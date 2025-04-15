@extends('layouts.app')

@section('title', 'Admin: Manage Species')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Manage Species</h1>
            <a href="{{ route('admin.species.create') }}" class="btn btn-primary">Add New Species</a>
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
                        <th>Wiki URL</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($species as $speciesItem)
                    <tr>
                        <td>{{ $speciesItem->id }}</td>
                        <td>{{ $speciesItem->name }}</td>
                        <td>{{ $speciesItem->code }}</td>
                        <td>{{ Str::limit($speciesItem->description, 80) }}</td>
                        <td>
                            @if($speciesItem->wiki_url)
                                <a href="{{ $speciesItem->wiki_url }}" target="_blank">Wiki Link</a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.species.edit', $speciesItem->id) }}" class="btn btn-sm btn-warning me-1" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('admin.species.destroy', $speciesItem->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this species? This might affect characters.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No species found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($species->hasPages())
        <div class="card-footer">
            {{ $species->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
