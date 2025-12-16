<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class UserWatchlist extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'identifier_id',
        'anime_title',
        'poster_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
