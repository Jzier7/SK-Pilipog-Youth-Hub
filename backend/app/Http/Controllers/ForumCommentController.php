<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForumComment\Retrieve;
use App\Http\Requests\ForumComment\Store;
use App\Http\Requests\ForumComment\Update;
use App\Http\Requests\ForumComment\Delete;
use App\Repositories\ForumCommentRepository;
use Illuminate\Http\JsonResponse;

class ForumCommentController extends Controller
{

    /**
     * @var App\Repositories\ForumCommentRepository
     */
    protected $forumCommentRepository;

    public function __construct()
    {
        $this->forumCommentRepository = new ForumCommentRepository;
    }

    /**
     * Retrieves all forumComment.
     *
     * @return Illuminate\Http\JsonResponse The forumComment's data in JSON format.
     */
    public function retrieve(Retrieve $request): JsonResponse
    {
        $params = [
            'search' => $request->input('search'),
            'currentPage' => $request->input('currentPage', 1),
            'pageSize' => $request->input('pageSize', 10),
            'orderBy' => $request->input('orderBy', 'desc'),
            'post' => $request->input('post')
        ];

        $response = $this->forumCommentRepository->retrieve($params);
        return $this->forumCommentRepository->getJsonResponse($response);
    }

    /**
     * Add a forumComment.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function store(Store $request): JsonResponse
    {

        $data = [
            'post_id' => $request->input('post'),
            'comment' => $request->input('comment')
        ];

        $response = $this->forumCommentRepository->store($data);
        return $this->forumCommentRepository->getJsonResponse($response);
    }

    /**
     * Update a forumComment.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function update(Update $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
            'comment' => $request->input('comment'),
        ];

        $response = $this->forumCommentRepository->update($data);
        return $this->forumCommentRepository->getJsonResponse($response);
    }

    /**
     * Delete a forumComment.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function delete(Delete $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
        ];

        $response = $this->forumCommentRepository->delete($data);
        return $this->forumCommentRepository->getJsonResponse($response);
    }
}


