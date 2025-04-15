@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit Banned IP</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.banned-ips.update', $bannedIp->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="ip_address">IP Address</label>
                                <input type="text" class="form-control @error('ip_address') is-invalid @enderror" id="ip_address" name="ip_address" value="{{ $bannedIp->ip_address }}" required>
                                @error('ip_address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="reason">Reason</label>
                                <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason">{{ $bannedIp->reason }}</textarea>
                                @error('reason')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Update Banned IP</button>
                            <a href="{{ route('admin.banned-ips.index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
