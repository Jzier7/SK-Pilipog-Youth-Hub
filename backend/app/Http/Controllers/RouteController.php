<?php

namespace App\Http\Controllers;

use App\Http\Requests\Route\Index;
use App\Repositories\RouteRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class RouteController extends Controller
{
    protected RouteRepository $routeRepository;

    public function __construct()
    {
        $this->routeRepository = new RouteRepository();
    }

    /**
     * Handles route index
     *
     * @param App\Http\Requests\Route\Index $request
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index(Index $request): JsonResponse
    {
        $data = $request->validated();
        $response = $this->routeRepository->index($data);
        return $this->routeRepository->getJsonResponse($response);
    }
}
