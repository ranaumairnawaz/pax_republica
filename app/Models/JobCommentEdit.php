<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCommentEdit extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_comment_id',
        'previous_content',
    ];

    public function jobComment()
    {
        return $this->belongsTo(JobComment::class);
    }
}
