<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\ForumPost;
use App\Models\Term;
use Illuminate\Support\Facades\DB;

class ForumPostRepository extends JsonResponseFormat
{

    /**
     * Get forumPosts from a term
     *
     * @param array $params
     * @return array
     */
    public function retrieve(array $params): array
    {
        $query = ForumPost::query();

        if (!empty($params['search'])) {
            $searchTerm = '%' . $params['search'] . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('post', 'like', $searchTerm)
                    ->orWhereHas('author', function($q) use ($searchTerm) {
                        $q->where('first_name', 'like', $searchTerm)
                            ->orWhere('last_name', 'like', $searchTerm);
                    });
            });
        }

        if (!empty($params['orderBy'])) {
            $query->orderBy('created_at', $params['orderBy']);
        }

        $query->with(['comments.author', 'author']);

        $currentPage = $params['currentPage'] ?? 1;
        $pageSize = $params['pageSize'] ?? 10;

        $forumPosts = $query->paginate($pageSize, ['*'], 'page', $currentPage);

        return [
            'message' => 'All posts retrieved successfully',
            'body' => $forumPosts->items(),
            'current_page' => $forumPosts->currentPage(),
            'from' => $forumPosts->firstItem(),
            'to' => $forumPosts->lastItem(),
            'last_page' => $forumPosts->lastPage(),
            'skip' => ($currentPage - 1) * $pageSize,
            'take' => $pageSize,
            'total' => $forumPosts->total(),
        ];
    }

    /**
     * Add an forumPost.
     *
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        DB::beginTransaction();
        try {

            $forumPost = ForumPost::create([
                'author_id' => auth()->id(),
                'post' => $data['post'],
            ]);

            DB::commit();
            return [
                'message' => 'Post added successfully',
                'body' => $forumPost
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
     * Update an forumPost.
     *
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        DB::beginTransaction();
        try {
            $forumPost = ForumPost::findOrFail($data['id']);

            $forumPost->update([
                'post' => $data['post'],
            ]);

            DB::commit();
            return [
                'message' => 'Post updated successfully',
                'body' => $forumPost,
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
     * Delete a forumPost.
     *
     * @param array $data
     * @return array
     */
    public function delete(array $data): array
    {
        DB::beginTransaction();
        try {
            $forumPost = ForumPost::findOrFail($data['id']);

            $forumPost->delete();

            DB::commit();
            return [
                'message' => 'Post deleted successfully',
                'body' => $forumPost,
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

