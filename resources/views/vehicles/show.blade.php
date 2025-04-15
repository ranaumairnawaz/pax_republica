@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $vehicle->name }}</h1>
        <p>{{ $vehicle->description }}</p>
        <p><strong>Model:</strong> {{ $vehicle->model }}</p>
    </div>
@endsection
