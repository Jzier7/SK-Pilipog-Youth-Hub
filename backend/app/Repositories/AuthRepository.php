<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\File;
use App\Models\User;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthRepository extends JsonResponseFormat
{

    /**
     * @param array $data
     * @return array
     */
    public function login(array $data, bool $rememberMe): array
    {
        if (!Auth::attempt($data, $rememberMe)) {
            return [
                'message' => 'Invalid credentials',
                'status' => 401
            ];
        }

        $user = User::with(['files', 'role.abilities.route'])->find(Auth::id());

        if ($user->role_id === 3 && !$user->active_voter) {
            Auth::guard('web')->logout();

            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return [
                'message' => 'Account is inactive. Please contact support.',
                'status' => 401
            ];
        }

        return [
            'message' => 'Login successful',
            'body' => $user
        ];
    }

    /**
     * @return array
     */
    public function loginAsGuest(): array
    {
        $guestCredentials = [
            'email' => 'guest@guest.com',
            'password' => '2M[4oD5BAaP4'
        ];

        if (!Auth::attempt($guestCredentials)) {
            return [
                'message' => 'Guest login failed',
                'status' => 401
            ];
        }

        $user = User::with(['files', 'role.abilities.route'])->find(Auth::id());

        return [
            'message' => 'Login as Guest',
            'body' => $user,
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    public function logout(): array
    {
        Auth::guard('web')->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

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
        DB::beginTransaction();
        try {
            $files = $data['files'];
            unset($data['files']);
            $data['role_id'] = 3;

            $data['purok_id'] = $data['purok'];
            unset($data['purok']);

            $user = User::create($data);

            if ($files) {

                $fileUploadService = new FileUploadService();

                foreach ($files as $file) {
                    $file_size = $fileUploadService->getFileSize($file);
                    $file_path = $fileUploadService->upload($file, 'users');
                    $file_name = $file->getClientOriginalName();

                    $new_file = new File();
                    $new_file->path = $file_path;
                    $new_file->size = $file_size;
                    $new_file->name = $file_name;

                    $user->files()->save($new_file);
                }
            }

            Auth::login($user->load(['files', 'role.abilities.route']));

            DB::commit();
            return [
                'message' => 'User created successfully',
                'body' => $user
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500
            ];
        }
    }

    /**
     * Checks Auth
     *
     * @return array
     */
    public function checkAuth(): array
    {
        $user = null;
        $isAuthenticated = Auth::check();
        $viaRemember = Auth::viaRemember();

        if ($isAuthenticated && $viaRemember) {
            $user = User::with(['files', 'role.abilities.route'])->find(Auth::id());
        }

        return [
            'body' => [
                'isAuthenticated' => $isAuthenticated,
                'viaRemember' => $viaRemember,
                'user' => $user
            ]
        ];
    }
}
