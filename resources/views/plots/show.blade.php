@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Plot Details</div>

                    <div class="card-body">
                        <h5 class="card-title">{{ $plot->title }}</h5>
                        <p class="card-text">{{ $plot->description }}</p>
                        <p class="card-text">Creator: {{ $plot->creator->name }}</p>

                        <a href="{{ route('plots.edit', $plot->id) }}" class="btn btn-primary">Edit</a>
                        <a href="{{ route('plots.index') }}" class="btn btn-secondary">Back to Plots</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
