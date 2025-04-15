<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleMod extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vehicle_id',
        'mod_template_id',
        'installation_date',
        'notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'installation_date' => 'datetime'
    ];

    /**
     * Get the vehicle this mod is installed on.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the template for this mod.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(ModTemplate::class, 'mod_template_id');
    }

    /**
     * Get the formatted name of the mod.
     *
     * @return string
     */
    public function getFormattedName(): string
    {
        return $this->template->name;
    }

    /**
     * Get the formatted type of the mod.
     *
     * @return string
     */
    public function getFormattedType(): string
    {
        return $this->template->getFormattedType();
    }

    /**
     * Get the formatted effects of the mod.
     *
     * @return string
     */
    public function getFormattedEffects(): string
    {
        return $this->template->getEffectsSummary();
    }

    /**
     * Remove this mod from the vehicle.
     *
     * @return bool
     */
    public function remove(): bool
    {
        return $this->delete();
    }
}
