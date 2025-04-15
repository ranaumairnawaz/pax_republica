@extends('layouts.app')

@section('title', 'Jobs - Pax Republica')

@section('content')
<div class="container mt-4">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Jobs</h5>
            <a href="{{ route('jobs.create') }}" class="btn btn-sm btn-light">
                <i class="bi bi-plus-circle me-1"></i> Create New Job
            </a>
        </div>
        <div class="card-body">
            @if ($jobs && count($jobs) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Location</th>
                                <th>Reward</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jobs as $job)
                                <tr>
                                    <td>{{ $job->title }}</td>
                                    <td>{{ $job->location }}</td>
                                    <td>{{ $job->reward }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('jobs.show', $job) }}" class="btn btn-outline-primary">
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
                    <i class="bi bi-briefcase" style="font-size: 3rem; color: #6a0dad;"></i>
                    <h4 class="mt-3">No Jobs Yet</h4>
                    <p class="text-muted mb-4">You haven't created any jobs yet. Find a job and start earning rewards!</p>
                    <a href="{{ route('jobs.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i> Create Your First Job
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
