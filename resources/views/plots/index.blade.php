@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Plots</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <a href="{{ route('plots.create') }}" class="btn btn-primary">Create New Plot</a>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Creator</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($plots as $plot)
                                    <tr>
                                        <td>{{ $plot->title }}</td>
                                        <td>{{ $plot->description }}</td>
                                        <td>{{ $plot->creator->name }}</td>
                                        <td>
                                            <a href="{{ route('plots.show', $plot->id) }}" class="btn btn-sm btn-info">View</a>
                                            <a href="{{ route('plots.edit', $plot->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('plots.destroy', $plot->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this plot?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
