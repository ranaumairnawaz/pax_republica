<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import BelongsTo
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skill extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code', // Added code
        'description',
        'attribute_id', // Added attribute_id
    ];

    /**
     * Get the characters that have this skill.
     */
    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(Character::class, 'character_skills')
                    ->withPivot('value')
                    ->withTimestamps();
    }

    /**
     * Get the specializations for this skill.
     */
    public function specializations(): HasMany
    {
        return $this->hasMany(Specialization::class);
    }

    /**
     * Get the attribute that this skill belongs to.
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }
}
