<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\Index;
use App\Repositories\RoleRepository;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{

    protected RoleRepository $roleRepository;

    public function __construct()
    {
        $this->roleRepository = new RoleRepository();
    }


    /**
     * Route index
     *
     * @param App\Http\Requests\Role\Index $request
     * @return array
     */
    public function index(Index $request): JsonResponse
    {
        $data = $request->validated();
        $response = $this->roleRepository->index($data);
        return $this->roleRepository->getJsonResponse($response);
    }
}
