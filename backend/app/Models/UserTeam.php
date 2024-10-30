<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'team_id'
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_id');
    }

    public function teams(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
