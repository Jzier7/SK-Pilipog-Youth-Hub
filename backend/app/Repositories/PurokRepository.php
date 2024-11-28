<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\Purok;
use Illuminate\Support\Facades\DB;

class PurokRepository extends JsonResponseFormat
{
    /**
     * Get paginated purok.
     *
     * @param array $params
     * @return array
     */
    public function retrievePaginate(array $params): array
    {
        $query = Purok::query();

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

        $purokRecords = $query->paginate($pageSize, ['*'], 'page', $currentPage);

        $retrievedCount = count($purokRecords->items());

        return [
            'message' => "{$retrievedCount} Purok records retrieved successfully",
            'body' => $purokRecords->items(),
            'current_page' => $purokRecords->currentPage(),
            'from' => $purokRecords->firstItem(),
            'to' => $purokRecords->lastItem(),
            'last_page' => $purokRecords->lastPage(),
            'skip' => ($currentPage - 1) * $pageSize,
            'take' => $pageSize,
            'total' => $purokRecords->total(),
        ];
    }

    /**
     * Get all purok
     *
     * @param array $params
     * @return array
     */
    public function retrieveAll(): array
    {
        $puroks = Purok::select('id', 'name')->get();

        return [
            'message' => "All Purok records retrieved successfully",
            'body' => $puroks,
            'total' => $puroks->count(),
        ];
    }

    /**
     * Add an purok.
     *
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        DB::beginTransaction();
        try {

            $purok = Purok::create([
                'name' => $data['name'],
            ]);

            DB::commit();
            return [
                'message' => 'Purok added successfully',
                'body' => $purok
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
     * Update an purok.
     *
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        DB::beginTransaction();
        try {
            $purok = Purok::findOrFail($data['id']);

            $purok->update([
                'name' => $data['name'],
            ]);

            DB::commit();
            return [
                'message' => 'Purok updated successfully',
                'body' => $purok,
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
     * Delete a purok.
     *
     * @param array $data
     * @return array
     */
    public function delete(array $data): array
    {
        DB::beginTransaction();
        try {
            $purok = Purok::findOrFail($data['id']);

            $purok->delete();

            DB::commit();
            return [
                'message' => 'Purok deleted successfully',
                'body' => $purok,
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
