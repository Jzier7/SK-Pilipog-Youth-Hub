<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\Route;

class RouteRepository extends JsonResponseFormat
{
    /**
     * Handles route index
     *
     * @param array $data
     * @return array
     */
    public function index(array $data): array
    {
        $routes = Route::all();
        return [
            'message' => 'These are the data.',
            'body' => $routes
        ];
    }
}
