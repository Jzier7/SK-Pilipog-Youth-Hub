<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\Team;
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
        $query = Team::with('users');

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
                'players' => $team->users->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => "{$user->first_name} {$user->last_name}",
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
                $team->users()->attach($data['players']);
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
                $team->users()->sync($data['players']);
            } else {
                $team->users()->detach();
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
