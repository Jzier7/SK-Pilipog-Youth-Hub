<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\ForumComment;
use App\Models\Term;
use Illuminate\Support\Facades\DB;

class ForumCommentRepository extends JsonResponseFormat
{

    /**
     * Get forumComments from a term
     *
     * @param array $params
     * @return array
     */
    public function retrieve(array $params): array
    {
        $query = ForumComment::query();

        $query->where('post_id', $params['post']);

        if (!empty($params['search'])) {
            $searchTerm = '%' . $params['search'] . '%';
            $query->where('name', 'like', $searchTerm);
        }

        if (!empty($params['orderBy'])) {
            $query->orderBy('created_at', $params['orderBy']);
        }

        $query->with(['author']);

        $currentPage = $params['currentPage'] ?? 1;
        $pageSize = $params['pageSize'] ?? 10;

        $forumComments = $query->paginate($pageSize, ['*'], 'page', $currentPage);

        return [
            'message' => 'All posts retrieved successfully',
            'body' => $forumComments->items(),
            'current_page' => $forumComments->currentPage(),
            'from' => $forumComments->firstItem(),
            'to' => $forumComments->lastItem(),
            'last_page' => $forumComments->lastPage(),
            'skip' => ($currentPage - 1) * $pageSize,
            'take' => $pageSize,
            'total' => $forumComments->total(),
        ];
    }

    /**
     * Add an forumComment.
     *
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        DB::beginTransaction();
        try {

            $forumComment = ForumComment::create([
                'author_id' => auth()->id(),
                'post_id' => $data['post_id'],
                'comment' => $data['comment'],
            ]);

            DB::commit();
            return [
                'message' => 'Comment added successfully',
                'body' => $forumComment
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
     * Update an forumComment.
     *
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        DB::beginTransaction();
        try {
            $forumComment = ForumComment::findOrFail($data['id']);

            $forumComment->update([
                'comment' => $data['comment'],
            ]);

            DB::commit();
            return [
                'message' => 'Comment updated successfully',
                'body' => $forumComment,
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
     * Delete a forumComment.
     *
     * @param array $data
     * @return array
     */
    public function delete(array $data): array
    {
        DB::beginTransaction();
        try {
            $forumComment = ForumComment::findOrFail($data['id']);

            $forumComment->delete();

            DB::commit();
            return [
                'message' => 'Comment deleted successfully',
                'body' => $forumComment,
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


