@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Vehicle Templates</h2>
                    @can('create', App\Models\VehicleTemplate::class)
                        <a href="{{ route('vehicle-templates.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create Template
                        </a>
                    @endcan
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
                                    <th>Type</th>
                                    <th>Size</th>
                                    <th>Manufacturer</th>
                                    <th>Model</th>
                                    <th>Cost</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($templates as $template)
                                    <tr>
                                        <td>{{ $template->name }}</td>
                                        <td>{{ $template->getFormattedType() }}</td>
                                        <td>{{ $template->getFormattedSize() }}</td>
                                        <td>{{ $template->manufacturer }}</td>
                                        <td>{{ $template->model }}</td>
                                        <td>{{ number_format($template->base_cost) }} credits</td>
                                        <td>
                                            @if($template->is_restricted)
                                                <span class="badge bg-danger">Restricted</span>
                                            @else
                                                <span class="badge bg-success">Standard</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('vehicle-templates.show', $template) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @can('update', $template)
                                                    <a href="{{ route('vehicle-templates.edit', $template) }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('delete', $template)
                                                    <form action="{{ route('vehicle-templates.destroy', $template) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this template?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No vehicle templates found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $templates->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 