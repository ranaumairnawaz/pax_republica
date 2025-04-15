@extends('layouts.app')

@section('title', 'Create Job')

@section('content')
    <div class="container">
        <h1>Create Job</h1>
        <form method="POST" action="{{ route('admin.jobs.store') }}">
            @csrf
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <select class="form-control" id="category" name="category" required>
                    <option value="ADVANCEMENT">ADVANCEMENT</option>
                    <option value="APPLICATIONS">APPLICATIONS</option>
                    <option value="BUG_REPORTS">BUG REPORTS</option>
                    <option value="FEEDBACK">FEEDBACK</option>
                    <option value="PITCH">PITCH</option>
                    <option value="REWORK">REWORK</option>
                    <option value="TP">TP</option>
                </select>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="OPEN">OPEN</option>
                    <option value="CLOSED">CLOSED</option>
                    <option value="APPROVED">APPROVED</option>
                    <option value="REJECTED">REJECTED</option>
                    <option value="CANCELED">CANCELED</option>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
