@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Edit NPC: {{ $npc->name }}</h2>
                </div>

                <div class="card-body">
                    <form action="{{ route('npcs.update', $npc) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $npc->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="species_id" class="form-label">Species</label>
                                    <select class="form-select @error('species_id') is-invalid @enderror" id="species_id" name="species_id">
                                        <option value="">Select Species</option>
                                        @foreach($species as $s)
                                            <option value="{{ $s->id }}" {{ old('species_id', $npc->species_id) == $s->id ? 'selected' : '' }}>
                                                {{ $s->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('species_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="faction_id" class="form-label">Faction</label>
                                    <select class="form-select @error('faction_id') is-invalid @enderror" id="faction_id" name="faction_id">
                                        <option value="">Select Faction</option>
                                        @foreach($factions as $f)
                                            <option value="{{ $f->id }}" {{ old('faction_id', $npc->faction_id) == $f->id ? 'selected' : '' }}>
                                                {{ $f->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('faction_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="faction_rank_id" class="form-label">Faction Rank</label>
                                    <select class="form-select @error('faction_rank_id') is-invalid @enderror" id="faction_rank_id" name="faction_rank_id">
                                        <option value="">Select Rank</option>
                                        @foreach($factionRanks as $rank)
                                            <option value="{{ $rank->id }}" {{ old('faction_rank_id', $npc->faction_rank_id) == $rank->id ? 'selected' : '' }}>
                                                {{ $rank->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('faction_rank_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="occupation" class="form-label">Occupation</label>
                                    <input type="text" class="form-control @error('occupation') is-invalid @enderror" id="occupation" name="occupation" value="{{ old('occupation', $npc->occupation) }}">
                                    @error('occupation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="current_location" class="form-label">Current Location</label>
                                    <input type="text" class="form-control @error('current_location') is-invalid @enderror" id="current_location" name="current_location" value="{{ old('current_location', $npc->current_location) }}">
                                    @error('current_location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="importance" class="form-label">Importance</label>
                                    <select class="form-select @error('importance') is-invalid @enderror" id="importance" name="importance" required>
                                        <option value="minor" {{ old('importance', $npc->importance) == 'minor' ? 'selected' : '' }}>Minor</option>
                                        <option value="supporting" {{ old('importance', $npc->importance) == 'supporting' ? 'selected' : '' }}>Supporting</option>
                                        <option value="major" {{ old('importance', $npc->importance) == 'major' ? 'selected' : '' }}>Major</option>
                                    </select>
                                    @error('importance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="image_url" class="form-label">Image URL</label>
                                    <input type="url" class="form-control @error('image_url') is-invalid @enderror" id="image_url" name="image_url" value="{{ old('image_url', $npc->image_url) }}">
                                    @error('image_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input @error('is_public') is-invalid @enderror" id="is_public" name="is_public" value="1" {{ old('is_public', $npc->is_public) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_public">Make NPC Public</label>
                                        @error('is_public')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $npc->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="appearance" class="form-label">Appearance</label>
                            <textarea class="form-control @error('appearance') is-invalid @enderror" id="appearance" name="appearance" rows="3">{{ old('appearance', $npc->appearance) }}</textarea>
                            @error('appearance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="card mb-3">
                            <div class="card-header">
                                <h4 class="mb-0">Attributes</h4>
                            </div>
                            <div class="card-body">
                                @foreach($attributes as $attribute)
                                    <div class="mb-3">
                                        <label for="attributes[{{ $attribute->id }}]" class="form-label">{{ $attribute->name }}</label>
                                        <input type="number" class="form-control @error('attributes.' . $attribute->id) is-invalid @enderror" 
                                               id="attributes[{{ $attribute->id }}]" 
                                               name="attributes[{{ $attribute->id }}]" 
                                               value="{{ old('attributes.' . $attribute->id, $attributeValues[$attribute->id] ?? 1) }}"
                                               min="1" max="10">
                                        @error('attributes.' . $attribute->id)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header">
                                <h4 class="mb-0">Skills</h4>
                            </div>
                            <div class="card-body">
                                @foreach($skills as $skill)
                                    <div class="mb-3">
                                        <label for="skills[{{ $skill->id }}]" class="form-label">{{ $skill->name }} ({{ $skill->attribute->name }})</label>
                                        <input type="number" class="form-control @error('skills.' . $skill->id) is-invalid @enderror" 
                                               id="skills[{{ $skill->id }}]" 
                                               name="skills[{{ $skill->id }}]" 
                                               value="{{ old('skills.' . $skill->id, $skillValues[$skill->id] ?? 0) }}"
                                               min="0" max="10">
                                        @error('skills.' . $skill->id)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('npcs.show', $npc) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update NPC</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const factionSelect = document.getElementById('faction_id');
    const rankSelect = document.getElementById('faction_rank_id');

    factionSelect.addEventListener('change', function() {
        const factionId = this.value;
        rankSelect.innerHTML = '<option value="">Select Rank</option>';
        
        if (factionId) {
            fetch(`/npcs/faction-ranks?faction_id=${factionId}`)
                .then(response => response.json())
                .then(ranks => {
                    ranks.forEach(rank => {
                        const option = new Option(rank.name, rank.id);
                        rankSelect.add(option);
                    });
                });
        }
    });
});
</script>
@endpush
@endsection 