<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\Retrieve;
use App\Http\Requests\Category\Store;
use App\Http\Requests\Category\Update;
use App\Http\Requests\Category\Delete;
use App\Repositories\CategoryRepository;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * @var App\Repositories\CategoryRepository
     */
    protected $categoryRepository;
    public function __construct()
    {
        $this->categoryRepository = new CategoryRepository;
    }

    /**
     * Retrieves paginated category.
     *
     * @return Illuminate\Http\JsonResponse The category data in JSON format.
     */
    public function retrievePaginate(Retrieve $request): JsonResponse
    {
        $params = [
            'search' => $request->input('search'),
            'currentPage' => $request->input('page', 1),
            'pageSize' => $request->input('pageSize', 10),
        ];

        $response = $this->categoryRepository->retrievePaginate($params);
        return $this->categoryRepository->getJsonResponse($response);
    }

    /**
     * Retrieves all category.
     *
     * @return Illuminate\Http\JsonResponse The category data in JSON format.
     */
    public function retrieveAll(): JsonResponse
    {
        $response = $this->categoryRepository->retrieveAll();
        return $this->categoryRepository->getJsonResponse($response);
    }

    /**
     * Add a category.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function store(Store $request): JsonResponse
    {

        $data = [
            'name' => $request->input('name'),
        ];

        $response = $this->categoryRepository->store($data);
        return $this->categoryRepository->getJsonResponse($response);
    }

    /**
     * Update a category.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function update(Update $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
            'name' => $request->input('name'),
        ];

        $response = $this->categoryRepository->update($data);
        return $this->categoryRepository->getJsonResponse($response);
    }

    /**
     * Delete a category.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function delete(Delete $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
        ];

        $response = $this->categoryRepository->delete($data);
        return $this->categoryRepository->getJsonResponse($response);
    }

}
