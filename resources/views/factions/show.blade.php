@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $faction->name }}</h1>
        <p>{{ $faction->description }}</p>
    </div>
@endsection
