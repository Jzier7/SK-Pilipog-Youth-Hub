<?php

namespace App\Http\Controllers;

use App\Http\Requests\Term\Retrieve;
use App\Http\Requests\Term\Store;
use App\Http\Requests\Term\Update;
use App\Http\Requests\Term\UpdateStatus;
use App\Http\Requests\Term\Delete;
use App\Repositories\TermRepository;
use Illuminate\Http\JsonResponse;

class TermController extends Controller
{

    /**
     * @var App\Repositories\TermRepository
     */
    protected $termRepository;

    public function __construct()
    {
        $this->termRepository = new TermRepository;
    }

    /**
     * Retrieves paginated term.
     *
     * @return Illuminate\Http\JsonResponse The term's data in JSON format.
     */
    public function retrievePaginate(Retrieve $request): JsonResponse
    {
        $params = [
            'search' => $request->input('search'),
            'currentPage' => $request->input('currentPage', 1),
            'pageSize' => $request->input('pageSize', 10),
            'orderBy' => $request->input('orderBy', 'desc'),
        ];

        $response = $this->termRepository->retrievePaginate($params);
        return $this->termRepository->getJsonResponse($response);
    }

    /**
     * Retrieves all term.
     *
     * @return Illuminate\Http\JsonResponse The term's data in JSON format.
     */
    public function retrieveAll(): JsonResponse
    {
        $response = $this->termRepository->retrieveAll();
        return $this->termRepository->getJsonResponse($response);
    }

    /**
     * Add a term.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function store(Store $request): JsonResponse
    {

        $data = [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ];

        $response = $this->termRepository->store($data);
        return $this->termRepository->getJsonResponse($response);
    }

    /**
     * Update a term.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function update(Update $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ];

        $response = $this->termRepository->update($data);
        return $this->termRepository->getJsonResponse($response);
    }

    /**
     * Update a term status.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function updateStatus(UpdateStatus $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
        ];

        $response = $this->termRepository->updateStatus($data);
        return $this->termRepository->getJsonResponse($response);
    }

    /**
     * Delete a term.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function delete(Delete $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
        ];

        $response = $this->termRepository->delete($data);
        return $this->termRepository->getJsonResponse($response);
    }
}


