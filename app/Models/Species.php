<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Species extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'modifiers',
        'wiki_url',
        'image_url',
        'playable'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'modifiers' => 'json',
        'playable' => 'boolean'
    ];

    /**
     * Get the characters that belong to this species.
     */
    public function characters(): HasMany
    {
        return $this->hasMany(Character::class);
    }

    /**
     * Get the NPCs that belong to this species.
     */
    public function npcs(): HasMany
    {
        return $this->hasMany(Npc::class);
    }

    /**
     * Get the attribute modifier for a specific attribute.
     * 
     * @param string $attributeKey
     * @return int
     */
    public function getAttributeModifier(string $attributeKey): int
    {
        if (is_array($this->modifiers) && isset($this->modifiers['attributes'][$attributeKey])) {
            return $this->modifiers['attributes'][$attributeKey];
        }
        
        return 0;
    }

    /**
     * Get the skill modifier for a specific skill.
     * 
     * @param string $skillKey
     * @return int
     */
    public function getSkillModifier(string $skillKey): int
    {
        if (is_array($this->modifiers) && isset($this->modifiers['skills'][$skillKey])) {
            return $this->modifiers['skills'][$skillKey];
        }
        
        return 0;
    }
}
