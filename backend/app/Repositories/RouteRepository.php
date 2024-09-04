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
        $route_group = [];

        foreach ($routes as $route) {
            $prefix = explode('/', $route->uri)[1];
            if (isset($route_group[$prefix])) {
                $route_group[$prefix][] = $route;
            } else {
                $route_group[$prefix] = [$route];
            }
        }

        return [
            'message' => 'These are the data.',
            'body' => $route_group
        ];
    }
}
