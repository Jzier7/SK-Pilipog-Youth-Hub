<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\Purok;

class PurokRepository extends JsonResponseFormat
{
    /**
     * Get all purok records with optional filtering and pagination.
     *
     * @param array $params
     * @return array
     */
    public function retrieveAll(array $params): array
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

        return [
            'message' => 'All purok records retrieved successfully',
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
}

