@extends('layouts.app')

@section('title', 'Admin: Edit Mod Template')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h1>Edit Mod Template: {{ $modTemplate->name }}</h1>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('admin.mod-templates.update', $modTemplate->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $modTemplate->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $modTemplate->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="">Select Type</option>
                        <option value="weapon" {{ old('type', $modTemplate->type) == 'weapon' ? 'selected' : '' }}>Weapon</option>
                        <option value="defense" {{ old('type', $modTemplate->type) == 'defense' ? 'selected' : '' }}>Defense</option>
                        <option value="propulsion" {{ old('type', $modTemplate->type) == 'propulsion' ? 'selected' : '' }}>Propulsion</option>
                        <option value="sensor" {{ old('type', $modTemplate->type) == 'sensor' ? 'selected' : '' }}>Sensor</option>
                        <option value="utility" {{ old('type', $modTemplate->type) == 'utility' ? 'selected' : '' }}>Utility</option>
                        <option value="special" {{ old('type', $modTemplate->type) == 'special' ? 'selected' : '' }}>Special</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="effects" class="form-label">Effects (JSON)</label>
                    <textarea class="form-control @error('effects') is-invalid @enderror" id="effects" name="effects" rows="5">{{ old('effects', $modTemplate->effects) }}</textarea>
                    <small class="text-muted">Enter a valid JSON object, e.g., {"damage": 10, "range": 5}</small>
                    @error('effects')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input @error('is_restricted') is-invalid @enderror" id="is_restricted" name="is_restricted" {{ old('is_restricted', $modTemplate->is_restricted) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_restricted">Restricted</label>
                    @error('is_restricted')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="cost" class="form-label">Cost</label>
                    <input type="number" class="form-control @error('cost') is-invalid @enderror" id="cost" name="cost" value="{{ old('cost', $modTemplate->cost) }}" required>
                    @error('cost')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="installation_difficulty" class="form-label">Installation Difficulty</label>
                    <select class="form-select @error('installation_difficulty') is-invalid @enderror" id="installation_difficulty" name="installation_difficulty" required>
                        <option value="">Select Difficulty</option>
                        <option value="1" {{ old('installation_difficulty', $modTemplate->installation_difficulty) == '1' ? 'selected' : '' }}>Easy</option>
                        <option value="2" {{ old('installation_difficulty', $modTemplate->installation_difficulty) == '2' ? 'selected' : '' }}>Medium</option>
                        <option value="3" {{ old('installation_difficulty', $modTemplate->installation_difficulty) == '3' ? 'selected' : '' }}>Hard</option>
                        <option value="4" {{ old('installation_difficulty', $modTemplate->installation_difficulty) == '4' ? 'selected' : '' }}>Very Hard</option>
                    </select>
                    @error('installation_difficulty')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <a href="{{ route('admin.mod-templates.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Mod Template</button>
            </form>
        </div>
    </div>
</div>
@endsection

</final_file_content>

IMPORTANT: For any future changes to this file, use the final_file_content shown above as your reference. This content reflects the current state of the file, including any auto-formatting (e.g., if you used single quotes but the formatter converted them to double quotes). Always base your SEARCH/REPLACE operations on this final version to ensure accuracy.

<environment_details>
# VSCode Visible Files
resources/views/admin/mod-templates/edit.blade.php

# VSCode Open Tabs
resources/views/factions/show.blade.php
resources/views/jobs/show.blade.php
resources/views/jobs/create.blade.php
resources/views/messages/index.blade.php
resources/views/messages/create.blade.php
resources/views/posts/edit.blade.php
resources/views/scenes/show.blade.php
app/Http/Controllers/Admin/SpecializationController.php
app/Models/Specialization.php
resources/views/admin/specializations/edit.blade.php
resources/views/admin/skills/index.blade.php
resources/views/admin/skills/create.blade.php
resources/views/admin/skills/edit.blade.php
resources/views/admin/attributes/index.blade.php
resources/views/admin/attributes/create.blade.php
resources/views/admin/attributes/edit.blade.php
resources/views/scenes/create.blade.php
resources/views/vehicles/show.blade.php
app/Http/Controllers/SceneController.php
resources/views/scenes/index.blade.php
resources/views/factions/index.blade.php
resources/views/jobs/index.blade.php
resources/views/vehicles/index.blade.php
app/Http/Controllers/VehicleController.php
app/Http/Middleware/AdminMiddleware.php
app/Http/Kernel.php
routes/web.php
database/migrations/2025_04_07_200331_create_trait_models_table.php
resources/views/admin/traits/index.blade.php
app/Http/Controllers/Admin/SkillController.php
app/Models/Skill.php
app/Http/Controllers/Admin/AttributeController.php
resources/views/admin/traits/create.blade.php
resources/views/admin/traits/edit.blade.php
app/Models/TraitModel.php
app/Http/Controllers/Admin/TraitController.php
resources/views/admin/species/index.blade.php
resources/views/admin/species/create.blade.php
app/Http/Controllers/Admin/SpeciesController.php
app/Models/Species.php
database/migrations/2025_04_07_211413_create_archetypes_table.php
resources/views/admin/archetypes/index.blade.php
resources/views/admin/archetypes/create.blade.php
resources/views/admin/archetypes/edit.blade.php
app/Http/Controllers/Admin/ArchetypeController.php
app/Models/Archetype.php
app/Http/Controllers/Admin/FactionRankController.php
resources/views/admin/species/edit.blade.php
app/Models/Attribute.php
resources/views/admin/content.index.blade.php
resources/views/dashboard.blade.php
resources/views/admin/factions/index.blade.php
resources/views/admin/factions/ranks/create.blade.php
resources/views/admin/factions/ranks/edit.blade.php
resources/views/layouts.app.blade.php
app/Http/Controllers/Admin/FactionController.php
app/Models/Faction.php
resources/views/admin/factions/edit.blade.php
resources/views/admin/factions/create.blade.php
resources/views/admin/factions/ranks/index.blade.php
app/Http/Controllers/Admin/LocationController.php
resources/views/admin/locations/index.blade.php
resources/views/admin/locations/create.blade.php
resources/views/admin/locations/edit.blade.php
app/Http/Controllers/Admin/VehicleTemplateController.php
resources/views/admin/vehicle-templates/index.blade.php
resources/views/admin/vehicle-templates/create.blade.php
resources/views/admin/vehicle-templates/edit.blade.php
app/Http/Controllers/Admin/ModTemplateController.php
resources/views/admin/mod-templates/index.blade.php
resources/views/admin/mod-templates/create.blade.php
resources/views/admin/mod-templates/edit.blade.php
resources/views/admin/specializations/index.blade.php
resources/views/admin/specializations/create.blade.php
app/Http/Controllers/Admin/CharacterController.php
app/Http/Controllers/Admin/JobController.php
resources/views/auth/verify-email.blade.php
app/Http/Controllers/JobController.php
resources/views/admin/jobs/index.blade.php

# Current Time
4/8/2025, 4:42:14 PM (Asia/Karachi, UTC+5:00)

# Current Mode
ACT MODE
</environment_details>
