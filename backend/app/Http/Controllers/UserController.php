<?php

namespace App\Http\Controllers;

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

