<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level'
    ];

    public function officials(): HasMany
    {
        return $this->hasMany(Official::class, 'position_id', 'id');
    }
}

