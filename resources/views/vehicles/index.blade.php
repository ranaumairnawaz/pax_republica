@extends('layouts.app')

@section('title', 'Vehicles - Pax Republica')

@section('content')
<div class="container mt-4">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Vehicles</h5>
            <a href="{{ route('vehicles.create') }}" class="btn btn-sm btn-light">
                <i class="bi bi-plus-circle me-1"></i> Create New Vehicle
            </a>
        </div>
        <div class="card-body">
            @if ($vehicles && count($vehicles) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Model</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vehicles as $vehicle)
                                <tr>
                                    <td>{{ $vehicle->name }}</td>
                                    <td>{{ $vehicle->model }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-outline-primary">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-truck" style="font-size: 3rem; color: #6a0dad;"></i>
                    <h4 class="mt-3">No Vehicles Yet</h4>
                    <p class="text-muted mb-4">You haven't created any vehicles yet. Start building your fleet!</p>
                    <a href="{{ route('vehicles.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i> Create Your First Vehicle
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
