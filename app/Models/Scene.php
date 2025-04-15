<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scene extends Model
{
  use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'synopsis',
        'plot_id',
        'location_id',
        'status',
        'is_private',
        'started_at',
        'ended_at',
        'creator_id',
        'creator_character_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_private' => 'boolean',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * Scene status constants
     */
    const STATUS_PLANNING = 'planning';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';

    /**
     * Get the location of the scene.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the plot of the scene.
     */
    public function plot(): BelongsTo
    {
        return $this->belongsTo(Plot::class);
    }

    /**
     * Get the characters participating in the scene.
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Character::class, 'scene_participants')
                    ->withPivot('joined_at')
                    ->withTimestamps();
    }

    /**
     * Get the NPCs appearing in the scene.
     */
    public function npcs(): BelongsToMany
    {
        return $this->belongsToMany(Npc::class, 'scene_npcs')
                    ->withPivot('notes')
                    ->withTimestamps();
    }

    /**
     * Get the posts in the scene.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Check if the scene is in planning stage.
     *
     * @return bool
     */
    public function isPlanning(): bool
    {
        return $this->status === self::STATUS_PLANNING;
    }

    /**
     * Check if the scene is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if the scene is completed.
     *
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Start the scene.
     *
     * @return bool
     */
    public function start(): bool
    {
        if ($this->status !== self::STATUS_PLANNING) {
            return false;
        }

        $this->status = self::STATUS_ACTIVE;
        $this->started_at = now();
        return $this->save();
    }

    /**
     * Complete the scene.
     *
     * @return bool
     */
    public function complete(): bool
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        $this->status = self::STATUS_COMPLETED;
        $this->ended_at = now();
        return $this->save();
    }

    /**
     * Add an NPC to the scene.
     * 
     * @param int $npcId
     * @param string|null $notes
     * @return void
     */
    public function addNpc(int $npcId, ?string $notes = null): void
    {
        $this->npcs()->syncWithoutDetaching([
            $npcId => [
                'notes' => $notes
            ]
        ]);
    }

    /**
     * Remove an NPC from the scene.
     * 
     * @param int $npcId
     * @return void
     */
    public function removeNpc(int $npcId): void
    {
        $this->npcs()->detach($npcId);
    }

    /**
     * Scope a query to only include planning scenes.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePlanning($query)
    {
        return $query->where('status', self::STATUS_PLANNING);
    }

    /**
     * Scope a query to only include active scenes.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope a query to only include completed scenes.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }
}
