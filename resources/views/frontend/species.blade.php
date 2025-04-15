@extends('layouts.frontend')

@section('content')
<section>
    <div class="container">
        <h2 class="text-center my-5">{{ $pagetitle }}</h2>

        @isset($searchQuery)
            <div class="alert alert-info" role="alert">
                Showing results for: <strong>"{{ $searchQuery }}"</strong> in category: <strong>{{ $slug }}</strong>
            </div>
        @endisset

        <div class="row g-4">
            @if($data->isEmpty())
                <div class="col-12 text-center">
                    @isset($searchQuery)
                        <p>No species found matching your search criteria.</p>
                    @else
                        <p>No species found in this category yet.</p>
                    @endisset
                </div>
            @else
            @foreach($data as $item)
                <div class="col-md-4">
                    <a href="{{ route('category.item', [$slug, $item->id]) }}" class="text-decoration-none">
                        <div class="category-card p-4 text-center">
                            <h3>{{ $item->name }}</h3>
                            <p class="mb-0">Description: {{ $item->description }}</p>
                            <h3 class="mt-2" style="font-size: 18px;" >Modifiers</h3>
                            <p class="mb-0">{!! formatJsonToText($item->modifiers) !!}</p>
                        </div>
                    </a>
                </div>
            @endforeach
            @endif
        </div>
    </div>
</section>
@endsection
