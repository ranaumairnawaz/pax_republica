@extends('layouts.frontend')

@section('content')
<section>
    <div class="container">
        <h2 class="text-center my-5">Archtypes</h2>
        <div class="row g-4">
            @if($data->isEmpty())
                <div class="col-12">
                    <p>No archtypes found.</p>
                </div>
            @endif
            @foreach($data as $item)
                <div class="col-md-4">
                    <a href="{{ route('category.item', [$slug, $item->id]) }}" class="text-decoration-none">
                        <div class="category-card p-4 text-center">
                            <h3>{{ $item->name }}</h3>
                            <p class="mb-0">Description: {{ $item->description }}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
