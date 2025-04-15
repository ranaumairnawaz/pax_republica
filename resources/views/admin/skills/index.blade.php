@extends('layouts.app')

@section('title', 'Admin: Manage Skills')

@section('content')
<div class="container mt-4">

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

    <div class="card">
         <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Manage Skills</h1>
         <a href="{{ route('admin.skills.create') }}" class="btn btn-primary">Add New Skill</a>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Attribute</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($skills as $skill)
                    <tr>
                        <td>{{ $skill->id }}</td>
                        <td>{{ $skill->name }}</td>
                        <td>{{ $skill->attribute->name ?? 'N/A' }}</td> {{-- Display attribute name, handle if null --}}
                        <td>{{ Str::limit($skill->description, 80) }}</td> {{-- Limit description length --}}
                        <td>
                            <a href="{{ route('admin.skills.edit', $skill->id) }}" class="btn btn-sm btn-warning me-1" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('admin.skills.destroy', $skill->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this skill? This might affect characters and specializations.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No skills found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($skills->hasPages())
        <div class="card-footer">
            {{ $skills->links() }} {{-- Pagination --}}
        </div>
        @endif
    </div>
</div>
@endsection
