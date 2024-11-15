<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'event_id',
        'team1_id',
        'team2_id',
        'team1_score',
        'team2_score',
        'winner',
        'loser',
        'status',
        'date'
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function team1(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team1_id');
    }

    public function team2(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team2_id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'winner');
    }

    public function loser(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'loser');
    }

    public function teamLikes(): hasMany
    {
        return $this->hasMany(TeamLike::class, 'game_id', 'id');
    }
}
