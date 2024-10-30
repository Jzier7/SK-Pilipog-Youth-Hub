<?php

namespace App\Http\Controllers;

use App\Http\Requests\Game\Retrieve;
use App\Http\Requests\Game\Store;
use App\Http\Requests\Game\Update;
use App\Http\Requests\Game\UpdateResult;
use App\Http\Requests\Game\Delete;
use App\Repositories\GameRepository;
use Illuminate\Http\JsonResponse;

class GameController extends Controller
{

    /**
     * @var App\Repositories\GameRepository
     */
    protected $gameRepository;

    public function __construct()
    {
        $this->gameRepository = new GameRepository;
    }

    /**
     * Retrieves all game.
     *
     * @return Illuminate\Http\JsonResponse The game's data in JSON format.
     */
    public function retrieve(Retrieve $request): JsonResponse
    {
        $params = [
            'search' => $request->input('search'),
            'currentPage' => $request->input('currentPage', 1),
            'pageSize' => $request->input('pageSize', 10),
            'orderBy' => $request->input('orderBy', 'desc'),
            'event' => $request->input('event'),
            'status' => $request->input('status')
        ];

        $response = $this->gameRepository->retrieve($params);
        return $this->gameRepository->getJsonResponse($response);
    }

    /**
     * Add an game.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function store(Store $request): JsonResponse
    {

        $data = [
            'name' => $request->input('name'),
            'event_id' => $request->input('event'),
            'team1_id' => $request->input('team1'),
            'team2_id' => $request->input('team2'),
            'date' => $request->input('date')
        ];

        $response = $this->gameRepository->store($data);
        return $this->gameRepository->getJsonResponse($response);
    }

    /**
     * Update an game.
     *
     * @return Illuminate\Http\JsonResponse The game's data in JSON format.
     */
    public function update(Update $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
            'name' => $request->input('name'),
            'event_id' => $request->input('event'),
            'team1_id' => $request->input('team1'),
            'team2_id' => $request->input('team2'),
            'date' => $request->input('date')
        ];

        $response = $this->gameRepository->update($data);
        return $this->gameRepository->getJsonResponse($response);
    }

    /**
     * Update an game result.
     *
     * @return Illuminate\Http\JsonResponse The game's data in JSON format.
     */
    public function updateResult(UpdateResult $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
            'team1_score' => $request->input('team1Score'),
            'team2_score' => $request->input('team2Score'),
            'status' => $request->input('status')
        ];

        $response = $this->gameRepository->updateResult($data);
        return $this->gameRepository->getJsonResponse($response);
    }

    /**
     * Delete an game.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function delete(Delete $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
        ];

        $response = $this->gameRepository->delete($data);
        return $this->gameRepository->getJsonResponse($response);
    }
}
