<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attribute extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_primary',
        'default_value',
        'min_value',
        'max_value',
    ];

    /**
     * Get the characters that have this attribute.
     */
    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(Character::class, 'character_attributes')
                    ->withPivot('value')
                    ->withTimestamps();
    }
}
