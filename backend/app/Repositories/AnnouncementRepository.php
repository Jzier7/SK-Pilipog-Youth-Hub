<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\Announcement;
use App\Models\File;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
            },
            'files'
        ]);

        if (!empty($params['latest'])) {
            $todayStart = Carbon::today();
            $todayEnd = Carbon::tomorrow();
            $query->whereBetween('created_at', [$todayStart, $todayEnd]);
        }

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
            $files = $data['files'];
            unset($data['files']);
            $data['author_id'] = Auth::id();
            $announcement = Announcement::create($data);

            if ($files) {

                $fileUploadService = new FileUploadService();

                foreach ($files as $file) {
                    $file_size = $fileUploadService->getFileSize($file);
                    $file_path = $fileUploadService->upload($file, 'users');
                    $file_name = $file->getClientOriginalName();

                    $new_file = new File();
                    $new_file->path = $file_path;
                    $new_file->size = $file_size;
                    $new_file->name = $file_name;

                    $announcement->files()->save($new_file);
                }
            }

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
     * Update an existing announcement.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        DB::beginTransaction();
        try {
            $announcement = Announcement::findOrFail($data['id']);

            $files = $data['files'] ?? null;
            unset($data['files']);

            $announcement->update($data);

            if ($files && !is_array($files)) {
                $fileUploadService = new FileUploadService();

                $file_size = $fileUploadService->getFileSize($files);
                $file_path = $fileUploadService->upload($files, 'users');
                $file_name = $files->getClientOriginalName();

                $new_file = new File();
                $new_file->path = $file_path;
                $new_file->size = $file_size;
                $new_file->name = $file_name;

                $announcement->files()->save($new_file);
            }
            elseif (is_array($files) && count($files) > 0) {
                foreach ($announcement->files as $old_file) {
                    \Storage::delete($old_file->path);
                    $old_file->delete();
                }

                foreach ($files as $file) {
                    $fileUploadService = new FileUploadService();

                    $file_size = $fileUploadService->getFileSize($file);
                    $file_path = $fileUploadService->upload($file, 'users');
                    $file_name = $file->getClientOriginalName();

                    $new_file = new File();
                    $new_file->path = $file_path;
                    $new_file->size = $file_size;
                    $new_file->name = $file_name;

                    $announcement->files()->save($new_file);
                }
            }

            DB::commit();
            return [
                'message' => 'Announcement updated successfully',
                'body' => $announcement,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => 'An error occurred while updating the announcement: ' . $e->getMessage(),
                'status' => 500
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

