<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Character extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_id',
        'species_id',
        'faction_id',
        'faction_rank_id',
        'status',
        'xp',
        'level',
        'occupation',
        'biography',
        'appearance',
        'homeworld',
        'credits',
        'is_npc',
        'image_url',
        'details',
        'approved_at',
        'submitted_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'details' => 'array',
        'approved_at' => 'datetime',
        'submitted_at' => 'datetime',
        'is_npc' => 'boolean',
        'credits' => 'integer',
        'xp' => 'integer',
        'level' => 'integer'
    ];

    /**
     * Character status constants
     */
    const STATUS_INPROGRESS = 'inprogress';
    const STATUS_PENDING = 'pending';
    const STATUS_ACTIVE = 'active';

    /**
     * Get the user that owns the character.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the species of the character.
     */
    public function species(): BelongsTo
    {
        return $this->belongsTo(Species::class);
    }
    
    /**
     * Get the faction of the character.
     */
    public function faction(): BelongsTo
    {
        return $this->belongsTo(Faction::class);
    }
    
    /**
     * Get the faction rank of the character.
     */
    public function factionRank(): BelongsTo
    {
        return $this->belongsTo(FactionRank::class);
    }
    
    /**
     * Get the attributes of the character.
     */
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'character_attributes')
                    ->withPivot('value', 'xp_spent') // Added 'xp_spent'
                    ->withTimestamps();
    }
    
    /**
     * Get the skills of the character.
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'character_skills')
                    ->withPivot('value')
                    ->withTimestamps();
    }
    
    /**
     * Get the specializations of the character.
     */
    public function specializations(): BelongsToMany
    {
        return $this->belongsToMany(Specialization::class, 'character_specializations')
                    ->withTimestamps();
    }
    
    /**
     * Get the traits of the character.
     */
    public function traits(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\CharacterTrait::class, 'character_traits')
                    ->withTimestamps();
    }
    
    /**
     * Get the scenes the character is participating in.
     */
    public function scenes(): BelongsToMany
    {
        return $this->belongsToMany(Scene::class, 'scene_participants')
                    ->withPivot('joined_at')
                    ->withTimestamps();
    }
    
    /**
     * Get the posts made by the character.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
    
    /**
     * Get the votes cast by the character.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
    
    /**
     * Get the change logs for this character.
     */
    public function changeLogs(): HasMany
    {
        return $this->hasMany(CharacterChangeLog::class);
    }

    /**
     * Get the vehicles owned by the character.
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    /**
     * Check if the character is in progress.
     *
     * @return bool
     */
    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_INPROGRESS;
    }

    /**
     * Check if the character is pending approval.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if the character is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Submit the character for approval.
     *
     * @return bool
     */
    public function submit(): bool
    {
        if ($this->status !== self::STATUS_INPROGRESS) {
            return false;
        }

        $this->status = self::STATUS_PENDING;
        $this->submitted_at = now();
        return $this->save();
    }

    /**
     * Approve the character.
     *
     * @return bool
     */
    public function approve(): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            return false;
        }

        $this->status = self::STATUS_ACTIVE;
        $this->approved_at = now();
        return $this->save();
    }

    /**
     * Calculate the total XP earned by the character.
     *
     * @return int
     */
    public function totalXpEarned(): int
    {
        // Current XP + XP spent on attributes, skills, and specializations
        $attributeXp = $this->attributes()
            ->sum('xp_spent');
            
        $skillXp = $this->skills()
            ->sum('xp_spent');
            
        $specializationXp = $this->specializations()
            ->sum('xp_spent');
            
        return $this->xp + $attributeXp + $skillXp + $specializationXp;
    }
    
    /**
     * Calculate XP available to spend.
     *
     * @return int
     */
    public function availableXp(): int
    {
        return $this->xp;
    }
    
    /**
     * Get the character's level based on total XP.
     *
     * @return int
     */
    public function calculateLevel(): int
    {
        $totalXp = $this->totalXpEarned();
        
        // Simple level calculation - can be adjusted based on game balance
        // Level 1: 0 XP
        // Level 2: 100 XP
        // Level 3: 200 XP
        // etc.
        return min(10, 1 + floor($totalXp / 100));
    }
    
    /**
     * Recalculate and update the character's level.
     *
     * @return bool
     */
    public function updateLevel(): bool
    {
        $newLevel = $this->calculateLevel();
        
        if ($this->level != $newLevel) {
            $this->level = $newLevel;
            return $this->save();
        }
        
        return true;
    }
    
    /**
     * Add XP to the character.
     *
     * @param int $amount
     * @return bool
     */
    public function addXp(int $amount): bool
    {
        if ($amount <= 0) {
            return false;
        }
        
        $this->xp += $amount;
        $this->updateLevel();
        
        return $this->save();
    }
    
    /**
     * Spend XP on an attribute.
     *
     * @param int $attributeId
     * @param int $points Number of points to increase
     * @return bool
     */
    public function spendXpOnAttribute(int $attributeId, int $points = 1): bool
    {
        $characterAttribute = $this->attributes()->where('attribute_id', $attributeId)->first();
        
        if (!$characterAttribute) {
            return false;
        }
        
        // Calculate cost: current value * 5 for each point
        $costPerPoint = $characterAttribute->pivot->value * 5;
        $totalCost = $costPerPoint * $points;
        
        if ($this->xp < $totalCost) {
            return false;
        }
        
        // Update character attribute
        $newValue = $characterAttribute->pivot->value + $points;
        $newXpSpent = $characterAttribute->pivot->xp_spent + $totalCost;
        
        $this->attributes()->updateExistingPivot($attributeId, [
            'value' => $newValue,
            'xp_spent' => $newXpSpent
        ]);
        
        // Deduct XP
        $this->xp -= $totalCost;
        $this->updateLevel();
        
        return $this->save();
    }
    
    /**
     * Spend XP on a skill.
     *
     * @param int $skillId
     * @param int $points Number of points to increase
     * @return bool
     */
    public function spendXpOnSkill(int $skillId, int $points = 1): bool
    {
        $characterSkill = $this->skills()->where('skill_id', $skillId)->first();
        $skill = Skill::find($skillId);
        
        if (!$characterSkill || !$skill) {
            return false;
        }
        
        // Calculate cost based on difficulty and current value
        // 1. Easy: value * 2
        // 2. Medium: value * 3
        // 3. Hard: value * 4
        // 4. Very Hard: value * 5
        $multiplier = $skill->difficulty + 1;
        $costPerPoint = $characterSkill->pivot->value * $multiplier;
        $totalCost = $costPerPoint * $points;
        
        if ($this->xp < $totalCost) {
            return false;
        }
        
        // Update character skill
        $newValue = $characterSkill->pivot->value + $points;
        $newXpSpent = $characterSkill->pivot->xp_spent + $totalCost;
        
        $this->skills()->updateExistingPivot($skillId, [
            'value' => $newValue,
            'xp_spent' => $newXpSpent
        ]);
        
        // Deduct XP
        $this->xp -= $totalCost;
        $this->updateLevel();
        
        return $this->save();
    }
    
    /**
     * Learn a specialization.
     *
     * @param int $specializationId
     * @return bool
     */
    public function learnSpecialization(int $specializationId): bool
    {
        $specialization = Specialization::find($specializationId);
        
        if (!$specialization) {
            return false;
        }
        
        // Check if character already has this specialization
        if ($this->specializations()->where('specialization_id', $specializationId)->exists()) {
            return false;
        }
        
        // Check if character has the required skill
        $relatedSkill = $this->skills()->where('skill_id', $specialization->skill_id)->first();
        if (!$relatedSkill || $relatedSkill->pivot->value < 5) { // Require skill value of 5+ to specialize
            return false;
        }
        
        // Check XP cost
        $xpCost = $specialization->xp_cost;
        if ($this->xp < $xpCost) {
            return false;
        }
        
        // Add specialization
        $this->specializations()->attach($specializationId, [
            'xp_spent' => $xpCost
        ]);
        
        // Deduct XP
        $this->xp -= $xpCost;
        $this->updateLevel();
        
        return $this->save();
    }
    
    /**
     * Add or remove a trait.
     *
     * @param int $traitId
     * @param bool $add Whether to add (true) or remove (false) the trait
     * @return bool
     */
    public function modifyTrait(int $traitId, bool $add = true): bool
    {
        $trait = CharacterTrait::find($traitId);
        
        if (!$trait) {
            return false;
        }
        
        $hasTrait = $this->traits()->where('character_trait_id', $traitId)->exists();
        
        if ($add && !$hasTrait) {
            // Adding a trait
            if ($trait->xp_cost > 0 && $this->xp < $trait->xp_cost) {
                return false;
            }
            
            $this->traits()->attach($traitId);
            
            if ($trait->xp_cost > 0) {
                $this->xp -= $trait->xp_cost;
                $this->updateLevel();
                $this->save();
            }
            
            return true;
        } elseif (!$add && $hasTrait) {
            // Removing a trait
            $this->traits()->detach($traitId);
            
            if ($trait->xp_cost < 0) {
                // Disadvantages return XP when removed
                $this->xp += abs($trait->xp_cost);
                $this->updateLevel();
                $this->save();
            }
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Create a log entry for character changes.
     *
     * @param string $changeType
     * @param mixed $oldValue
     * @param mixed $newValue
     * @param string|null $notes
     * @return \App\Models\CharacterChangeLog
     */
    public function logChange(string $changeType, $oldValue, $newValue, ?string $notes = null)
    {
        return $this->changeLogs()->create([
            'user_id' => auth()->id(),
            'change_type' => $changeType,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'notes' => $notes
        ]);
    }

    /**
     * Scope a query to only include active characters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }
}
