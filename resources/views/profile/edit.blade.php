@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Profile</h1>

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', auth()->user()->name) }}">
        </div>

        <div class="mb-3">
            <label for="account_name" class="form-label">Account Name</label>
            <input type="text" class="form-control" id="account_name" name="account_name" value="{{ old('account_name', auth()->user()->account_name) }}" maxlength="8">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', auth()->user()->email) }}">
        </div>

        <div class="mb-3">
            <label for="timezone" class="form-label">Timezone</label>
            <input type="text" class="form-control" id="timezone" name="timezone" value="{{ old('timezone', auth()->user()->timezone) }}">
        </div>

        <div class="mb-3">
            <label for="real_name" class="form-label">Real Name</label>
            <input type="text" class="form-control" id="real_name" name="real_name" value="{{ old('real_name', auth()->user()->real_name) }}">
        </div>

        <div class="mb-3">
            <label for="age" class="form-label">Age</label>
            <input type="number" class="form-control" id="age" name="age" value="{{ old('age', auth()->user()->age) }}">
        </div>

        <div class="mb-3">
            <label for="sex" class="form-label">Sex</label>
            <select class="form-select" id="sex" name="sex">
                <option value="" @selected(old('sex', auth()->user()->sex) == '')>Select...</option>
                <option value="M" @selected(old('sex', auth()->user()->sex) == 'M')>Male</option>
                <option value="F" @selected(old('sex', auth()->user()->sex) == 'F')>Female</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="profile" class="form-label">Profile</label>
            <textarea class="form-control" id="profile" name="profile" rows="5">{{ old('profile', auth()->user()->profile) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
