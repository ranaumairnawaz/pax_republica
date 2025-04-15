<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faction extends Model
{
    use HasFactory;

    /**
     * Get the ranks associated with this faction.
     */
    public function ranks(): HasMany
    {
        return $this->hasMany(FactionRank::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'color',
        'picture_url',
        'wiki_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_playable' => 'boolean'
    ];

    /**
     * Get the characters that belong to this faction.
     */
    public function characters(): HasMany
    {
        return $this->hasMany(Character::class);
    }

    /**
     * Alias for the characters relationship, often used semantically as 'members'.
     */
    public function members(): HasMany
    {
        return $this->characters();
    }

    /**
     * Get the NPCs that belong to this faction.
     */
    public function npcs(): HasMany
    {
        return $this->hasMany(Npc::class);
    }

    /**
     * Get formatted type.
     *
     * @return string
     */
    public function getFormattedType(): string
    {
        $typeMap = [
            'government' => 'Government',
            'military' => 'Military Organization',
            'religious' => 'Religious Group',
            'corporate' => 'Corporation',
            'criminal' => 'Criminal Organization',
            'social' => 'Social Group',
            'other' => 'Other'
        ];

        return $typeMap[$this->type] ?? ucfirst($this->type);
    }

    /**
     * Get all characters and NPCs that belong to this faction.
     * 
     * @return array
     */
    public function getAllMembers(): array
    {
        $characters = $this->characters()->with('user')->get();
        $npcs = $this->npcs()->get();
        
        return [
            'characters' => $characters,
            'npcs' => $npcs
        ];
    }
}
