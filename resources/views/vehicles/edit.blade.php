@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Edit Vehicle: {{ $vehicle->name }}</h2>
                </div>

                <div class="card-body">
                    <form action="{{ route('vehicles.update', $vehicle) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="character_id" class="form-label">Owner</label>
                                    <select class="form-select @error('character_id') is-invalid @enderror" id="character_id" name="character_id" required>
                                        <option value="">Select Character</option>
                                        @foreach($characters as $character)
                                            <option value="{{ $character->id }}" {{ old('character_id', $vehicle->character_id) == $character->id ? 'selected' : '' }}>
                                                {{ $character->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('character_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $vehicle->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="registration" class="form-label">Registration</label>
                                    <input type="text" class="form-control @error('registration') is-invalid @enderror" id="registration" name="registration" value="{{ old('registration', $vehicle->registration) }}">
                                    @error('registration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="current_location" class="form-label">Current Location</label>
                                    <input type="text" class="form-control @error('current_location') is-invalid @enderror" id="current_location" name="current_location" value="{{ old('current_location', $vehicle->current_location) }}">
                                    @error('current_location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Vehicle Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <tr>
                                                <th>Type:</th>
                                                <td>{{ $vehicle->template->getFormattedType() }}</td>
                                            </tr>
                                            <tr>
                                                <th>Size:</th>
                                                <td>{{ $vehicle->template->getFormattedSize() }}</td>
                                            </tr>
                                            <tr>
                                                <th>Manufacturer:</th>
                                                <td>{{ $vehicle->template->manufacturer ?? 'Unknown' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Model:</th>
                                                <td>{{ $vehicle->template->model ?? 'Unknown' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Crew:</th>
                                                <td>{{ $vehicle->template->getFormattedCrew() }}</td>
                                            </tr>
                                            <tr>
                                                <th>Passengers:</th>
                                                <td>{{ $vehicle->template->passengers }}</td>
                                            </tr>
                                            <tr>
                                                <th>Cargo Capacity:</th>
                                                <td>{{ $vehicle->template->cargo_capacity }}</td>
                                            </tr>
                                            <tr>
                                                <th>Consumables:</th>
                                                <td>{{ $vehicle->template->getFormattedConsumables() }}</td>
                                            </tr>
                                            <tr>
                                                <th>Speed:</th>
                                                <td>{{ $vehicle->template->speed ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Hyperspace Speed:</th>
                                                <td>{{ $vehicle->template->hyperspace_speed ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Hull Points:</th>
                                                <td>{{ $vehicle->current_hull_points }}/{{ $vehicle->getMaxHullPoints() }}</td>
                                            </tr>
                                            <tr>
                                                <th>Shield Points:</th>
                                                <td>{{ $vehicle->current_shield_points }}/{{ $vehicle->getMaxShieldPoints() }}</td>
                                            </tr>
                                            <tr>
                                                <th>Armor:</th>
                                                <td>{{ $vehicle->template->armor }}</td>
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
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $vehicle->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Vehicle</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 