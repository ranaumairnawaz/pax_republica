<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'voter_character_id',
        'voted_character_id',
        'scene_id',
    ];

    /**
     * Get the character that cast the vote.
     */
    public function voter(): BelongsTo
    {
        return $this->belongsTo(Character::class, 'voter_character_id');
    }

    /**
     * Get the character that received the vote.
     */
    public function voted(): BelongsTo
    {
        return $this->belongsTo(Character::class, 'voted_character_id');
    }

    /**
     * Get the scene the vote was cast in.
     */
    public function scene(): BelongsTo
    {
        return $this->belongsTo(Scene::class);
    }
}
