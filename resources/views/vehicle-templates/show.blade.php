@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">{{ $template->name }}</h2>
                    <div>
                        @can('update', $template)
                            <a href="{{ route('vehicle-templates.edit', $template) }}" class="btn btn-warning">Edit</a>
                        @endcan
                        @can('delete', $template)
                            <form action="{{ route('vehicle-templates.destroy', $template) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this template?')">
                                    Delete
                                </button>
                            </form>
                        @endcan
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
                            @if($template->image_url)
                                <img src="{{ $template->image_url }}" alt="{{ $template->name }}" class="img-fluid rounded mb-3">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Basic Information</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Type:</th>
                                            <td>{{ $template->getFormattedType() }}</td>
                                        </tr>
                                        <tr>
                                            <th>Size:</th>
                                            <td>{{ $template->getFormattedSize() }}</td>
                                        </tr>
                                        <tr>
                                            <th>Manufacturer:</th>
                                            <td>{{ $template->manufacturer }}</td>
                                        </tr>
                                        <tr>
                                            <th>Model:</th>
                                            <td>{{ $template->model }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                @if($template->is_restricted)
                                                    <span class="badge bg-danger">Restricted</span>
                                                @else
                                                    <span class="badge bg-success">Standard</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5>Technical Specifications</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Crew:</th>
                                            <td>{{ $template->getFormattedCrew() }}</td>
                                        </tr>
                                        <tr>
                                            <th>Passengers:</th>
                                            <td>{{ $template->passengers }}</td>
                                        </tr>
                                        <tr>
                                            <th>Cargo Capacity:</th>
                                            <td>{{ $template->cargo_capacity }} tons</td>
                                        </tr>
                                        <tr>
                                            <th>Consumables:</th>
                                            <td>{{ $template->getFormattedConsumables() }}</td>
                                        </tr>
                                        <tr>
                                            <th>Speed:</th>
                                            <td>{{ $template->speed ?? 'N/A' }} MGLT</td>
                                        </tr>
                                        <tr>
                                            <th>Hyperspace Speed:</th>
                                            <td>{{ $template->hyperspace_speed ?? 'N/A' }} Class</td>
                                        </tr>
                                        <tr>
                                            <th>Base Cost:</th>
                                            <td>{{ number_format($template->base_cost) }} credits</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Combat Statistics</h5>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h6>Hull Points</h6>
                                            <div class="progress mb-3">
                                                <div class="progress-bar" role="progressbar" 
                                                     style="width: {{ ($template->hull_points / 100) * 100 }}%"
                                                     aria-valuenow="{{ $template->hull_points }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ $template->hull_points }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <h6>Shield Points</h6>
                                            <div class="progress mb-3">
                                                <div class="progress-bar" role="progressbar" 
                                                     style="width: {{ ($template->shield_points / 100) * 100 }}%"
                                                     aria-valuenow="{{ $template->shield_points }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ $template->shield_points }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <h6>Armor</h6>
                                            <div class="progress mb-3">
                                                <div class="progress-bar" role="progressbar" 
                                                     style="width: {{ ($template->armor / 10) * 100 }}%"
                                                     aria-valuenow="{{ $template->armor }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="10">
                                                    {{ $template->armor }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Description</h5>
                            <div class="card">
                                <div class="card-body">
                                    {!! nl2br(e($template->description ?? 'No description available.')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Weapons</h5>
                            <div class="card">
                                <div class="card-body">
                                    {!! nl2br(e($template->weapons ?? 'No weapons information available.')) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($template->vehicles->isNotEmpty())
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5>Vehicles Using This Template</h5>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Owner</th>
                                                        <th>Status</th>
                                                        <th>Location</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($template->vehicles as $vehicle)
                                                        <tr>
                                                            <td>
                                                                <a href="{{ route('vehicles.show', $vehicle) }}">
                                                                    {{ $vehicle->name }}
                                                                </a>
                                                            </td>
                                                            <td>{{ $vehicle->character->name }}</td>
                                                            <td>
                                                                <span class="badge bg-{{ $vehicle->status === 'operational' ? 'success' : ($vehicle->status === 'damaged' ? 'warning' : 'danger') }}">
                                                                    {{ ucfirst($vehicle->status) }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $vehicle->current_location ?? 'Unknown' }}</td>
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