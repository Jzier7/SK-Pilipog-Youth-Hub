<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Term extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'is_active'
    ];

    public function officials(): HasMany
    {
        return $this->hasMany(Official::class, 'term_id', 'id');
    }
}
