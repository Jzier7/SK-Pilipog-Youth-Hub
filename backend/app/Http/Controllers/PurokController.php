<?php

namespace App\Http\Controllers;

use App\Http\Requests\Purok\RetrieveAll;
use App\Repositories\PurokRepository;
use Illuminate\Http\JsonResponse;

class PurokController extends Controller
{

    /**
     * @var App\Repositories\PurokRepository
     */
    protected $purokRepository;

    public function __construct()
    {
        $this->purokRepository = new PurokRepository;
    }

    /**
     * Retrieves all purok.
     *
     * @return Illuminate\Http\JsonResponse The purok's data in JSON format.
     */
    public function retrieveAll(RetrieveAll $request): JsonResponse
    {
        $params = [
            'search' => $request->input('search'),
            'currentPage' => $request->input('currentPage', 1),
            'pageSize' => $request->input('pageSize', 10),
            'orderBy' => $request->input('orderBy', 'desc'),
        ];

        $response = $this->purokRepository->retrieveAll($params);
        return $this->purokRepository->getJsonResponse($response);
    }
}
