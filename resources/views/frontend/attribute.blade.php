@extends('layouts.frontend')

@section('content')
<section>
    <div class="container">
        <h2 class="text-center my-5">{{ $pagetitle }}</h2>

        <div class="row">
            <div class="col-md-12">
                @if($attribute)
                    <h3>{{ $attribute->name }}</h3>
                    <p><strong>Description:</strong> {{ $attribute->description ?? 'N/A' }}</p>
                    <p><strong>Created At:</strong> {{ $attribute->created_at }}</p>
                    <p><strong>Updated At:</strong> {{ $attribute->updated_at }}</p>
                    {{-- Add relationships if needed, e.g., characters, npcs --}}
                @else
                    <p>Attribute not found.</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
