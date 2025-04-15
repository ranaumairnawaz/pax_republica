@extends('layouts.app')

@section('title', 'Admin Dashboard - Pax Republica')

@section('content')
<div class="container mt-4">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <!-- Account Information Card -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Admin Account</h5>
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $user->name }}</h5>
                    <p class="card-text"><strong>Account Name:</strong> {{ $user->account_name }}</p>
                    <p class="card-text"><strong>Email:</strong> {{ $user->email }}</p>
                    <p class="card-text"><strong>Timezone:</strong> {{ $user->timezone }}</p>
                    <p class="card-text"><strong>Account Type:</strong> {{ ucfirst($user->user_type) }}</p>
                    <p class="card-text"><strong>Last Login:</strong> {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</p>
                    <a href="#" class="btn btn-outline-dark btn-sm">Edit Profile</a>
                </div>
            </div>

            <!-- System Stats Card -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">System Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Total Users
                            <span class="badge bg-primary rounded-pill">{{ \App\Models\User::count() }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Total Characters
                            <span class="badge bg-primary rounded-pill">{{ \App\Models\Character::count() }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Active Scenes
                            <span class="badge bg-primary rounded-pill">{{ \App\Models\Scene::where('status', 'active')->count() }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Pending Approvals
                            <span class="badge bg-warning text-dark rounded-pill">{{ $pendingCharacters->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Pending Character Approvals Card -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Pending Character Approvals</h5>
                </div>
                <div class="card-body">
                    @if ($pendingCharacters->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Player</th>
                                        <th>Submitted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendingCharacters as $character)
                                        <tr>
                                            <td>{{ $character->name }}</td>
                                            <td>{{ $character->user->account_name }}</td>
                                            <td>{{ $character->updated_at->diffForHumans() }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('characters.show', $character) }}" class="btn btn-outline-primary">Review</a>
                                                    <form action="{{ route('characters.approve', $character) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-success">Approve</button>
                                                    </form>
                                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $character->id }}">Reject</button>
                                                </div>

                                                <!-- Reject Modal -->
                                                <div class="modal fade" id="rejectModal-{{ $character->id }}" tabindex="-1" aria-labelledby="rejectModalLabel-{{ $character->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="{{ route('characters.reject', $character) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="rejectModalLabel-{{ $character->id }}">Reject Character: {{ $character->name }}</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label for="rejection_reason-{{ $character->id }}" class="form-label">Reason for Rejection:</label>
                                                                        <textarea class="form-control" id="rejection_reason-{{ $character->id }}" name="rejection_reason" rows="3" required minlength="10"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            No pending character approvals at this time.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Character Change Requests Card -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Character Change Requests</h5>
                </div>
                <div class="card-body">
                    @if ($recentChanges->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Character</th>
                                        <th>Player</th>
                                        <th>Type</th>
                                        <th>Requested</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentChanges as $character)
                                        @foreach ($character->changeLogs()->where('approved', false)->get() as $change)
                                            <tr>
                                                <td>{{ $character->name }}</td>
                                                <td>{{ $character->user->account_name }}</td>
                                                <td>{{ ucfirst($change->change_type) }}</td>
                                                <td>{{ $change->created_at->diffForHumans() }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="#" class="btn btn-outline-primary">Review</a>
                                                        <form action="{{ route('admin.changes.approve', $change) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-success">Approve</button>
                                                        </form>
                                                        <form action="{{ route('admin.changes.reject', $change) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-danger">Reject</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            No pending character change requests at this time.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Admin Tools Card -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Admin Tools</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="d-grid">
                                <a href="#" class="btn btn-outline-primary">User Management</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-grid">
                                <a href="#" class="btn btn-outline-primary">Faction Management</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-grid">
                                <a href="#" class="btn btn-outline-primary">Skills & Traits</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-grid">
                                <a href="#" class="btn btn-outline-primary">Location Management</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-grid">
                                <a href="#" class="btn btn-outline-primary">Job Categories</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-grid">
                                <a href="#" class="btn btn-outline-primary">System Settings</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
