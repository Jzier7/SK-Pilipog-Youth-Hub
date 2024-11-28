<?php

namespace App\Http\Controllers;

use App\Http\Requests\Purok\Retrieve;
use App\Http\Requests\Purok\Store;
use App\Http\Requests\Purok\Update;
use App\Http\Requests\Purok\Delete;
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
     * Retrieves paginated purok.
     *
     * @return Illuminate\Http\JsonResponse The purok's data in JSON format.
     */
    public function retrievePaginate(Retrieve $request): JsonResponse
    {
        $params = [
            'search' => $request->input('search'),
            'currentPage' => $request->input('currentPage', 1),
            'pageSize' => $request->input('pageSize', 10),
            'orderBy' => $request->input('orderBy', 'desc'),
        ];

        $response = $this->purokRepository->retrievePaginate($params);
        return $this->purokRepository->getJsonResponse($response);
    }

    /**
     * Retrieves all purok.
     *
     * @return Illuminate\Http\JsonResponse The position's data in JSON format.
     */
    public function retrieveAll(): JsonResponse
    {
        $response = $this->purokRepository->retrieveAll();
        return $this->purokRepository->getJsonResponse($response);
    }

    /**
     * Add a purok.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function store(Store $request): JsonResponse
    {

        $data = [
            'name' => $request->input('name'),
        ];

        $response = $this->purokRepository->store($data);
        return $this->purokRepository->getJsonResponse($response);
    }

    /**
     * Update a purok.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function update(Update $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
            'name' => $request->input('name'),
        ];

        $response = $this->purokRepository->update($data);
        return $this->purokRepository->getJsonResponse($response);
    }

    /**
     * Delete a purok.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function delete(Delete $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
        ];

        $response = $this->purokRepository->delete($data);
        return $this->purokRepository->getJsonResponse($response);
    }
}
