<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FactionRank extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'faction_id',
        'name',
        'description',
        'level',
        'is_default'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'level' => 'integer',
        'is_default' => 'boolean'
    ];

    /**
     * Get the faction that this rank belongs to.
     */
    public function faction(): BelongsTo
    {
        return $this->belongsTo(Faction::class);
    }

    /**
     * Get the characters that have this rank.
     */
    public function characters(): HasMany
    {
        return $this->hasMany(Character::class);
    }

    /**
     * Get the NPCs that have this rank.
     */
    public function npcs(): HasMany
    {
        return $this->hasMany(Npc::class);
    }

    /**
     * Get the count of members (characters + NPCs) with this rank.
     * 
     * @return int
     */
    public function getMembersCount(): int
    {
        return $this->characters()->count() + $this->npcs()->count();
    }

    /**
     * Make this rank the default for the faction.
     * 
     * @return void
     */
    public function makeDefault(): void
    {
        // First, set all ranks in this faction to not default
        FactionRank::where('faction_id', $this->faction_id)
            ->update(['is_default' => false]);
        
        // Then set this rank as default
        $this->is_default = true;
        $this->save();
    }
}