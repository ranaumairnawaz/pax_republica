@extends('layouts.app')

@section('title', 'Admin Content Management')

@section('content')
<div class="container mt-4">
    <h1>Admin Overview</h1>
    <div class="row">

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
            <div class="card h-100">
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
        </div>

        {{-- Vehicle Related --}}
        <div class="col-md-4 mb-3">
            <div class="card h-100">
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
    </div>
</div>
@endsection