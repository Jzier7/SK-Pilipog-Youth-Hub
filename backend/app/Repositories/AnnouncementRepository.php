<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\Announcement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AnnouncementRepository extends JsonResponseFormat
{
    /**
     * Get all announcements with optional filtering and pagination.
     *
     * @param array $params
     * @return array
     */
    public function retrieveAll(array $params): array
    {
        $query = Announcement::with([
            'category' => function ($query) {
                $query->select('id', 'name');
            },
            'author' => function ($query) {
                $query->select('id', 'first_name', 'last_name');
            }
        ]);

        if (!empty($params['search'])) {
            $query->where(function ($subQuery) use ($params) {
                $subQuery->where('title', 'like', '%' . $params['search'] . '%')
                    ->orWhereHas('category', function ($query) use ($params) {
                        $query->where('name', 'like', '%' . $params['search'] . '%');
                    });
            });
        }

        if (!empty($params['orderBy'])) {
            $query->orderBy('created_at', $params['orderBy']);
        }

        $currentPage = $params['currentPage'] ?? 1;
        $pageSize = $params['pageSize'] ?? 10;

        $announcements = $query->paginate($pageSize, ['*'], 'page', $currentPage);

        return [
            'message' => 'All announcements retrieved successfully',
            'body' => $announcements->items(),
            'current_page' => $announcements->currentPage(),
            'from' => $announcements->firstItem(),
            'to' => $announcements->lastItem(),
            'last_page' => $announcements->lastPage(),
            'skip' => ($currentPage - 1) * $pageSize,
            'take' => $pageSize,
            'total' => $announcements->total(),
        ];
    }

    /**
     * Store a new announcement.
     *
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        DB::beginTransaction();
        try {

            $data['author_id'] = Auth::id();
            $announcement = Announcement::create($data);

            DB::commit();
            return [
                'message' => 'Announcement created successfully',
                'body' => $announcement,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500
            ];
        }
    }

    /**
     * Update an announcement.
     *
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        DB::beginTransaction();
        try {
            $announcement = Announcement::findOrFail($data['id']);

            $announcement->update([
                'title' => $data['title'],
                'category_id' => $data['category_id'],
                'content' => $data['content'],
            ]);

            DB::commit();
            return [
                'message' => 'Announcement updated successfully',
                'body' => $announcement,
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
     * Delete an announcement.
     *
     * @param array $data
     * @return array
     */
    public function delete(array $data): array
    {
        DB::beginTransaction();
        try {
            $announcement = Announcement::findOrFail($data['id']);

            $announcement->delete();

            DB::commit();
            return [
                'message' => 'Announcement deleted successfully',
                'body' => $announcement,
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

