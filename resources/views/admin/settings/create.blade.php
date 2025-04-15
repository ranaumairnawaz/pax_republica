@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Setting</h1>
        <form action="{{ route('admin.settings.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="key">Key</label>
                <input type="text" class="form-control" id="key" name="key">
            </div>
            <div class="form-group">
                <label for="value">Value</label>
                <textarea class="form-control" id="value" name="value"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
