<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Npc extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'species_id',
        'faction_id',
        'faction_rank_id',
        'name',
        'occupation',
        'description',
        'appearance',
        'current_location',
        'importance',
        'is_public',
        'image_url'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_public' => 'boolean'
    ];

    /**
     * Get the user that created the NPC.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the species of the NPC.
     */
    public function species(): BelongsTo
    {
        return $this->belongsTo(Species::class);
    }

    /**
     * Get the faction of the NPC.
     */
    public function faction(): BelongsTo
    {
        return $this->belongsTo(Faction::class);
    }

    /**
     * Get the faction rank of the NPC.
     */
    public function factionRank(): BelongsTo
    {
        return $this->belongsTo(FactionRank::class);
    }

    /**
     * Get the attributes of the NPC.
     */
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'npc_attributes')
            ->withPivot('value')
            ->withTimestamps();
    }

    /**
     * Get the skills of the NPC.
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'npc_skills')
            ->withPivot('value')
            ->withTimestamps();
    }

    /**
     * Set an attribute value for the NPC.
     *
     * @param int $attributeId
     * @param int $value
     * @return void
     */
    public function setAttribute(int $attributeId, int $value): void
    {
        $this->attributes()->syncWithoutDetaching([
            $attributeId => ['value' => $value]
        ]);
    }

    /**
     * Set a skill value for the NPC.
     *
     * @param int $skillId
     * @param int $value
     * @return void
     */
    public function setSkill(int $skillId, int $value): void
    {
        $this->skills()->syncWithoutDetaching([
            $skillId => ['value' => $value]
        ]);
    }

    /**
     * Get an attribute value.
     *
     * @param int $attributeId
     * @return int
     */
    public function getAttributeValue(int $attributeId): int
    {
        $attribute = $this->attributes()->where('attribute_id', $attributeId)->first();
        return $attribute ? $attribute->pivot->value : 0;
    }

    /**
     * Get a skill value.
     *
     * @param int $skillId
     * @return int
     */
    public function getSkillValue(int $skillId): int
    {
        $skill = $this->skills()->where('skill_id', $skillId)->first();
        return $skill ? $skill->pivot->value : 0;
    }

    /**
     * Get the scenes this NPC appears in.
     */
    public function scenes(): BelongsToMany
    {
        return $this->belongsToMany(Scene::class, 'scene_npcs')
            ->withTimestamps();
    }

    /**
     * Make this NPC public or private.
     *
     * @param bool $isPublic
     * @return void
     */
    public function setPublic(bool $isPublic): void
    {
        $this->is_public = $isPublic;
        $this->save();
    }
}
