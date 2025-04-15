@extends('layouts.app')

@section('title', $character->name . ' - Pax Republica')

@section('content')
<div class="container mt-4">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <!-- Character Overview -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Character Overview</h5>
                    @if($character->isInProgress())
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('characters.edit', $character) }}" class="btn btn-light">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <h4 class="mb-3">{{ $character->name }}</h4>
                    <div class="mb-3">
                        <span class="badge bg-{{ $character->status === 'active' ? 'success' : ($character->status === 'pending' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($character->status) }}
                        </span>
                    </div>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Species</dt>
                        <dd class="col-sm-8">{{ $character->species ? $character->species->name : 'Not Set' }}</dd>
                        
                        <dt class="col-sm-4">XP</dt>
                        <dd class="col-sm-8">{{ number_format($character->xp) }}</dd>
                        
                        <dt class="col-sm-4">Level</dt>
                        <dd class="col-sm-8">{{ $character->level }}</dd>
                        
                        <dt class="col-sm-4">Created</dt>
                        <dd class="col-sm-8">{{ $character->created_at->format('M d, Y') }}</dd>
                    </dl>
                </div>
            </div>

            <!-- Biography -->
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Biography</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $character->biography ?: 'No biography provided.' }}</p>
                </div>
            </div>
        </div>

        <!-- Attributes and Skills -->
        <div class="col-md-8">
            <!-- Attributes -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Attributes</h5>
                    @if($character->isActive())
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#spendXPModal">
                            <i class="bi bi-arrow-up-circle"></i> Spend XP
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        @forelse($character->attributes as $attribute)
                            <div class="col">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title d-flex justify-content-between align-items-center">
                                            {{ $attribute->name }}
                                            <span class="badge bg-primary">{{ $attribute->pivot->value }}</span>
                                        </h6>
                                        <p class="card-text small text-muted mb-0">{{ $attribute->description }}</p>
                                        @if($character->isActive())
                                            <div class="mt-2 small text-muted">
                                                XP Spent: {{ number_format($attribute->pivot->xp_spent) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info mb-0">
                                    No attributes have been assigned to this character.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Skills -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Skills</h5>
                </div>
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        @forelse($character->skills as $skill)
                            <div class="col">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title d-flex justify-content-between align-items-center">
                                            {{ $skill->name }}
                                            <span class="badge bg-primary">{{ $skill->pivot->value }}</span>
                                        </h6>
                                        <p class="card-text small text-muted mb-0">{{ $skill->description }}</p>
                                        @if($character->isActive())
                                            <div class="mt-2 small text-muted">
                                                XP Spent: {{ number_format($skill->pivot->xp_spent) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info mb-0">
                                    No skills have been assigned to this character.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($character->isActive())
    <!-- Spend XP Modal -->
    <div class="modal fade" id="spendXPModal" tabindex="-1" aria-labelledby="spendXPModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="spendXPModalLabel">Spend Experience Points</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Available XP: {{ number_format($character->xp) }}</p>
                    <form id="spendXPForm" action="{{ route('characters.spend-xp', $character) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="attribute_id" class="form-label">Select Attribute</label>
                            <select class="form-select" id="attribute_id" name="attribute_id" required>
                                <option value="">Choose an attribute...</option>
                                @foreach($character->attributes as $attribute)
                                    <option value="{{ $attribute->id }}" data-current-value="{{ $attribute->pivot->value }}">
                                        {{ $attribute->name }} (Current: {{ $attribute->pivot->value }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="points" class="form-label">Points to Add</label>
                            <input type="number" class="form-control" id="points" name="points" value="1" min="1" max="1" required>
                            <div class="form-text">Cost: <span id="xpCost">0</span> XP</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="spendXPForm" class="btn btn-primary">Spend XP</button>
                </div>
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script>
$(document).ready(function() {
    // XP cost calculation
    $('#attribute_id, #points').on('change', function() {
        const $attribute = $('#attribute_id option:selected');
        const currentValue = parseInt($attribute.data('current-value')) || 0;
        const points = parseInt($('#points').val()) || 0;
        const costPerPoint = currentValue * 5;
        const totalCost = costPerPoint * points;
        
        $('#xpCost').text(totalCost);
    });
});
</script>
@endpush
@endsection