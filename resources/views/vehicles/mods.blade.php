@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Modify {{ $vehicle->name }}</h2>
                    <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-secondary">Back to Vehicle</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Vehicle Status</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Hull Points:</th>
                                            <td>{{ $vehicle->current_hull_points }}/{{ $vehicle->getMaxHullPoints() }}</td>
                                        </tr>
                                        <tr>
                                            <th>Shield Points:</th>
                                            <td>{{ $vehicle->current_shield_points }}/{{ $vehicle->getMaxShieldPoints() }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                <span class="badge bg-{{ $vehicle->status === 'operational' ? 'success' : ($vehicle->status === 'damaged' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($vehicle->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <h5>Available Modifications</h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Effects</th>
                                            <th>Difficulty</th>
                                            <th>Cost</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($availableMods as $mod)
                                            <tr>
                                                <td>{{ $mod->name }}</td>
                                                <td>{{ ucfirst($mod->type) }}</td>
                                                <td>
                                                    @foreach($mod->effects as $effect => $value)
                                                        @if(is_numeric($value))
                                                            {{ ucfirst(str_replace('_', ' ', $effect)) }}: {{ $value > 0 ? '+' : '' }}{{ $value }}
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @for($i = 1; $i <= 4; $i++)
                                                        <i class="fas fa-star {{ $i <= $mod->installation_difficulty ? 'text-warning' : 'text-muted' }}"></i>
                                                    @endfor
                                                </td>
                                                <td>{{ $mod->cost }} credits</td>
                                                <td>
                                                    <form action="{{ route('vehicles.install-mod', $vehicle) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="mod_template_id" value="{{ $mod->id }}">
                                                        <button type="submit" class="btn btn-sm btn-primary">Install</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No available modifications.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Installed Modifications</h5>
                            <div class="card">
                                <div class="card-body">
                                    @if($vehicle->mods->isNotEmpty())
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Type</th>
                                                        <th>Effects</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($vehicle->mods as $mod)
                                                        <tr>
                                                            <td>{{ $mod->template->name }}</td>
                                                            <td>{{ ucfirst($mod->template->type) }}</td>
                                                            <td>
                                                                @foreach($mod->template->effects as $effect => $value)
                                                                    @if(is_numeric($value))
                                                                        {{ ucfirst(str_replace('_', ' ', $effect)) }}: {{ $value > 0 ? '+' : '' }}{{ $value }}
                                                                    @endif
                                                                @endforeach
                                                            </td>
                                                            <td>
                                                                <form action="{{ route('vehicles.remove-mod', [$vehicle, $mod]) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to remove this mod?')">
                                                                        Remove
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">No modifications installed.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 