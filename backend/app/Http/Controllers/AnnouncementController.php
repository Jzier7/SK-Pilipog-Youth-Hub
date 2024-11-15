<?php

namespace App\Http\Controllers;

use App\Http\Requests\Announcement\Retrieve;
use App\Http\Requests\Announcement\Store;
use App\Http\Requests\Announcement\Update;
use App\Http\Requests\Announcement\Delete;
use App\Repositories\AnnouncementRepository;
use Illuminate\Http\JsonResponse;

class AnnouncementController extends Controller
{

    /**
     * @var App\Repositories\AnnouncementRepository
     */
    protected $announcementRepository;

    public function __construct()
    {
        $this->announcementRepository = new AnnouncementRepository;
    }

    /**
     * Retrieves paginated announcement.
     *
     * @return Illuminate\Http\JsonResponse The announcement's data in JSON format.
     */
    public function retrievePaginate(Retrieve $request): JsonResponse
    {
        $params = [
            'search' => $request->input('search'),
            'currentPage' => $request->input('currentPage', 1),
            'pageSize' => $request->input('pageSize', 10),
            'orderBy' => $request->input('orderBy', 'desc'),
            'category' => $request->input('category')
        ];

        $response = $this->announcementRepository->retrievePaginate($params);
        return $this->announcementRepository->getJsonResponse($response);
    }

    /**
     * Retrieves latest announcement.
     *
     * @return Illuminate\Http\JsonResponse The announcement's data in JSON format.
     */
    public function retrieveLatest(Retrieve $request): JsonResponse
    {
        $params = [
            'latest' => $request->input('latest')
        ];

        $response = $this->announcementRepository->retrieveLatest($params);
        return $this->announcementRepository->getJsonResponse($response);
    }

    /**
     * Store an announcement.
     *
     * @return Illuminate\Http\JsonResponse The announcement's data in JSON format.
     */
    public function store(Store $request): JsonResponse
    {

        $data = [
            'title' => $request->input('title'),
            'category_id' => $request->input('category'),
            'content' => $request->input('content'),
            'file' => $request->file('file')
        ];

        $response = $this->announcementRepository->store($data);
        return $this->announcementRepository->getJsonResponse($response);
    }

    /**
     * Update an announcement.
     *
     * @return Illuminate\Http\JsonResponse The announcement's data in JSON format.
     */
    public function update(Update $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
            'title' => $request->input('title'),
            'category_id' => $request->input('category'),
            'content' => $request->input('content'),
            'file' => $request->input('file'),
            'file' => $request->file('file')
        ];

        $response = $this->announcementRepository->update($data);
        return $this->announcementRepository->getJsonResponse($response);
    }

    /**
     * Delete an announcement.
     *
     * @return Illuminate\Http\JsonResponse The announcement's data in JSON format.
     */
    public function delete(Delete $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
        ];

        $response = $this->announcementRepository->delete($data);
        return $this->announcementRepository->getJsonResponse($response);
    }

}
