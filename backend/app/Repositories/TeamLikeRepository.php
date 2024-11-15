<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\TeamLike;
use Illuminate\Support\Facades\DB;

class TeamLikeRepository extends JsonResponseFormat
{

    /**
     * Add like or update the like if already exists.
     *
     * @param array $data
     * @return array
     */
    public function like(array $data): array
    {
        DB::beginTransaction();
        try {
            $existingLike = TeamLike::where('game_id', $data['game_id'])
                ->where('user_id', $data['user_id'])
                ->first();

            if ($existingLike) {
                $existingLike->delete();
            }

            $teamLike = TeamLike::create([
                'team_id' => $data['team_id'],
                'game_id' => $data['game_id'],
                'user_id' => $data['user_id'],
            ]);

            DB::commit();
            return [
                'success' => true,
                'message' => 'Team like updated successfully',
                'body' => $teamLike,
            ];

        } catch (\Throwable $e) {
            DB::rollBack();
            return [
                'success' => false,
                'error' => 'Failed to update like. ' . $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Remove like.
     *
     * @param int $teamLikeId
     * @return array
     */
    public function unlike(int $teamLikeId): array
    {
        DB::beginTransaction();
        try {
            $teamLike = TeamLike::findOrFail($teamLikeId);

            $teamLike->delete();

            DB::commit();
            return [
                'success' => true,
                'message' => 'Team unliked successfully',
                'body' => $teamLike,
            ];

        } catch (\Throwable $e) {
            DB::rollBack();
            return [
                'success' => false,
                'error' => 'Failed to unlike team. ' . $e->getMessage(),
                'status' => 500,
            ];
        }
    }
}
