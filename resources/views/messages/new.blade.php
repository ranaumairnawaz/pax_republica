@extends('layouts.app')

@section('title', 'New Chat - Pax Republica')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">Start a New Chat</h1>
                <div>
                    <a href="{{ route('chat.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-light">Select a User to Chat With</div>
                <div class="card-body">
                    @if($users->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                            <p class="mt-3 text-muted">No users available to chat with.</p>
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($users as $user)
                                <a href="{{ route('chat.index', ['user' => $user->id]) }}" class="list-group-item list-group-item-action d-flex align-items-center py-3">
                                    <div class="position-relative">
                                        <div class="avatar rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center">
                                            {{ strtoupper(substr($user->account_name, 0, 1)) }}
                                        </div>
                                        @if($user->isOnline())
                                            <span class="position-absolute bottom-0 end-0 translate-middle-x">
                                                <span class="online-indicator"></span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-0">{{ $user->account_name }}</h6>
                                        <small class="text-muted">
                                            {{ $user->isOnline() ? 'Online' : ($user->last_activity_at ? 'Last seen ' . $user->last_activity_at->diffForHumans() : 'Offline') }}
                                        </small>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar {
    width: 40px;
    height: 40px;
    font-size: 1.2rem;
}

.online-indicator {
    width: 10px;
    height: 10px;
    background-color: #31a24c;
    border-radius: 50%;
    border: 2px solid white;
    display: inline-block;
}
</style>
@endsection 