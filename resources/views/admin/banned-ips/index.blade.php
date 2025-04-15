@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Banned IPs</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <a href="{{ route('admin.banned-ips.create') }}" class="btn btn-primary">Add New Banned IP</a>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>IP Address</th>
                                    <th>Reason</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bannedIps as $bannedIp)
                                    <tr>
                                        <td>{{ $bannedIp->ip_address }}</td>
                                        <td>{{ $bannedIp->reason }}</td>
                                        <td>
                                            <a href="{{ route('admin.banned-ips.edit', $bannedIp->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('admin.banned-ips.destroy', $bannedIp->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this banned IP?')">Delete</button>
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
