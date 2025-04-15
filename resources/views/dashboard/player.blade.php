@extends('layouts.app')

@section('title', 'Player Dashboard - Pax Republica')

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
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Account Information</h5>
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $user->name }}</h5>
                    <p class="card-text"><strong>Account Name:</strong> {{ $user->account_name }}</p>
                    <p class="card-text"><strong>Email:</strong> {{ $user->email }}</p>
                    <p class="card-text"><strong>Timezone:</strong> {{ $user->timezone }}</p>
                    <p class="card-text"><strong>Account Type:</strong> {{ ucfirst($user->user_type) }}</p>
                    <p class="card-text"><strong>Last Login:</strong> {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">Edit Profile</a>
                </div>
            </div>

            <!-- Notifications Card -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Notifications</h5>
                </div>
                <div class="card-body">
                    @if ($notifications->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach ($notifications as $notification)
                                <li class="list-group-item">
                                    <a href="{{ $notification->link }}" class="text-decoration-none">
                                        {{ $notification->message }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-4">
                            <p class="mb-0">No new notifications.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Characters Card -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Your Characters</h5>
                    <a href="{{ route('characters.create') }}" class="btn btn-sm btn-light">Create New Character</a>
                </div>
                <div class="card-body">
                    @if ($characters->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>XP</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($characters as $character)
                                        <tr>
                                            <td>{{ $character->name }}</td>
                                            <td>
                                                @if ($character->isInProgress())
                                                    <span class="badge bg-warning text-dark">In Progress</span>
                                                @elseif ($character->isPending())
                                                    <span class="badge bg-info">Pending Approval</span>
                                                @elseif ($character->isActive())
                                                    <span class="badge bg-success">Active</span>
                                                @endif
                                            </td>
                                            <td>{{ $character->xp }}</td>
                                            <td>{{ $character->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('characters.show', $character) }}" class="btn btn-outline-primary">View</a>
                                                    @if ($character->isInProgress())
                                                        <a href="{{ route('characters.edit', $character) }}" class="btn btn-outline-secondary">Edit</a>
                                                    @endif
                                                    @if ($character->isActive())
                                                        <a href="#" class="btn btn-outline-success">Spend XP</a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="mb-3">You haven't created any characters yet.</p>
                            <a href="{{ route('characters.create') }}" class="btn btn-primary">Create Your First Character</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Scenes Card -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Scenes</h5>
                    <a href="{{ route('scenes.create') }}" class="btn btn-sm btn-light">Create New Scene</a>
                </div>
                <div class="card-body">
                    @if ($recentScenes->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach ($recentScenes as $scene)
                                <li class="list-group-item">
                                    <a href="{{ route('scenes.show', $scene) }}" class="text-decoration-none">
                                        {{ $scene->title }} - {{ $scene->created_at->format('M d, Y') }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-4">
                            <p class="mb-0">No recent scenes.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
