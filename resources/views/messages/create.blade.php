@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>New Message</h1>
        <form action="{{ route('chat.send') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="recipient">Recipient</label>
                <input type="text" class="form-control" id="recipient" name="recipient">
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea class="form-control" id="message" name="message"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>
@endsection
