<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'scene_id',
        'character_id',
        'content',
    ];

    /**
     * Get the scene that owns the post.
     */
    public function scene(): BelongsTo
    {
        return $this->belongsTo(Scene::class);
    }

    /**
     * Get the character that owns the post.
     */
    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    /**
     * Get the edits for this post.
     */
    public function edits(): HasMany
    {
        return $this->hasMany(PostEdit::class);
    }

    /**
     * Create an edit record before updating the post content.
     *
     * @param string $newContent
     * @return bool
     */
    public function updateContent(string $newContent): bool
    {
        // Create an edit record with the current content
        $this->edits()->create([
            'previous_content' => $this->content,
            'edited_at' => now(),
        ]);

        // Update the post content
        $this->content = $newContent;
        return $this->save();
    }
}