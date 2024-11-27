<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'event_id'
    ];

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_team', 'team_id', 'player_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function teamAsTeam1(): HasMany
    {
        return $this->hasMany(Game::class, 'team1_id', 'id');
    }

    public function teamAsTeam2(): HasMany
    {
        return $this->hasMany(Game::class, 'team2_id', 'id');
    }

    public function teamAsWinner(): HasMany
    {
        return $this->hasMany(Game::class, 'winner', 'id');
    }

    public function teamAsLoser(): HasMany
    {
        return $this->hasMany(Game::class, 'loser', 'id');
    }

    public function teamLikes(): HasMany
    {
        return $this->hasMany(TeamLike::class, 'team_id', 'id');
    }
}
