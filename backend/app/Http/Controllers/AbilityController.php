<?php

namespace App\Http\Controllers;

use App\Repositories\AbilityRepository;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Ability\Store;
use App\Http\Requests\Ability\Update;

class AbilityController extends Controller
{
    protected AbilityRepository $abilityRepository;

    public function __construct()
    {
        $this->abilityRepository = new AbilityRepository();
    }

    /**
     * Ability store
     *
     * @param App\Http\Requests\Ability\Store $request
     * @return Illuminate\Http\JsonResponse
     */
    public function store(Store $request): JsonResponse
    {
        $data = $request->validated();
        $response = $this->abilityRepository->store($data);
        return $this->abilityRepository->getJsonResponse($response);
    }

    /**
     * Ability update
     *
     * @param App\Http\Requests\Ability\Store $request
     * @return Illuminate\Http\JsonResponse
     */
    public function update(Update $request): JsonResponse
    {
        $data = $request->validated();
        $response = $this->abilityRepository->update($data);
        return $this->abilityRepository->getJsonResponse($response);
    }
}
