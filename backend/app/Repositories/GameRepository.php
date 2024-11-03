<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\Game;
use Illuminate\Support\Facades\DB;

class GameRepository extends JsonResponseFormat
{

    /**
     * Get paginated games
     *
     * @param array $params
     * @return array
     */
    public function retrievePaginate(array $params): array
    {
        $query = Game::query();
        $query->with(['event', 'team1', 'team2']);

        if (!empty($params['search'])) {
            $searchTerm = '%' . $params['search'] . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhereHas('team1', function($q) use ($searchTerm) {
                        $q->where('name', 'like', $searchTerm);
                    })
                    ->orWhereHas('team2', function($q) use ($searchTerm) {
                        $q->where('name', 'like', $searchTerm);
                    });
            });
        }

        if (!empty($params['event'])) {
            $query->where('event_id', $params['event']);
        }

        if (!empty($params['status'])) {
            $query->where('status', $params['status']);
        }

        $currentPage = $params['currentPage'] ?? 1;
        $pageSize = $params['pageSize'] ?? 10;

        $games = $query->paginate($pageSize, ['*'], 'page', $currentPage);

        $retrievedCount = count($games->items());

        return [
            'message' => "{$retrievedCount} games retrieved successfully",
            'body' => $games->items(),
            'current_page' => $games->currentPage(),
            'from' => $games->firstItem(),
            'to' => $games->lastItem(),
            'last_page' => $games->lastPage(),
            'skip' => ($currentPage - 1) * $pageSize,
            'take' => $pageSize,
            'total' => $games->total(),
        ];
    }

    /**
     * Get all games
     *
     * @param array $params
     * @return array
     */
    public function retrieveAll(array $params): array
    {
        $query = Game::query();
        $query->with(['event.category', 'team1', 'team2']);

        if (!empty($params['search'])) {
            $searchTerm = '%' . $params['search'] . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhereHas('team1', function($q) use ($searchTerm) {
                        $q->where('name', 'like', $searchTerm);
                    })
                    ->orWhereHas('team2', function($q) use ($searchTerm) {
                        $q->where('name', 'like', $searchTerm);
                    });
            });
        }

        if (!empty($params['event'])) {
            $query->where('event_id', $params['event']);
        }

        if (!empty($params['status'])) {
            $query->where('status', $params['status']);
        }

        $games = $query->get();

        return [
            'message' => "all games retrieved successfully",
            'body' => $games,
            'total' => $games->count(),
        ];
    }

    /**
     * Add a game.
     *
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        DB::beginTransaction();
        try {

            $game = Game::create([
                'name' => $data['name'],
                'event_id' => $data['event_id'],
                'team1_id' => $data['team1_id'],
                'team2_id' => $data['team2_id'],
                'date' => $data['date']
            ]);

            DB::commit();
            return [
                'message' => 'Game added successfully',
                'body' => $game
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
     * Update a game.
     *
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        DB::beginTransaction();
        try {
            $game = Game::findOrFail($data['id']);

            $game->update([
                'name' => $data['name'],
                'event_id' => $data['event_id'],
                'team1_id' => $data['team1_id'],
                'team2_id' => $data['team2_id'],
                'date' => $data['date']
            ]);

            DB::commit();
            return [
                'message' => 'Game updated successfully',
                'body' => $game,
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
     * Update a game result.
     *
     * @param array $data
     * @return array
     */
    public function updateResult(array $data): array
    {
        DB::beginTransaction();
        try {
            $game = Game::findOrFail($data['id']);

            $winner = null;
            $loser = null;

            if ($data['status'] === 'completed') {
                if ($data['team1_score'] > $data['team2_score']) {
                    $winner = $game->team1_id;
                    $loser = $game->team2_id;
                } elseif ($data['team1_score'] < $data['team2_score']) {
                    $winner = $game->team2_id;
                    $loser = $game->team1_id;
                }
            }

            $game->update([
                'team1_score' => $data['team1_score'],
                'team2_score' => $data['team2_score'],
                'status' => $data['status'],
                'winner' => $winner,
                'loser' => $loser
            ]);

            DB::commit();
            return [
                'message' => 'Game result updated successfully',
                'body' => $game,
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
     * Delete a game.
     *
     * @param array $data
     * @return array
     */
    public function delete(array $data): array
    {
        DB::beginTransaction();
        try {
            $game = Game::findOrFail($data['id']);

            $game->delete();

            DB::commit();
            return [
                'message' => 'Game deleted successfully',
                'body' => $game,
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

