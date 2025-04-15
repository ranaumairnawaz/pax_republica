<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'account_name',
        'email',
        'timezone',
        'user_type',
        'real_name',
        'sex',
        'age',
        'profile',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_online' => 'boolean',
            'last_login_at' => 'datetime',
            'last_activity_at' => 'datetime',
        ];
    }

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->user_type === 'admin';
    }

    /**
     * Check if the user is a player.
     *
     * @return bool
     */
    public function isPlayer(): bool
    {
        return $this->user_type === 'player';
    }
    
    /**
     * Get the characters for the user.
     */
    public function characters(): HasMany
    {
        return $this->hasMany(Character::class);
    }
    
    /**
     * Get the NPCs created by the user.
     */
    public function npcs(): HasMany
    {
        return $this->hasMany(Npc::class);
    }
    
    /**
     * Get the messages sent by the user.
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }
    
    /**
     * Get the messages received by the user.
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }
    
    /**
     * Get the unread messages count for the user.
     *
     * @return int
     */
    public function unreadMessagesCount(): int
    {
        return $this->receivedMessages()
            ->where('is_read', false)
            ->whereNull('deleted_at')
            ->count();
    }

    /**
     * Check if the user is currently online.
     *
     * @return bool
     */
    public function isOnline(): bool
    {
        if ($this->is_online) {
            return true;
        }
        
        // Consider user online if they had activity in the last 5 minutes
        if ($this->last_activity_at && $this->last_activity_at->isAfter(now()->subMinutes(5))) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Update the user's online status.
     *
     * @param bool $status
     * @return bool
     */
    public function updateOnlineStatus(bool $status): bool
    {
        $this->is_online = $status;
        $this->last_activity_at = now();
        return $this->save();
    }
    
    /**
     * Update the user's last activity timestamp.
     *
     * @return bool
     */
    public function touchActivity(): bool
    {
        $this->last_activity_at = now();
        return $this->save();
    }
}
