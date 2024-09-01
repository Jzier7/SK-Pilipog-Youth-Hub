<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthRepository extends JsonResponseFormat
{
    /**
     * @param array $data
     * @return array
     */
    public function login($data): array
    {
        if (!Auth::attempt($data)) {
            return [
                'message' => 'Invalid credentials',
                'status' => 401
            ];
        }

        $user = User::find(Auth::id());

        return [
            'message' => 'Login successful',
            'body' => $user
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    public function logout($data): array
    {
        Auth::guard('web')->logout();
        return [
            'message' => 'Logout successful'
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    public function register($data): array
    {
        try {
            $user = User::create($data);
            Auth::login($user);

            return [
                'message' => 'User created successfully',
                'body' => $user
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'status' => 500
            ];
        }
    }
}
