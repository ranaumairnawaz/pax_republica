<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'character_id',
        'vehicle_template_id',
        'name',
        'description',
        'registration',
        'current_location',
        'current_hull_points',
        'current_shield_points',
        'status',
        'is_approved'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'current_hull_points' => 'integer',
        'current_shield_points' => 'integer',
        'is_approved' => 'boolean'
    ];

    /**
     * Get the character that owns the vehicle.
     */
    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    /**
     * Get the template for this vehicle.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(VehicleTemplate::class, 'vehicle_template_id');
    }

    /**
     * Get the mods installed on this vehicle.
     */
    public function mods(): HasMany
    {
        return $this->hasMany(VehicleMod::class);
    }

    /**
     * Calculate the maximum hull points including mods.
     *
     * @return int
     */
    public function getMaxHullPoints(): int
    {
        $baseHullPoints = $this->template->hull_points;
        $modBonus = $this->mods->sum(function ($mod) {
            $effects = $mod->template->effects;
            return $effects['hull_points'] ?? 0;
        });
        
        return $baseHullPoints + $modBonus;
    }

    /**
     * Calculate the maximum shield points including mods.
     *
     * @return int
     */
    public function getMaxShieldPoints(): int
    {
        $baseShieldPoints = $this->template->shield_points;
        $modBonus = $this->mods->sum(function ($mod) {
            $effects = $mod->template->effects;
            return $effects['shield_points'] ?? 0;
        });
        
        return $baseShieldPoints + $modBonus;
    }

    /**
     * Apply damage to the vehicle.
     *
     * @param int $damage
     * @return void
     */
    public function applyDamage(int $damage): void
    {
        // First apply damage to shields
        if ($this->current_shield_points > 0) {
            if ($damage <= $this->current_shield_points) {
                $this->current_shield_points -= $damage;
                $damage = 0;
            } else {
                $damage -= $this->current_shield_points;
                $this->current_shield_points = 0;
            }
        }
        
        // Then apply remaining damage to hull
        if ($damage > 0) {
            $this->current_hull_points = max(0, $this->current_hull_points - $damage);
        }
        
        // Update status based on hull points
        if ($this->current_hull_points == 0) {
            $this->status = 'disabled';
        } elseif ($this->current_hull_points < ($this->getMaxHullPoints() * 0.25)) {
            $this->status = 'critical';
        } elseif ($this->current_hull_points < ($this->getMaxHullPoints() * 0.5)) {
            $this->status = 'damaged';
        }
        
        $this->save();
    }

    /**
     * Repair the vehicle's hull and shields.
     *
     * @param int $hullRepair
     * @param int $shieldRepair
     * @return void
     */
    public function repair(int $hullRepair, int $shieldRepair): void
    {
        $maxHull = $this->getMaxHullPoints();
        $maxShield = $this->getMaxShieldPoints();
        
        $this->current_hull_points = min($maxHull, $this->current_hull_points + $hullRepair);
        $this->current_shield_points = min($maxShield, $this->current_shield_points + $shieldRepair);
        
        // Update status based on hull points
        if ($this->current_hull_points >= $maxHull) {
            $this->status = 'operational';
        } elseif ($this->current_hull_points >= ($maxHull * 0.75)) {
            $this->status = 'operational';
        } elseif ($this->current_hull_points >= ($maxHull * 0.5)) {
            $this->status = 'damaged';
        } elseif ($this->current_hull_points >= ($maxHull * 0.25)) {
            $this->status = 'critical';
        } else {
            $this->status = 'disabled';
        }
        
        $this->save();
    }

    /**
     * Fully repair the vehicle.
     *
     * @return void
     */
    public function fullRepair(): void
    {
        $this->current_hull_points = $this->getMaxHullPoints();
        $this->current_shield_points = $this->getMaxShieldPoints();
        $this->status = 'operational';
        $this->save();
    }

    /**
     * Install a mod on the vehicle.
     *
     * @param int $modTemplateId
     * @return VehicleMod|null
     */
    public function installMod(int $modTemplateId): ?VehicleMod
    {
        // Check if the mod is already installed
        $existingMod = $this->mods()->where('mod_template_id', $modTemplateId)->first();
        if ($existingMod) {
            return null;
        }
        
        // Create and save the mod
        $mod = new VehicleMod([
            'vehicle_id' => $this->id,
            'mod_template_id' => $modTemplateId,
            'installation_date' => now(),
        ]);
        $mod->save();
        
        // Refresh the current model to reflect the new mod
        $this->refresh();
        
        return $mod;
    }

    /**
     * Set the approval status of the vehicle.
     *
     * @param bool $approved
     * @return void
     */
    public function setApproval(bool $approved): void
    {
        $this->is_approved = $approved;
        $this->save();
    }
}
