<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\RetrieveUsers;
use App\Http\Requests\User\RetrieveAdmins;
use App\Http\Requests\User\Store;
use App\Http\Requests\User\UpdateData;
use App\Http\Requests\User\UpdateStatus;
use App\Http\Requests\User\UpdateParticipationCount;
use App\Http\Requests\User\Delete;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{

    /**
     * @var App\Repositories\UserRepository
     */
    protected $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository;
    }

    /**
     * Retrieves the authenticated user's information.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function me(): JsonResponse
    {
        $response = $this->userRepository->me();
        return $this->userRepository->getJsonResponse($response);
    }

    /**
     * Retrieves paginated users.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function retrieveUsers(RetrieveUsers $request): JsonResponse
    {
        $params = [
            'search' => $request->input('search'),
            'currentPage' => $request->input('currentPage', 1),
            'pageSize' => $request->input('pageSize', 10),
            'orderBy' => $request->input('orderBy', 'desc'),
            'activeVoter' => $request->input('activeVoter'),
        ];

        $response = $this->userRepository->retrieveUsers($params);
        return $this->userRepository->getJsonResponse($response);
    }

    /**
     * Retrieves paginated admins.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function retrieveAdmins(RetrieveAdmins $request): JsonResponse
    {
        $params = [
            'search' => $request->input('search'),
            'currentPage' => $request->input('currentPage', 1),
            'pageSize' => $request->input('pageSize', 10),
            'orderBy' => $request->input('orderBy', 'desc'),
            'isAdmin' => true
        ];

        $response = $this->userRepository->retrieveUsers($params);
        return $this->userRepository->getJsonResponse($response);
    }

    /**
     * Retrieves all users.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function retrievePlayers(): JsonResponse
    {
        $response = $this->userRepository->retrievePlayers();
        return $this->userRepository->getJsonResponse($response);
    }

    /**
     * Add a user.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function store(Store $request): JsonResponse
    {

        $data = [
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'birthdate' => $request->input('birthdate'),
            'gender' => $request->input('gender'),
            'purok' => $request->input('purok'),
            'username' => $request->input('username'),
        ];

        $response = $this->userRepository->store($data);
        return $this->userRepository->getJsonResponse($response);
    }

    /**
     * Update a user.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function updateData(UpdateData $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'birthdate' => $request->input('birthdate'),
            'gender' => $request->input('gender'),
            'purok' => $request->input('purok'),
            'username' => $request->input('username'),
        ];

        $response = $this->userRepository->updateData($data);
        return $this->userRepository->getJsonResponse($response);
    }

    /**
     * Delete a user.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function delete(Delete $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
        ];

        $response = $this->userRepository->delete($data);
        return $this->userRepository->getJsonResponse($response);
    }

    /**
     * Update user status.
     *
     * @return Illuminate\Http\JsonResponse The user's data in JSON format.
     */
    public function updateStatus(UpdateStatus $request): JsonResponse
    {

        $data = [
            'id' => $request->input('id'),
            'status' => $request->input('status')
        ];

        $response = $this->userRepository->updateStatus($data);
        return $this->userRepository->getJsonResponse($response);
    }

    /**
     * Update user participation counts.
     *
     * @return \Illuminate\Http\JsonResponse The participation count of users in JSON format
     */
    public function updateParticipationCount(UpdateParticipationCount $request): JsonResponse
    {
        $data = $request->all();

        $response = $this->userRepository->updateParticipationCount($data);
        return response()->json($response);
    }

    /**
     * Retrieves the total number of registered voters.
     *
     * @return Illuminate\Http\JsonResponse The voters count in JSON format.
     */
    public function getVotersCount(): JsonResponse
    {
        $response = $this->userRepository->getVotersCount();
        return $this->userRepository->getJsonResponse($response);
    }

    /**
     * Retrieves the count of users per "Purok".
     *
     * @return Illuminate\Http\JsonResponse The count per Purok in JSON format.
     */
    public function getCountPerPurok(): JsonResponse
    {
        $response = $this->userRepository->getCountPerPurok();
        return $this->userRepository->getJsonResponse($response);
    }
}
