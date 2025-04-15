@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Setting</h1>
        <form action="{{ route('admin.settings.update', $setting) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="key">Key</label>
                <input type="text" class="form-control" id="key" name="key" value="{{ $setting->key }}">
            </div>
            <div class="form-group">
                <label for="value">Value</label>
                <textarea class="form-control" id="value" name="value">{{ $setting->value }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
