@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">{{ $npc->name }}</h2>
                    <div>
                        @if($npc->user_id === Auth::id() || Auth::user()->isAdmin())
                            <a href="{{ route('npcs.edit', $npc) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('npcs.destroy', $npc) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this NPC?')">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4">
                            @if($npc->image_url)
                                <img src="{{ $npc->image_url }}" alt="{{ $npc->name }}" class="img-fluid rounded mb-3">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Basic Information</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Species:</th>
                                            <td>{{ $npc->species ? $npc->species->name : 'Unknown' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Faction:</th>
                                            <td>
                                                @if($npc->faction)
                                                    {{ $npc->faction->name }}
                                                    @if($npc->factionRank)
                                                        ({{ $npc->factionRank->name }})
                                                    @endif
                                                @else
                                                    None
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Occupation:</th>
                                            <td>{{ $npc->occupation ?? 'Unknown' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Current Location:</th>
                                            <td>{{ $npc->current_location ?? 'Unknown' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Importance:</th>
                                            <td>
                                                <span class="badge bg-{{ $npc->importance === 'major' ? 'danger' : ($npc->importance === 'supporting' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($npc->importance) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5>Status</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Created By:</th>
                                            <td>{{ $npc->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Visibility:</th>
                                            <td>
                                                <span class="badge bg-{{ $npc->is_public ? 'success' : 'secondary' }}">
                                                    {{ $npc->is_public ? 'Public' : 'Private' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Created:</th>
                                            <td>{{ $npc->created_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Last Updated:</th>
                                            <td>{{ $npc->updated_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Description</h5>
                            <div class="card">
                                <div class="card-body">
                                    {!! nl2br(e($npc->description ?? 'No description available.')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Appearance</h5>
                            <div class="card">
                                <div class="card-body">
                                    {!! nl2br(e($npc->appearance ?? 'No appearance description available.')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Attributes</h5>
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-sm">
                                        @foreach($npc->attributes as $attribute)
                                            <tr>
                                                <th>{{ $attribute->name }}:</th>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" 
                                                             style="width: {{ ($attribute->pivot->value / 10) * 100 }}%"
                                                             aria-valuenow="{{ $attribute->pivot->value }}" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="10">
                                                            {{ $attribute->pivot->value }}
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5>Skills</h5>
                            <div class="card">
                                <div class="card-body">
                                    @foreach($skillsByAttribute as $attributeName => $skills)
                                        <h6 class="mt-3">{{ $attributeName }}</h6>
                                        <table class="table table-sm">
                                            @foreach($skills as $skill)
                                                <tr>
                                                    <th>{{ $skill->name }}:</th>
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar" 
                                                                 style="width: {{ ($skill->pivot->value / 10) * 100 }}%"
                                                                 aria-valuenow="{{ $skill->pivot->value }}" 
                                                                 aria-valuemin="0" 
                                                                 aria-valuemax="10">
                                                                {{ $skill->pivot->value }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($npc->scenes->isNotEmpty())
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5>Appearances in Scenes</h5>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Scene</th>
                                                        <th>Status</th>
                                                        <th>Last Activity</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($npc->scenes as $scene)
                                                        <tr>
                                                            <td>
                                                                <a href="{{ route('scenes.show', $scene) }}">
                                                                    {{ $scene->title }}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-{{ $scene->status === 'completed' ? 'success' : ($scene->status === 'active' ? 'primary' : 'secondary') }}">
                                                                    {{ ucfirst($scene->status) }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $scene->last_activity_at ? $scene->last_activity_at->format('Y-m-d H:i:s') : 'Never' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 