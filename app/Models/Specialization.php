<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Specialization extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'skill_id',
        'name',
        'code',
        'description',
        'xp_cost',
        'restricted',
    ];

    /**
     * Get the skill that owns the specialization.
     */
    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    /**
     * Get the characters that have this specialization.
     */
    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(Character::class, 'character_specializations')
                    ->withTimestamps();
    }
}
