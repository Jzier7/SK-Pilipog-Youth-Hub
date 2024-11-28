<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\Team;
use App\Models\TeamLike;
use Illuminate\Support\Facades\DB;

class TeamRepository extends JsonResponseFormat
{

    /**
     * Get paginated teams
     *
     * @param array $params
     * @return array
     */
    public function retrievePaginate(array $params): array
    {
        $query = Team::with('players');

        if (!empty($params['search'])) {
            $searchTerm = '%' . $params['search'] . '%';
            $query->where('name', 'like', $searchTerm);
        }

        if (!empty($params['category'])) {
            $query->where('position_id', $params['category']);
        }

        if (!empty($params['event'])) {
            $query->where('event_id', $params['event']);
        }

        $currentPage = $params['currentPage'] ?? 1;
        $pageSize = $params['pageSize'] ?? 10;

        $teams = $query->paginate($pageSize, ['*'], 'page', $currentPage);

        $teamsData = $teams->map(function ($team) {
            return [
                'id' => $team->id,
                'name' => $team->name,
                'event_id' => $team->event_id,
                'players' => $team->players->map(function ($player) {
                    return [
                        'id' => $player->id,
                        'name' => "{$player->first_name} {$player->last_name}",
                    ];
                }),
            ];
        });

        $retrievedCount = count($teams->items());

        return [
            'message' => "{$retrievedCount} teams retrieved successfully",
            'body' => $teamsData,
            'current_page' => $teams->currentPage(),
            'from' => $teams->firstItem(),
            'to' => $teams->lastItem(),
            'last_page' => $teams->lastPage(),
            'skip' => ($currentPage - 1) * $pageSize,
            'take' => $pageSize,
            'total' => $teams->total(),
        ];
    }

    /**
     * Get all teams
     *
     * @param array $params
     * @return array
     */
    public function retrieveAll(array $params): array
    {
        $query = Team::select('id', 'name');

        if (!empty($params['event'])) {
            $query->where('event_id', $params['event']);
        }

        $teams = $query->get();

        return [
            'message' => 'All teams retrieved successfully',
            'body' => $teams,
            'total' => $teams->count(),
        ];
    }

    /**
     * Add a team.
     *
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        DB::beginTransaction();
        try {
            $team = Team::create([
                'name' => $data['name'],
                'event_id' => $data['event_id'],
            ]);

            if (!empty($data['players']) && is_array($data['players'])) {
                $team->players()->attach($data['players']);
            }

            DB::commit();
            return [
                'message' => 'Team added successfully with players',
                'body' => $team
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Update a team.
     *
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        DB::beginTransaction();
        try {
            $team = Team::findOrFail($data['id']);

            $team->update([
                'name' => $data['name'],
                'event_id' => $data['event_id'],
            ]);

            if (!empty($data['players']) && is_array($data['players'])) {
                $team->players()->sync($data['players']);
            } else {
                $team->players()->detach();
            }

            DB::commit();
            return [
                'message' => 'Team updated successfully with players',
                'body' => $team,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Add like.
     *
     * @param array $data
     * @return array
     */
    public function like(array $data): array
    {
        DB::beginTransaction();
        try {
            $teamLike = TeamLike::create([
                'team_id' => $data['team_id'],
                'game_id' => $data['game_id'],
                'user_id' => $data['user_id'],
            ]);

            DB::commit();
            return [
                'message' => 'Team liked successfully',
                'body' => $teamLike,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Remove like.
     *
     * @param array $data
     * @return array
     */
    public function unlike(array $data): array
    {
        DB::beginTransaction();
        try {
            $teamLike = TeamLike::where('team_id', $data['team_id'])
                ->where('user_id', $data['user_id'])
                ->where('game_id', $data['game_id'])
                ->firstOrFail();

            $teamLike->delete();

            DB::commit();
            return [
                'message' => 'Team unliked successfully',
                'body' => $teamLike,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Delete a team.
     *
     * @param array $data
     * @return array
     */
    public function delete(array $data): array
    {
        DB::beginTransaction();
        try {
            $team = Team::findOrFail($data['id']);

            $team->delete();

            DB::commit();
            return [
                'message' => 'Team deleted successfully',
                'body' => $team,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }
}
