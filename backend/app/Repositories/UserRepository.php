<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRepository extends JsonResponseFormat
{
    /**
     * Get the currently authenticated user with related data.
     *
     * @return array
     */
    public function me(): array
    {
        $user = User::with(['files', 'role.abilities.route'])->find(Auth::id());

        if ($user) {
            return [
                'message' => 'User retrieved successfully',
                'body' => $user
            ];
        }

        return [
            'message' => 'User not found',
            'body' => null
        ];
    }

    /**
     * Get active voters count.
     *
     * @return array
     */
    public function getVotersCount(): array
    {
        $voters = User::where('active_voter', true)->get();
        $votersCount = $voters->count();

        return [
            'message' => 'Active voters count retrieved successfully',
            'body' => $votersCount
        ];
    }

    /**
     * Get users count per purok.
     *
     * @return array
     */
    public function getCountPerPurok(): array
    {
        $votersPerPurok = User::select('purok')
            ->get()
            ->groupBy('purok')
            ->map(function ($group, $purok) {
                return [
                    'purok' => $purok,
                    'count' => $group->count(),
                ];
            })
            ->values();

        return [
            'message' => 'Users count per purok retrieved successfully',
            'body' => $votersPerPurok
        ];
    }
}

