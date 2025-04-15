<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModTemplate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'type',
        'effects',
        'is_restricted',
        'cost',
        'installation_difficulty'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'effects' => 'json',
        'is_restricted' => 'boolean',
        'cost' => 'integer',
        'installation_difficulty' => 'integer'
    ];

    /**
     * Get the mods that use this template.
     */
    public function mods(): HasMany
    {
        return $this->hasMany(VehicleMod::class);
    }

    /**
     * Get formatted type.
     *
     * @return string
     */
    public function getFormattedType(): string
    {
        $typeMap = [
            'weapon' => 'Weapon System',
            'defense' => 'Defense System',
            'propulsion' => 'Propulsion',
            'sensor' => 'Sensor',
            'utility' => 'Utility',
            'special' => 'Special'
        ];

        return $typeMap[$this->type] ?? ucfirst($this->type);
    }

    /**
     * Get formatted difficulty.
     *
     * @return string
     */
    public function getFormattedDifficulty(): string
    {
        $difficultyMap = [
            1 => 'Easy',
            2 => 'Medium',
            3 => 'Hard',
            4 => 'Very Hard'
        ];

        return $difficultyMap[$this->installation_difficulty] ?? 'Unknown';
    }

    /**
     * Get summary of effects.
     *
     * @return string
     */
    public function getEffectsSummary(): string
    {
        $summary = [];
        
        if (is_array($this->effects)) {
            foreach ($this->effects as $key => $value) {
                if ($value != 0) {
                    $sign = $value > 0 ? '+' : '';
                    $formattedKey = str_replace('_', ' ', ucfirst($key));
                    $summary[] = "{$formattedKey}: {$sign}{$value}";
                }
            }
        }
        
        return implode(', ', $summary);
    }
}
