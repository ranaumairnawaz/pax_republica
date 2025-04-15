<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobComment extends Model
{
   use HasFactory;

    /**
     * Get the user who wrote the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the edits for this job comment.
     */
    public function edits(): HasMany
    {
        return $this->hasMany(JobCommentEdit::class, 'job_comment_id');
    }
}
