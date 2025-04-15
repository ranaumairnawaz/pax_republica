@extends('layouts.app')

@section('title', 'Edit Job')

@section('content')
    <div class="container">
        <h1>Edit Job</h1>
        <form method="POST" action="{{ route('admin.jobs.update', $job->id) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $job->title }}" required>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <select class="form-control" id="category" name="category" required>
                    <option value="ADVANCEMENT" {{ $job->category == 'ADVANCEMENT' ? 'selected' : '' }}>ADVANCEMENT</option>
                    <option value="APPLICATIONS" {{ $job->category == 'APPLICATIONS' ? 'selected' : '' }}>APPLICATIONS</option>
                    <option value="BUG_REPORTS" {{ $job->category == 'BUG_REPORTS' ? 'selected' : '' }}>BUG REPORTS</option>
                    <option value="FEEDBACK" {{ $job->category == 'FEEDBACK' ? 'selected' : '' }}>FEEDBACK</option>
                    <option value="PITCH" {{ $job->category == 'PITCH' ? 'selected' : '' }}>PITCH</option>
                    <option value="REWORK" {{ $job->category == 'REWORK' ? 'selected' : '' }}>REWORK</option>
                    <option value="TP" {{ $job->category == 'TP' ? 'selected' : '' }}>TP</option>
                </select>
            </div>
           <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="OPEN" {{ $job->status == 'OPEN' ? 'selected' : '' }}>OPEN</option>
                    <option value="CLOSED" {{ $job->status == 'CLOSED' ? 'selected' : '' }}>CLOSED</option>
                    <option value="APPROVED" {{ $job->status == 'APPROVED' ? 'selected' : '' }}>APPROVED</option>
                    <option value="REJECTED" {{ $job->status == 'REJECTED' ? 'selected' : '' }}>REJECTED</option>
                    <option value="CANCELED" {{ $job->status == 'CANCELED' ? 'selected' : '' }}>CANCELED</option>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ $job->description }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
