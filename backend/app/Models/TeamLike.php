<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'team_id'
    ];

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function game(): belongsTo
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function team(): belongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
