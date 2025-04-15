@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mt-4">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        {{-- Account Info (Visible to All) --}}
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-person-circle me-2"></i>Account Information</div>
                <div class="card-body">
                    <h5 class="card-title">{{ Auth::user()->name }}</h5>
                    <p class="card-text mb-1"><strong>Account:</strong> {{ Auth::user()->account_name }}</p>
                    <p class="card-text mb-1"><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    <p class="card-text mb-1"><strong>Timezone:</strong> {{ Auth::user()->timezone }}</p>
                    <p class="card-text mb-1"><strong>Type:</strong> {{ Auth::user()->is_admin ? 'Administrator' : 'Player' }}</p>
                    {{-- Add Last Login if available in User model --}}
                    <p class="card-text"><strong>Last Login:</strong> {{ Auth::user()->last_login_at ? Auth::user()->last_login_at->diffForHumans() : 'Never' }}</p>
                </div>
                <div class="card-footer">
                     <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-secondary">Edit Profile</a>
                </div>
            </div>
        </div>

        {{-- Admin Dashboard --}}
        @if(auth()->user()->is_admin)
            {{-- Character Related --}}
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center" style="background: linear-gradient(45deg, #9d4edd, #ff8c42); color: white;">
                        <i class="bi bi-database-fill-gear me-2"></i>Character Data
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><a href="{{ route('admin.skills.index') }}">Skills</a></li>
                            <li class="list-group-item"><a href="{{ route('admin.attributes.index') }}">Attributes</a></li>
                            <li class="list-group-item"><a href="{{ route('admin.specializations.index') }}">Specializations</a></li>
                            <li class="list-group-item"><a href="{{ route('admin.traits.index') }}">Traits</a></li>
                            <li class="list-group-item"><a href="{{ route('admin.species.index') }}">Species (Races)</a></li>
                            <li class="list-group-item"><a href="{{ route('admin.archetypes.index') }}">Archetypes</a></li>
                        </ul>
                    </div>
                </div>
            </div>
    
            {{-- Faction Related --}}
            <div class="col-md-4 mb-3">
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center" style="background: linear-gradient(45deg, #9d4edd, #ff8c42); color: white;">
                        <i class="bi bi-flag-fill me-2"></i>Factions
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><a href="{{ route('admin.factions.index') }}">Factions & Ranks</a></li>
                            {{-- Add link to Faction Rank management if separate route exists, currently nested --}}
                        </ul>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header d-flex align-items-center" style="background: linear-gradient(45deg, #9d4edd, #ff8c42); color: white;">
                        <i class="bi bi-truck me-2"></i>Vehicles & Mods
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><a href="{{ route('admin.vehicle-templates.index') }}">Vehicle Templates</a></li>
                            <li class="list-group-item"><a href="{{ route('admin.mod-templates.index') }}">Mod Templates</a></li>
                        </ul>
                    </div>
                </div>
            </div>
    
            {{-- World Related --}}
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center" style="background: linear-gradient(45deg, #9d4edd, #ff8c42); color: white;">
                        <i class="bi bi-globe-americas me-2"></i>World Data
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><a href="{{ route('admin.locations.index') }}">Locations</a></li>
                            {{-- Add Plots management link if needed --}}
                        </ul>
                    </div>
                </div>
            </div>
    
             {{-- NPC Management --}}
             <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center" style="background: linear-gradient(45deg, #9d4edd, #ff8c42); color: white;">
                        <i class="bi bi-person-bounding-box me-2"></i>NPCs
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><a href="{{ route('admin.npcs.index') }}">Manage NPCs</a></li>
                        </ul>
                    </div>
                </div>
            </div>
    
             {{-- User Management --}}
             <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center" style="background: linear-gradient(45deg, #9d4edd, #ff8c42); color: white;">
                        <i class="bi bi-people-fill me-2"></i>User Management
                    </div>
                    <div class="card-body">
                        <p>Manage user accounts and permissions.</p>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">Manage Users</a>
                    </div>
                </div>
            </div>
    
            {{-- Character Approvals --}}
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center" style="background: linear-gradient(45deg, #9d4edd, #ff8c42); color: white;">
                        <i class="bi bi-person-check-fill me-2"></i>Character Approvals
                    </div>
                    <div class="card-body">
                        <p>Approve or reject pending character applications.</p>
                        <a href="{{ route('admin.characters.pending') }}" class="btn btn-sm btn-primary">Review Characters</a>
                    </div>
                </div>
            </div>
    
             {{-- Job Management --}}
             <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center" style="background: linear-gradient(45deg, #9d4edd, #ff8c42); color: white;">
                        <i class="bi bi-briefcase-fill me-2"></i>Job Management
                    </div>
                    <div class="card-body">
                        <p>Manage and assign jobs.</p>
                        <a href="{{ route('admin.jobs.index') }}" class="btn btn-sm btn-primary">Manage Jobs</a>
                    </div>
                </div>
            </div>

        {{-- Player Dashboard --}}
        @else
            <div class="col-lg-8 col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-person-badge me-2"></i>Your Characters</span>
                        <a href="{{ route('characters.create') }}" class="btn btn-sm btn-primary">Create New Character</a>
                    </div>
                    <div class="card-body">
                        @if (Auth::user()->characters->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Status</th>
                                            {{-- Add other relevant player character info if needed --}}
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (Auth::user()->characters as $character)
                                            <tr>
                                                <td>{{ $character->name }}</td>
                                                <td>
                                                    {{-- Assuming status methods exist on Character model --}}
                                                    @if ($character->status === 'INPROGRESS') {{-- Adjust based on actual status values/methods --}}
                                                        <span class="badge bg-warning text-dark">In Progress</span>
                                                    @elseif ($character->status === 'PENDING')
                                                        <span class="badge bg-info">Pending Approval</span>
                                                    @elseif ($character->status === 'ACTIVE')
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                         <span class="badge bg-secondary">{{ $character->status }}</span>
                                                    @endif
                                                </td>
                                                {{-- <td>{{ $character->xp ?? 0 }}</td> --}}
                                                <td>{{ $character->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <a href="{{ route('characters.show', $character) }}" class="btn btn-sm btn-outline-primary">View</a>
                                                    @if ($character->status === 'INPROGRESS') {{-- Adjust based on actual status values/methods --}}
                                                        {{-- <a href="{{ route('characters.edit', $character) }}" class="btn btn-sm btn-outline-secondary">Edit</a> --}}
                                                    @endif
                                                    {{-- Add other player actions if needed --}}
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
            </div>
        @endif
    </div>
</div>
@endsection
