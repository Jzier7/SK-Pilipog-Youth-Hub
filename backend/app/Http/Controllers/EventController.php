<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\Retrieve;
use App\Http\Requests\Event\Store;
use App\Http\Requests\Event\Update;
use App\Http\Requests\Event\Delete;
use App\Repositories\EventRepository;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{

    /**
     * @var App\Repositories\EventRepository
     */
    protected $eventRepository;

    public function __construct()
    {
        $this->eventRepository = new EventRepository;
    }

    /**
     * Retrieves paginated event.
     *
     * @return Illuminate\Http\JsonResponse The event's data in JSON format.
     */
    public function retrievePaginate(Retrieve $request): JsonResponse
    {
        $params = [
            'search' => $request->input('search'),
            'currentPage' => $request->input('currentPage', 1),
            'pageSize' => $request->input('pageSize', 10),
            'orderBy' => $request->input('orderBy', 'desc'),
            'category' => $request->input('category'),
        ];

        $response = $this->eventRepository->retrievePaginate($params);
        return $this->eventRepository->getJsonResponse($response);
    }

    /**
     * Retrieves all event.
     *
     * @return Illuminate\Http\JsonResponse The event's data in JSON format.
     */
    public function retrieveAll(): JsonResponse
    {
        $response = $this->eventRepository->retrieveAll();
        return $this->eventRepository->getJsonResponse($response);
    }

    /**
     * Add an event.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function store(Store $request): JsonResponse
    {

        $data = [
            'name' => $request->input('name'),
            'category_id' => $request->input('category')
        ];

        $response = $this->eventRepository->store($data);
        return $this->eventRepository->getJsonResponse($response);
    }

    /**
     * Update an event.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function update(Update $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
            'name' => $request->input('name'),
            'category_id' => $request->input('category')
        ];

        $response = $this->eventRepository->update($data);
        return $this->eventRepository->getJsonResponse($response);
    }

    /**
     * Delete an event.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function delete(Delete $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
        ];

        $response = $this->eventRepository->delete($data);
        return $this->eventRepository->getJsonResponse($response);
    }
}

