<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\Position;
use Illuminate\Support\Facades\DB;

class PositionRepository extends JsonResponseFormat
{
    /**
     * Get paginated position
     *
     * @param array $params
     * @return array
     */
    public function retrievePaginate(array $params): array
    {
        $query = Position::query();

        if (!empty($params['search'])) {
            $query->where(function ($subQuery) use ($params) {
                $subQuery->where('name', 'like', '%' . $params['search'] . '%');
            });
        }

        if (!empty($params['orderBy'])) {
            $query->orderBy('created_at', $params['orderBy']);
        }

        $currentPage = $params['currentPage'] ?? 1;
        $pageSize = $params['pageSize'] ?? 10;

        $positionRecords = $query->paginate($pageSize, ['*'], 'page', $currentPage);

        $retrievedCount = count($positionRecords->items());

        return [
            'message' => "{$retrievedCount} Position records retrieved successfully",
            'body' => $positionRecords->items(),
            'current_page' => $positionRecords->currentPage(),
            'from' => $positionRecords->firstItem(),
            'to' => $positionRecords->lastItem(),
            'last_page' => $positionRecords->lastPage(),
            'skip' => ($currentPage - 1) * $pageSize,
            'take' => $pageSize,
            'total' => $positionRecords->total(),
        ];
    }

    /**
     * Get all position
     *
     * @param array $params
     * @return array
     */
    public function retrieveAll(): array
    {
        $positions = Position::select('id', 'name')->get();

        return [
            'message' => "All Position records retrieved successfully",
            'body' => $positions,
            'total' => $positions->count(),
        ];
    }

    /**
     * Add an position.
     *
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        DB::beginTransaction();
        try {

            $position = Position::create([
                'name' => $data['name'],
                'level' => $data['level'],
            ]);

            DB::commit();
            return [
                'message' => 'Position added successfully',
                'body' => $position
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
     * Update an position.
     *
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        DB::beginTransaction();
        try {
            $position = Position::findOrFail($data['id']);

            $position->update([
                'name' => $data['name'],
                'level' => $data['level'],
            ]);

            DB::commit();
            return [
                'message' => 'Position updated successfully',
                'body' => $position,
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
     * Delete a position.
     *
     * @param array $data
     * @return array
     */
    public function delete(array $data): array
    {
        DB::beginTransaction();
        try {
            $position = Position::findOrFail($data['id']);

            $position->delete();

            DB::commit();
            return [
                'message' => 'Position deleted successfully',
                'body' => $position,
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


