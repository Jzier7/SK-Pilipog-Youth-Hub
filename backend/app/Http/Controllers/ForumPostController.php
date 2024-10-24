<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForumPost\Retrieve;
use App\Http\Requests\ForumPost\Store;
use App\Http\Requests\ForumPost\Update;
use App\Http\Requests\ForumPost\Delete;
use App\Repositories\ForumPostRepository;
use Illuminate\Http\JsonResponse;

class ForumPostController extends Controller
{

    /**
     * @var App\Repositories\ForumPostRepository
     */
    protected $forumPostRepository;

    public function __construct()
    {
        $this->forumPostRepository = new ForumPostRepository;
    }

    /**
     * Retrieves all forumPost.
     *
     * @return Illuminate\Http\JsonResponse The forumPost's data in JSON format.
     */
    public function retrieve(Retrieve $request): JsonResponse
    {
        $params = [
            'search' => $request->input('search'),
            'currentPage' => $request->input('currentPage', 1),
            'pageSize' => $request->input('pageSize', 10),
            'orderBy' => $request->input('orderBy', 'desc'),
        ];

        $response = $this->forumPostRepository->retrieve($params);
        return $this->forumPostRepository->getJsonResponse($response);
    }

    /**
     * Add a forumPost.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function store(Store $request): JsonResponse
    {

        $data = [
            'post' => $request->input('post'),
        ];

        $response = $this->forumPostRepository->store($data);
        return $this->forumPostRepository->getJsonResponse($response);
    }

    /**
     * Update a forumPost.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function update(Update $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
            'post' => $request->input('post'),
        ];

        $response = $this->forumPostRepository->update($data);
        return $this->forumPostRepository->getJsonResponse($response);
    }

    /**
     * Delete a forumPost.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function delete(Delete $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
        ];

        $response = $this->forumPostRepository->delete($data);
        return $this->forumPostRepository->getJsonResponse($response);
    }
}

