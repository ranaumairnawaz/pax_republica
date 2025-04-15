@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">NPCs</h2>
                    <a href="{{ route('npcs.create') }}" class="btn btn-primary">Create New NPC</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Species</th>
                                    <th>Faction</th>
                                    <th>Occupation</th>
                                    <th>Importance</th>
                                    <th>Location</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($npcs as $npc)
                                    <tr>
                                        <td>
                                            <a href="{{ route('npcs.show', $npc) }}">
                                                {{ $npc->name }}
                                            </a>
                                        </td>
                                        <td>{{ $npc->species ? $npc->species->name : 'Unknown' }}</td>
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
                                        <td>{{ $npc->occupation ?? 'Unknown' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $npc->importance === 'major' ? 'danger' : ($npc->importance === 'supporting' ? 'warning' : 'info') }}">
                                                {{ ucfirst($npc->importance) }}
                                            </span>
                                        </td>
                                        <td>{{ $npc->current_location ?? 'Unknown' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('npcs.show', $npc) }}" class="btn btn-sm btn-info">
                                                    View
                                                </a>
                                                @if($npc->user_id === Auth::id() || Auth::user()->isAdmin())
                                                    <a href="{{ route('npcs.edit', $npc) }}" class="btn btn-sm btn-warning">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('npcs.destroy', $npc) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this NPC?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No NPCs found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $npcs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 