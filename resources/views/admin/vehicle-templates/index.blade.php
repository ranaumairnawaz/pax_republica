@extends('layouts.app')

@section('title', 'Admin: Manage Vehicle Templates')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Manage Vehicle Templates</h1>
            <a href="{{ route('admin.vehicle-templates.create') }}" class="btn btn-primary">Add New Vehicle Template</a>
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
                        <th>Manufacturer</th>
                        <th>Model</th>
                        <th>Type</th>
                        <th>Size</th>
                        <th>Base Cost</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vehicleTemplates as $vehicleTemplate)
                    <tr>
                        <td>{{ $vehicleTemplate->id }}</td>
                        <td>{{ $vehicleTemplate->name }}</td>
                        <td>{{ $vehicleTemplate->manufacturer }}</td>
                        <td>{{ $vehicleTemplate->model }}</td>
                        <td>{{ $vehicleTemplate->type }}</td>
                        <td>{{ $vehicleTemplate->size }}</td>
                        <td>{{ $vehicleTemplate->base_cost }}</td>
                        <td>
                            <a href="{{ route('admin.vehicle-templates.edit', $vehicleTemplate->id) }}" class="btn btn-sm btn-warning me-1" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('admin.vehicle-templates.destroy', $vehicleTemplate->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this vehicle template?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No vehicle templates found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($vehicleTemplates->hasPages())
        <div class="card-footer">
            {{ $vehicleTemplates->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
