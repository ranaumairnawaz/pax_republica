@extends('layouts.app')

@section('title', 'Create Character - Pax Republica')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Create New Character</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('characters.store') }}" id="createCharacterForm">
                @csrf
                
                <!-- Section Headers -->
                <div class="mb-4">
                    <h4 class="mb-3">Create Your Character</h4>
                    <p class="text-muted">Fill out all sections below to create your character.</p>
                </div>

                <!-- Basic Information Section -->
                <div class="mb-4">
                    <h5 class="border-bottom pb-2 mb-3">Basic Information</h5>
                    <div class="mb-3">
                        <label for="name" class="form-label">Character Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="invalid-feedback" id="name-error">Please enter a character name.</div>
                    </div>
                    <div class="mb-3">
                        <label for="biography" class="form-label">Biography</label>
                        <textarea class="form-control @error('biography') is-invalid @enderror" id="biography" name="biography" rows="4">{{ old('biography') }}</textarea>
                        @error('biography')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Species Selection Section -->
                <div class="mb-4">
                    <h5 class="border-bottom pb-2 mb-3">Species Selection</h5>
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        @foreach($species as $specie)
                            <div class="col">
                                <div class="card h-100 species-card" data-species-id="{{ $specie->id }}">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="species_id" id="species_{{ $specie->id }}" value="{{ $specie->id }}" {{ old('species_id') == $specie->id ? 'checked' : '' }}>
                                            <label class="form-check-label" for="species_{{ $specie->id }}">
                                                <h5 class="card-title mb-2">{{ $specie->name }}</h5>
                                            </label>
                                        </div>
                                        <p class="card-text">{{ $specie->description }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3 text-danger d-none" id="species-error">Please select a species before proceeding.</div>
                </div>

                <!-- Attributes Section -->
                <div class="mb-4">
                    <h5 class="border-bottom pb-2 mb-3">Attributes</h5>
                    <div class="row">
                        @foreach($attributes as $attribute)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $attribute->name }}</h6>
                                        <p class="card-text small">{{ $attribute->description }}</p>
                                        <div class="input-group">
                                            <button type="button" class="btn btn-outline-secondary btn-sm attribute-decrement" data-id="{{ $attribute->id }}">-</button>
                                            <input type="number" class="form-control form-control-sm text-center attribute-input" id="attribute_{{ $attribute->id }}" name="attributes[{{ $attribute->id }}]" value="{{ old('attributes.'.$attribute->id, 1) }}" min="1" max="5" readonly>
                                            <button type="button" class="btn btn-outline-secondary btn-sm attribute-increment" data-id="{{ $attribute->id }}">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>Points Remaining: <span id="attributePointsRemaining">10</span>
                            </div>
                            <div class="text-danger d-none" id="attributes-error">Please allocate all attribute points before proceeding.</div>
                        </div>
                    </div>
                </div>

                <!-- Skills Section -->
                <div class="mb-4">
                    <h5 class="border-bottom pb-2 mb-3">Skills</h5>
                    <div class="row">
                        @foreach($skills as $skill)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $skill->name }}</h6>
                                        <p class="card-text small">{{ $skill->description }}</p>
                                        <div class="input-group">
                                            <button type="button" class="btn btn-outline-secondary btn-sm skill-decrement" data-id="{{ $skill->id }}">-</button>
                                            <input type="number" class="form-control form-control-sm text-center skill-input" id="skill_{{ $skill->id }}" name="skills[{{ $skill->id }}]" value="{{ old('skills.'.$skill->id, 0) }}" min="0" max="3" readonly>
                                            <button type="button" class="btn btn-outline-secondary btn-sm skill-increment" data-id="{{ $skill->id }}">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>Skill Points Remaining: <span id="skillPointsRemaining">10</span>
                            </div>
                            <div class="text-danger d-none" id="skills-error">Please allocate all skill points before proceeding.</div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-success" id="submitForm">Create Character</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    console.log('Document ready - initializing character form');
    
    // Form variables
    const $form = $('#createCharacterForm');
    
    // Points management
    let attributePoints = 10;
    let skillPoints = 10;
    
    // Initialize the form
    calculateAttributePoints();
    calculateSkillPoints();
    
    // Species card click handler (easier selection)
    $(document).on('click', '.species-card', function() {
        const speciesId = $(this).data('species-id');
        console.log('Species card clicked:', speciesId);
        $(`#species_${speciesId}`).prop('checked', true);
        $('#species-error').addClass('d-none');
    });
    
    // Attribute increment/decrement handlers
    $(document).on('click', '.attribute-increment', function() {
        const id = $(this).data('id');
        const $input = $(`#attribute_${id}`);
        const currentValue = parseInt($input.val());
        
        console.log('Attribute increment clicked:', id, 'Current value:', currentValue);
        
        if (attributePoints > 0 && currentValue < 5) {
            $input.val(currentValue + 1);
            calculateAttributePoints();
        }
    });
    
    $(document).on('click', '.attribute-decrement', function() {
        const id = $(this).data('id');
        const $input = $(`#attribute_${id}`);
        const currentValue = parseInt($input.val());
        
        console.log('Attribute decrement clicked:', id, 'Current value:', currentValue);
        
        if (currentValue > 1) {
            $input.val(currentValue - 1);
            calculateAttributePoints();
        }
    });
    
    // Skill increment/decrement handlers
    $(document).on('click', '.skill-increment', function() {
        const id = $(this).data('id');
        const $input = $(`#skill_${id}`);
        const currentValue = parseInt($input.val());
        
        console.log('Skill increment clicked:', id, 'Current value:', currentValue);
        
        if (skillPoints > 0 && currentValue < 3) {
            $input.val(currentValue + 1);
            calculateSkillPoints();
        }
    });
    
    $(document).on('click', '.skill-decrement', function() {
        const id = $(this).data('id');
        const $input = $(`#skill_${id}`);
        const currentValue = parseInt($input.val());
        
        console.log('Skill decrement clicked:', id, 'Current value:', currentValue);
        
        if (currentValue > 0) {
            $input.val(currentValue - 1);
            calculateSkillPoints();
        }
    });
    
    // Input validation handlers
    $('#name').on('input', function() {
        if ($(this).val().trim() !== '') {
            $(this).removeClass('is-invalid');
            $('#name-error').hide();
        }
    });
    
    // Form submission validation
    $form.on('submit', function(e) {
        let isValid = true;
        
        // Validate character name
        const nameValue = $('#name').val().trim();
        if (nameValue === '') {
            $('#name').addClass('is-invalid');
            $('#name-error').show();
            isValid = false;
        }
        
        // Validate species selection
        const speciesSelected = $('input[name="species_id"]:checked').length > 0;
        if (!speciesSelected) {
            $('#species-error').removeClass('d-none');
            isValid = false;
        }
        
        // Ensure all attribute values are set properly
        $('.attribute-input').each(function() {
            const value = parseInt($(this).val());
            if (isNaN(value) || value < 1) {
                $(this).val(1); // Ensure minimum value of 1
            }
            console.log('Attribute value set for submission:', $(this).attr('name'), $(this).val());
        });
        
        // Ensure all skill values are set properly
        $('.skill-input').each(function() {
            const value = parseInt($(this).val());
            if (isNaN(value)) {
                $(this).val(0); // Ensure minimum value of 0
            }
            console.log('Skill value set for submission:', $(this).attr('name'), $(this).val());
        });
        
        // Validate attributes allocation - recalculate to ensure accuracy
        calculateAttributePoints();
        if (attributePoints !== 0) {
            $('#attributes-error').removeClass('d-none');
            isValid = false;
        }
        
        // Validate skills allocation - recalculate to ensure accuracy
        calculateSkillPoints();
        if (skillPoints !== 0) {
            $('#skills-error').removeClass('d-none');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            const $firstInvalid = $('.is-invalid').first();
            if ($firstInvalid.length > 0) {
                $('html, body').animate({
                    scrollTop: $firstInvalid.offset().top - 100
                }, 200);
            }
        }
    });
    
    function calculateAttributePoints() {
        let used = 0;
        let basePoints = 0;
        
        $('.attribute-input').each(function() {
            const value = parseInt($(this).val());
            used += value;
            // Each attribute starts at 1, so count only points above that
            basePoints += 1;
        });
        
        // Total points = 10 + number of attributes (since each starts at 1)
        // Used points = sum of all attribute values
        // Remaining = Total - Used
        attributePoints = (10 + basePoints) - used;
        $('#attributePointsRemaining').text(attributePoints);
        console.log('Attribute points remaining:', attributePoints);
        
        // Hide error if points are fully allocated
        if (attributePoints === 0) {
            $('#attributes-error').addClass('d-none');
        } else {
            $('#attributes-error').removeClass('d-none');
        }
    }
    
    function calculateSkillPoints() {
        let used = 0;
        $('.skill-input').each(function() {
            used += parseInt($(this).val());
        });
        
        skillPoints = 10 - used;
        $('#skillPointsRemaining').text(skillPoints);
        console.log('Skill points remaining:', skillPoints);
        
        // Hide error if points are fully allocated
        if (skillPoints === 0) {
            $('#skills-error').addClass('d-none');
        } else {
            $('#skills-error').removeClass('d-none');
        }
    }
});
</script>
@endpush

<style>
.step-indicator {
    font-size: 0.9rem;
    color: #6c757d;
}

.step-indicator.active {
    color: #6a0dad;
    font-weight: 500;
}

.card {
    transition: all 0.3s ease;
}

.form-check-input:checked + .form-check-label .card-title {
    color: #6a0dad;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
</style>
@endsection