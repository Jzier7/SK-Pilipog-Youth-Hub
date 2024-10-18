<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\RetrieveAll;
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
     * Retrieves all category.
     *
     * @return Illuminate\Http\JsonResponse The category data in JSON format.
     */
    public function retrieveAll(RetrieveAll $request): JsonResponse
    {
        $params = [
            'search' => $request->input('search'),
            'currentPage' => $request->input('page', 1),
            'pageSize' => $request->input('pageSize', 10),
        ];

        $response = $this->categoryRepository->retrieveAll($params);
        return $this->categoryRepository->getJsonResponse($response);
    }
}
