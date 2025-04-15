@extends('layouts.app')

@section('title', 'Admin Job Management')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            Admin Job Management
            <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary float-right">Create Job</a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jobs as $job)
                    <tr>
                        <td>{{ $job->title }}</td>
                        <td>{{ $job->category }}</td>
                        <td>{{ $job->status }}</td>
                        <td>
                            <a href="{{ route('jobs.show', $job) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('admin.jobs.edit', $job) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form method="POST" action="{{ route('admin.jobs.destroy', $job) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
