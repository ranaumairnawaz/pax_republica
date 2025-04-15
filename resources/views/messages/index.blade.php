@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Messages</h1>

        <a href="{{ route('chat.new') }}" class="btn btn-primary">New Message</a>

        <div class="conversations">
            </div>
    </div>
@endsection
