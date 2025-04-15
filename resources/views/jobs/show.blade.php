@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $job->title }}</h1>

        <div class="card">
            <div class="card-body">
                <p><strong>Category:</strong> {{ $job->category }}</p>
                <p><strong>Status:</strong> {{ $job->status }}</p>
                <p><strong>Description:</strong> {{ $job->content }}</p>

                @if($job->character)
                    <p><strong>Character:</strong> {{ $job->character->name }}</p>
                @endif

                <p><strong>Created by:</strong> {{ $job->creator->name }}</p>

                @if($job->handler)
                    <p><strong>Assigned to:</strong> {{ $job->handler->name }}</p>
                @endif
            </div>
        </div>

        <hr>

        <h2>Comments</h2>

        @foreach($job->comments as $comment)
            <div class="card">
                <div class="card-body">
                    <p>{{ $comment->content }}</p>
                    <p>
                        <strong>{{ $comment->user->name }}</strong> -
                        {{ $comment->created_at->diffForHumans() }}
                    </p>
                     @if(Auth::id() === $comment->user_id)
                        <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#editComment{{ $comment->id }}" aria-expanded="false" aria-controls="editComment{{ $comment->id }}">
                            Edit
                        </button>
                        <form action="{{ route('jobs.comments.destroy', [$job, $comment]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
                        </form>
                    @endif
                </div>
                 <div class="collapse" id="editComment{{ $comment->id }}">
                    <div class="card card-body">
                        <form action="{{ route('jobs.comments.update', [$job, $comment]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <textarea class="form-control" name="content" rows="3">{{ $comment->content }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Comment</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <hr>

        <h2>Add Comment</h2>
        <form action="{{ route('jobs.comments.store', $job) }}" method="POST">
            @csrf
            <div class="form-group">
                <textarea class="form-control" name="content" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Comment</button>
        </form>
    </div>
@endsection
