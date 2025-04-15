<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category',
        'title',
        'creator_id',
        'handler_id',
        'status',
        'character_id',
        'last_activity_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_activity_at' => 'datetime',
    ];

    /**
     * Job category constants
     */
    const CATEGORY_ADVANCEMENT = 'ADVANCEMENT';
    const CATEGORY_APPLICATIONS = 'APPLICATIONS';
    const CATEGORY_BUG_REPORTS = 'BUG_REPORTS';
    const CATEGORY_FEEDBACK = 'FEEDBACK';
    const CATEGORY_PITCH = 'PITCH';
    const CATEGORY_REWORK = 'REWORK';
    const CATEGORY_TP = 'TP'; // Assuming TP stands for something like 'Technical Problem' or similar

    /**
     * Job status constants
     */
    const STATUS_OPEN = 'OPEN';
    const STATUS_CLOSED = 'CLOSED';
    const STATUS_APPROVED = 'APPROVED';
    const STATUS_REJECTED = 'REJECTED';
    const STATUS_CANCELED = 'CANCELED';

    /**
     * Get the user who created the job.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get the user handling the job (admin).
     */
    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handler_id');
    }

    /**
     * Get the character associated with the job (if any).
     */
    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    /**
     * Get the comments for the job.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(JobComment::class);
    }
}
