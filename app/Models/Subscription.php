<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'price',
        'duration_days',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
