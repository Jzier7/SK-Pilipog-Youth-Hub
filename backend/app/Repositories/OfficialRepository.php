<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\Official;
use App\Models\Term;
use Illuminate\Support\Facades\DB;

class OfficialRepository extends JsonResponseFormat
{

    /**
     * Get paginated officials
     *
     * @param array $params
     * @return array
     */
    public function retrievePaginate(array $params): array
    {
        $query = Official::query();
        $isActiveTerm = null;

        if (!empty($params['is_active'])) {
            $isActiveTerm = Term::where('is_active', 1)->first();
            if (!$isActiveTerm) {
                return [
                    'message' => 'No active term found',
                    'body' => [
                        'officials' => [],
                        'term' => null,
                    ],
                    'current_page' => 1,
                    'from' => 0,
                    'to' => 0,
                    'last_page' => 1,
                    'skip' => 0,
                    'take' => 0,
                    'total' => 0,
                ];
            }
            $query->where('term_id', $isActiveTerm->id);
        } elseif (!empty($params['term'])) {
            $term = Term::find($params['term']);
            if ($term) {
                $query->where('term_id', $term->id);
            }
        }

        if (!empty($params['search'])) {
            $searchTerm = '%' . $params['search'] . '%';
            $query->where('name', 'like', $searchTerm);
        }

        if (!empty($params['position'])) {
            $query->where('position_id', $params['position']);
        }

        if (!empty($params['orderBy'])) {
            $query->orderBy('created_at', $params['orderBy']);
        }

        $query->with(['position', 'term']);

        $currentPage = $params['currentPage'] ?? 1;
        $pageSize = $params['pageSize'] ?? 10;

        $officials = $query->paginate($pageSize, ['*'], 'page', $currentPage);

        $retrievedCount = count($officials->items());

        return [
            'message' => "{$retrievedCount} officials retrieved successfully",
            'body' => [
                'officials' => $officials->items(),
                'term' => !empty($params['term']) ? $term : $isActiveTerm,
            ],
            'current_page' => $officials->currentPage(),
            'from' => $officials->firstItem(),
            'to' => $officials->lastItem(),
            'last_page' => $officials->lastPage(),
            'skip' => ($currentPage - 1) * $pageSize,
            'take' => $pageSize,
            'total' => $officials->total(),
        ];
    }

    /**
     * Get active officials
     *
     * @return array
     */
    public function retrieveActive(): array
    {
        $query = Official::query();
        $isActiveTerm = Term::where('is_active', 1)->first();

        if (!$isActiveTerm) {
            return [
                'message' => 'No active term found',
                'body' => [
                    'officials' => [],
                    'term' => null,
                ],
                'total' => 0,
            ];
        }

        $query->where('term_id', $isActiveTerm->id);
        $query->with(['position', 'term']);

        $officials = $query->get();

        return [
            'message' => 'All active officials retrieved successfully',
            'body' => [
                'officials' => $officials,
                'term' => $isActiveTerm,
            ],
            'total' => count($officials),
        ];
    }

    /**
     * Add an official.
     *
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        DB::beginTransaction();
        try {

            $official = Official::create([
                'name' => $data['name'],
                'position_id' => $data['position'],
                'term_id' => $data['term'],
            ]);

            DB::commit();
            return [
                'message' => 'Official added successfully',
                'body' => $official
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
     * Update an official.
     *
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        DB::beginTransaction();
        try {
            $official = Official::findOrFail($data['id']);

            $official->update([
                'name' => $data['name'],
                'position_id' => $data['position'],
                'term_id' => $data['term'],
            ]);

            DB::commit();
            return [
                'message' => 'Official updated successfully',
                'body' => $official,
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
     * Delete a official.
     *
     * @param array $data
     * @return array
     */
    public function delete(array $data): array
    {
        DB::beginTransaction();
        try {
            $official = Official::findOrFail($data['id']);

            $official->delete();

            DB::commit();
            return [
                'message' => 'Official deleted successfully',
                'body' => $official,
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

