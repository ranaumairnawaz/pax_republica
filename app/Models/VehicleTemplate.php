<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleTemplate extends Model
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
        'manufacturer',
        'model',
        'type',
        'size',
        'crew_min',
        'crew_max',
        'passengers',
        'cargo_capacity',
        'consumables',
        'speed',
        'hyperspace_speed',
        'hull_points',
        'shield_points',
        'armor',
        'weapons',
        'base_cost',
        'is_restricted',
        'image_url'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'crew_min' => 'integer',
        'crew_max' => 'integer',
        'passengers' => 'integer',
        'cargo_capacity' => 'integer',
        'consumables' => 'integer',
        'hull_points' => 'integer',
        'shield_points' => 'integer',
        'armor' => 'integer',
        'base_cost' => 'integer',
        'is_restricted' => 'boolean'
    ];

    /**
     * Get the vehicles created from this template.
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    /**
     * Get formatted size.
     *
     * @return string
     */
    public function getFormattedSize(): string
    {
        $sizeMap = [
            'tiny' => 'Tiny (1-5m)',
            'small' => 'Small (5-20m)',
            'medium' => 'Medium (20-100m)',
            'large' => 'Large (100-500m)',
            'huge' => 'Huge (500-1000m)',
            'gargantuan' => 'Gargantuan (1km+)'
        ];

        return $sizeMap[$this->size] ?? ucfirst($this->size);
    }

    /**
     * Get formatted type.
     *
     * @return string
     */
    public function getFormattedType(): string
    {
        $typeMap = [
            'speeder' => 'Speeder',
            'starfighter' => 'Starfighter',
            'transport' => 'Transport',
            'capital' => 'Capital Ship',
            'walker' => 'Walker',
            'other' => 'Other'
        ];

        return $typeMap[$this->type] ?? ucfirst($this->type);
    }

    /**
     * Get formatted crew count.
     *
     * @return string
     */
    public function getFormattedCrew(): string
    {
        if (!$this->crew_max) {
            return (string) $this->crew_min;
        }

        return $this->crew_min . '-' . $this->crew_max;
    }

    /**
     * Get formatted consumables duration.
     *
     * @return string
     */
    public function getFormattedConsumables(): string
    {
        if ($this->consumables < 1) {
            return 'None';
        }

        if ($this->consumables < 30) {
            return $this->consumables . ' days';
        }

        if ($this->consumables < 365) {
            $months = floor($this->consumables / 30);
            return $months . ' month' . ($months > 1 ? 's' : '');
        }

        $years = floor($this->consumables / 365);
        return $years . ' year' . ($years > 1 ? 's' : '');
    }
}
