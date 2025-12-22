<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_premium',
        'premium_until',
        'active_subscription_id',
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
            'is_premium' => 'boolean',
            'premium_until' => 'datetime',
            'active_subscription_id' => 'string',
        ];
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function watchlists()
    {
        return $this->hasMany(UserWatchlist::class);
    }

    /**
     * The user's active subscription (nullable).
     */
    public function activeSubscription()
    {
        return $this->belongsTo(Subscription::class, 'active_subscription_id');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Computed `is_premium` attribute.
     * Prefer deriving premium state from `premium_until` (and active subscription).
     */
    public function getIsPremiumAttribute(): bool
    {
        if ($this->premium_until instanceof \Illuminate\Support\Carbon) {
            return $this->premium_until->isFuture();
        }

        return (bool) ($this->attributes['is_premium'] ?? false);
    }

    /**
     * Compatibility helper method used elsewhere in code.
     */
    public function isPremium(): bool
    {
        return $this->getIsPremiumAttribute();
    }
}
