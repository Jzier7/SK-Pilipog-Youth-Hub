<?php

namespace App\Http\Controllers;

use App\Http\Requests\Position\Retrieve;
use App\Http\Requests\Position\Store;
use App\Http\Requests\Position\Update;
use App\Http\Requests\Position\Delete;
use App\Repositories\PositionRepository;
use Illuminate\Http\JsonResponse;

class PositionController extends Controller
{

    /**
     * @var App\Repositories\PositionRepository
     */
    protected $positionRepository;

    public function __construct()
    {
        $this->positionRepository = new PositionRepository;
    }

    /**
     * Retrieves paginated position.
     *
     * @return Illuminate\Http\JsonResponse The position's data in JSON format.
     */
    public function retrievePaginate(Retrieve $request): JsonResponse
    {
        $params = [
            'search' => $request->input('search'),
            'currentPage' => $request->input('currentPage', 1),
            'pageSize' => $request->input('pageSize', 10),
            'orderBy' => $request->input('orderBy', 'desc'),
        ];

        $response = $this->positionRepository->retrievePaginate($params);
        return $this->positionRepository->getJsonResponse($response);
    }

    /**
     * Retrieves all position.
     *
     * @return Illuminate\Http\JsonResponse The position's data in JSON format.
     */
    public function retrieveAll(): JsonResponse
    {
        $response = $this->positionRepository->retrieveAll();
        return $this->positionRepository->getJsonResponse($response);
    }

    /**
     * Add a position.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function store(Store $request): JsonResponse
    {

        $data = [
            'name' => $request->input('name'),
            'level' => $request->input('level'),
        ];

        $response = $this->positionRepository->store($data);
        return $this->positionRepository->getJsonResponse($response);
    }

    /**
     * Update a position.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function update(Update $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
            'name' => $request->input('name'),
            'level' => $request->input('level'),
        ];

        $response = $this->positionRepository->update($data);
        return $this->positionRepository->getJsonResponse($response);
    }

    /**
     * Delete a position.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function delete(Delete $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
        ];

        $response = $this->positionRepository->delete($data);
        return $this->positionRepository->getJsonResponse($response);
    }
}

