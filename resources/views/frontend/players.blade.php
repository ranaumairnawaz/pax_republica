@extends('layouts.frontend')

@section('content')
<section>
    <div class="container">
        <h2 class="text-center my-5">{{ $pagetitle }}</h2>

        {{-- Display search query if present --}}
        @isset($searchQuery)
            <div class="alert alert-info" role="alert">
                Showing results for: <strong>"{{ $searchQuery }}"</strong> in category: <strong>{{ $slug }}</strong>
            </div>
        @endisset

        <div class="row g-4">
            @if($data->isEmpty())
                <div class="col-12 text-center">
                    @isset($searchQuery)
                        <p>No players found matching your search criteria.</p>
                    @else
                        <p>No players found in this category yet.</p>
                    @endisset
                </div>
            @else
                @foreach($data as $item)
                <div class="col-md-4">
                    <div class="category-card p-4 text-center">
                        <h3>{{ $item->name }}</h3>
                        <p class="mb-0">Account: {{ $item->account_name }}</p>
                        <p class="mb-0">Email: {{ $item->email }}</p>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</section>
@endsection
