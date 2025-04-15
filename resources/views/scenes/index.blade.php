@extends('layouts.app')

@section('title', 'Scenes - Pax Republica')

@section('content')
<div class="container mt-4">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Scenes</h5>
            <a href="{{ route('scenes.create') }}" class="btn btn-sm btn-light">
                <i class="bi bi-plus-circle me-1"></i> Create New Scene
            </a>
        </div>
        <div class="card-body">
            @if ($scenes && count($scenes) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Location</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($scenes as $scene)
                                <tr>
                                    <td>{{ $scene->title }}</td>
                                    <td>{{ $scene->location ? $scene->location->name : 'Unknown' }}</td>
                                    <td>{{ $scene->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('scenes.show', $scene) }}" class="btn btn-outline-primary">
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
                    <i class="bi bi-camera-reels" style="font-size: 3rem; color: #6a0dad;"></i>
                    <h4 class="mt-3">No Scenes Yet</h4>
                    <p class="text-muted mb-4">You haven't created any scenes yet. Start your roleplaying journey!</p>
                    <a href="{{ route('scenes.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i> Create Your First Scene
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
