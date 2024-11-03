<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryRepository extends JsonResponseFormat
{
    /**
     * Get paginated category
     *
     * @param array $params
     * @return array
     */
    public function retrievePaginate(array $params): array
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

        $retrievedCount = count($category->items());

        return [
            'message' => "{$retrievedCount} category retrieved successfully",
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

    /**
     * Get all categories
     *
     * @return array
     */
    public function retrieveAll(): array
    {
        $categories = Category::select('id', 'name')->get();

        return [
            'message' => 'All categories retrieved successfully',
            'body' => $categories,
            'total' => $categories->count(),
        ];
    }

    /**
     * Add a category.
     *
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        DB::beginTransaction();
        try {

            $category = Category::create([
                'name' => $data['name'],
            ]);

            DB::commit();
            return [
                'message' => 'Category added successfully',
                'body' => $category
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
     * Update a category.
     *
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        DB::beginTransaction();
        try {
            $category = Category::findOrFail($data['id']);

            $category->update([
                'name' => $data['name'],
            ]);

            DB::commit();
            return [
                'message' => 'Category updated successfully',
                'body' => $category,
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
     * Delete a category.
     *
     * @param array $data
     * @return array
     */
    public function delete(array $data): array
    {
        DB::beginTransaction();
        try {
            $category = Category::findOrFail($data['id']);

            $category->delete();

            DB::commit();
            return [
                'message' => 'Category deleted successfully',
                'body' => $category,
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

