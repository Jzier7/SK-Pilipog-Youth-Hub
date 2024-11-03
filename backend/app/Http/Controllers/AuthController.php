<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\AuthRepository;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{

    /**
     * @var App\Repositories\AuthRepository
     */
    protected $authRepository;

    public function __construct()
    {
        $this->authRepository = new AuthRepository();
    }

    /**
     * Handles login requests
     *
     * @param array $request
     * @return Illuminate\Http\Response
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $data = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];

        $rememberMe = $request->input('rememberMe');

        $response = $this->authRepository->login($data, $rememberMe);
        return $this->authRepository->getJsonResponse($response);
    }

    /**
     * Handles login as guest requests
     *
     * @param array $request
     * @return Illuminate\Http\Response
     */
    public function loginAsGuest(): JsonResponse
    {
        $response = $this->authRepository->loginAsGuest();
        return $this->authRepository->getJsonResponse($response);
    }

    /**
     * Handles logout requests
     *
     * @param array $request
     * @return Illuminate\Http\Response
     */
    public function logout(LogoutRequest $request): JsonResponse
    {
        $data = $request->validated();
        $response = $this->authRepository->logout($data);
        return $this->authRepository->getJsonResponse($response);
    }

    /**
     * Handles register requests
     *
     * @param array $request
     * @return Illuminate\Http\Response
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $response = $this->authRepository->register($data);
        return $this->authRepository->getJsonResponse($response);
    }

    /**
     * Checks Auth
     *
     * @return Illuminate\Http\Response
     */
    public function checkAuth(): JsonResponse
    {
        $response = $this->authRepository->checkAuth();
        return $this->authRepository->getJsonResponse($response);
    }
}
