<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWatchHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'identifier_id',
        'anime_title',
        'anime_poster',
        'episode_number',
        'position',
        'duration',
        'last_watched_at',
    ];

    protected $casts = [
        'last_watched_at' => 'datetime',
        'position' => 'float',
        'duration' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
