<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ability extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'route_id'
    ];

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class, 'route_id', 'id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Route::class, 'role_id', 'id');
    }
}
