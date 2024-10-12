<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\Category;

class CategoryRepository extends JsonResponseFormat
{
    /**
     * Get all category with optional filtering and pagination.
     *
     * @param array $params
     * @return array
     */
    public function retrieveAll(array $params): array
    {
        $query = Category::query();

        if (!empty($params['search'])) {
            $query->where('name', 'like', '%' . $params['search'] . '%');
        };

        if (!empty($params['orderBy'])) {
            $query->orderBy('created_at', $params['orderBy']);
        }

        $currentPage = $params['currentPage'] ?? 1;
        $pageSize = $params['pageSize'] ?? 10;

        $category = $query->paginate($pageSize, ['*'], 'page', $currentPage);

        return [
            'message' => 'All category retrieved successfully',
            'body' => $category->items(),
            'current_page' => $category->currentPage(),
            'from' => $category->firstItem(),
            'to' => $category->lastItem(),
            'last_page' => $category->lastPage(),
            'skip' => ($currentPage - 1) * $pageSize,
            'take' => $pageSize,
            'total' => $category->total(),
        ];
    }
}

