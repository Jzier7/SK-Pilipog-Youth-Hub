<?php

namespace App\Repositories;

use App\Models\Role;
use App\Classes\JsonResponseFormat;

class RoleRepository extends JsonResponseFormat
{
    /**
     * Route index
     *
     * @param array $data
     * @return array
     */
    public function index(array $data): array
    {
        $roles = Role::with(['abilities.route'])->get();
        return [
            'message' => 'These are the data.',
            'body' => $roles
        ];
    }
}
