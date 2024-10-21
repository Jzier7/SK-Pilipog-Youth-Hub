<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\Term;
use Illuminate\Support\Facades\DB;

class TermRepository extends JsonResponseFormat
{
    /**
     * Get all term records with optional filtering and pagination.
     *
     * @param array $params
     * @return array
     */
    public function retrieve(array $params): array
    {
        $query = Term::query();

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

        $termRecords = $query->paginate($pageSize, ['*'], 'page', $currentPage);

        return [
            'message' => 'Term records retrieved successfully',
            'body' => $termRecords->items(),
            'current_page' => $termRecords->currentPage(),
            'from' => $termRecords->firstItem(),
            'to' => $termRecords->lastItem(),
            'last_page' => $termRecords->lastPage(),
            'skip' => ($currentPage - 1) * $pageSize,
            'take' => $pageSize,
            'total' => $termRecords->total(),
        ];
    }

    /**
     * Add a term.
     *
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        DB::beginTransaction();
        try {

            $term = Term::create([
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
            ]);

            DB::commit();
            return [
                'message' => 'Term added successfully',
                'body' => $term
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
     * Update a term.
     *
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        DB::beginTransaction();
        try {
            $term = Term::findOrFail($data['id']);

            $term->update([
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
            ]);

            DB::commit();
            return [
                'message' => 'Term updated successfully',
                'body' => $term,
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
     * Update a term status.
     *
     * @param array $data
     * @return array
     */
    public function updateStatus(array $data): array
    {
        DB::beginTransaction();
        try {
            $termToActivate = Term::findOrFail($data['id']);

            Term::where('id', '!=', $termToActivate->id)->update(['is_active' => 0]);

            $termToActivate->update([
                'is_active' => 1,
            ]);

            DB::commit();
            return [
                'message' => 'Term status updated successfully',
                'body' => $termToActivate,
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
     * Delete a term.
     *
     * @param array $data
     * @return array
     */
    public function delete(array $data): array
    {
        DB::beginTransaction();
        try {
            $term = Term::findOrFail($data['id']);

            $term->delete();

            DB::commit();
            return [
                'message' => 'Term deleted successfully',
                'body' => $term,
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



