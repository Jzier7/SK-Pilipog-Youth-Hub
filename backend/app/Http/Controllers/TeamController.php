<?php

namespace App\Http\Controllers;

use App\Http\Requests\Team\Retrieve;
use App\Http\Requests\Team\Store;
use App\Http\Requests\Team\Update;
use App\Http\Requests\Team\Delete;
use App\Repositories\TeamRepository;
use Illuminate\Http\JsonResponse;

class TeamController extends Controller
{

    /**
     * @var App\Repositories\TeamRepository
     */
    protected $teamRepository;

    public function __construct()
    {
        $this->teamRepository = new TeamRepository;
    }

    /**
     * Retrieves all team.
     *
     * @return Illuminate\Http\JsonResponse The team's data in JSON format.
     */
    public function retrieve(Retrieve $request): JsonResponse
    {
        $params = [
            'search' => $request->input('search'),
            'currentPage' => $request->input('currentPage', 1),
            'pageSize' => $request->input('pageSize', 10),
            'orderBy' => $request->input('orderBy', 'desc'),
            'event' => $request->input('event'),
        ];

        $response = $this->teamRepository->retrieve($params);
        return $this->teamRepository->getJsonResponse($response);
    }

    /**
     * Add an team.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function store(Store $request): JsonResponse
    {

        $data = [
            'name' => $request->input('name'),
            'event_id' => $request->input('event'),
            'players' => $request->input('players')
        ];

        $response = $this->teamRepository->store($data);
        return $this->teamRepository->getJsonResponse($response);
    }

    /**
     * Update an team.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function update(Update $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
            'name' => $request->input('name'),
            'event_id' => $request->input('event'),
            'players' => $request->input('players')
        ];

        $response = $this->teamRepository->update($data);
        return $this->teamRepository->getJsonResponse($response);
    }

    /**
     * Delete an team.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function delete(Delete $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
        ];

        $response = $this->teamRepository->delete($data);
        return $this->teamRepository->getJsonResponse($response);
    }
}


