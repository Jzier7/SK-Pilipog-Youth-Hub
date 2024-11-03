<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id'
    ];

    public function games(): HasMany
    {
        return $this->hasMany(Game::class, 'event_id', 'id');
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class, 'event_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    protected function getNameWithCategoryAttribute(): string
    {
        return $this->name . ' (' . ($this->category->name ?? '') . ')';
    }
}
