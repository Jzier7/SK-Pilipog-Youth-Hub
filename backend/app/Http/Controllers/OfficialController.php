<?php

namespace App\Http\Controllers;

use App\Http\Requests\Official\Retrieve;
use App\Http\Requests\Official\Store;
use App\Http\Requests\Official\Update;
use App\Http\Requests\Official\Delete;
use App\Repositories\OfficialRepository;
use Illuminate\Http\JsonResponse;

class OfficialController extends Controller
{

    /**
     * @var App\Repositories\OfficialRepository
     */
    protected $officialRepository;

    public function __construct()
    {
        $this->officialRepository = new OfficialRepository;
    }

    /**
     * Retrieves all official.
     *
     * @return Illuminate\Http\JsonResponse The official's data in JSON format.
     */
    public function retrieve(Retrieve $request): JsonResponse
    {
        $params = [
            'search' => $request->input('search'),
            'currentPage' => $request->input('currentPage', 1),
            'pageSize' => $request->input('pageSize', 10),
            'orderBy' => $request->input('orderBy', 'desc'),
            'term' => $request->input('term'),
            'position' => $request->input('position'),
            'is_active' => $request->input('is_active')
        ];

        $response = $this->officialRepository->retrieve($params);
        return $this->officialRepository->getJsonResponse($response);
    }

    /**
     * Add an official.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function store(Store $request): JsonResponse
    {

        $data = [
            'name' => $request->input('name'),
            'position' => $request->input('position'),
            'term' => $request->input('term'),
        ];

        $response = $this->officialRepository->store($data);
        return $this->officialRepository->getJsonResponse($response);
    }

    /**
     * Update an official.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function update(Update $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
            'name' => $request->input('name'),
            'position' => $request->input('position'),
            'term' => $request->input('term'),
        ];

        $response = $this->officialRepository->update($data);
        return $this->officialRepository->getJsonResponse($response);
    }

    /**
     * Delete a official.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function delete(Delete $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
        ];

        $response = $this->officialRepository->delete($data);
        return $this->officialRepository->getJsonResponse($response);
    }
}
