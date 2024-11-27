<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Purok;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRepository extends JsonResponseFormat
{
    /**
     * Get the currently authenticated user with related data.
     *
     * @return array
     */
    public function me(): array
    {
        $user = User::with(['files', 'role.abilities.route'])->find(Auth::id());

        if ($user) {
            return [
                'message' => 'User retrieved successfully',
                'body' => $user
            ];
        }

        return [
            'message' => 'User not found',
            'body' => null
        ];
    }

    /**
     * Get paginated users
     *
     * @param array $params
     * @return array
     */
    public function retrieveUsers(array $params): array
    {
        $query = User::query();

        if (!empty($params['isAdmin'])) {
            $query->where('role_id', 2);
        } else {
            $query->whereNotIn('role_id', [1, 2, 4]);
        }

        if (!empty($params['search'])) {
            $searchTerm = '%' . $params['search'] . '%';
            $query->where(function ($subQuery) use ($searchTerm) {
                $subQuery->where('first_name', 'like', $searchTerm)
                    ->orWhere('last_name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm)
                    ->orWhere('username', 'like', $searchTerm);
            });
        }

        if (!empty($params['activeVoter'])) {
            $query->where('active_voter', 1);
        } else {
            $query->where('active_voter', 0);
        }

        if (!empty($params['orderBy'])) {
            $query->orderBy('created_at', $params['orderBy']);
        }

        $query->with(['files', 'purok']);

        $currentPage = $params['currentPage'] ?? 1;
        $pageSize = $params['pageSize'] ?? 10;

        $users = $query->paginate($pageSize, ['*'], 'page', $currentPage);

        $retrievedCount = count($users->items());

        return [
            'message' => "{$retrievedCount} users retrieved successfully",
            'body' => $users->items(),
            'current_page' => $users->currentPage(),
            'from' => $users->firstItem(),
            'to' => $users->lastItem(),
            'last_page' => $users->lastPage(),
            'skip' => ($currentPage - 1) * $pageSize,
            'take' => $pageSize,
            'total' => $users->total(),
        ];
    }

    /**
     * Get all players
     *
     * @return array
     */
    public function retrievePlayers(): array
    {
        $players = User::select('id', 'first_name', 'last_name')
            ->whereNotIn('role_id', [1, 2, 4])
            ->where('active_voter', 1)
            ->get()
            ->map(function ($player) {
                return [
                    'id' => $player->id,
                    'name' => "{$player->first_name} {$player->last_name}",
                ];
            });

        return [
            'message' => 'All users retrieved successfully',
            'body' => $players,
            'total' => $players->count(),
        ];
    }

    /**
     * Add a user.
     *
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        DB::beginTransaction();
        try {
            $password = Str::random(10);

            $user = User::create([
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'role_id' => 2,
                'birthdate' => $data['birthdate'],
                'gender' => $data['gender'],
                'purok_id' => $data['purok'],
                'username' => $data['username'],
                'password' => Hash::make($password),
            ]);

            DB::commit();
            return [
                'message' => 'User added successfully',
                'body' => [
                    'user' => $user,
                    'password' => $password,
                ],
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Update a user.
     *
     * @param array $data
     * @return array
     */
    public function updateData(array $data): array
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($data['id']);

            $user->update([
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'],
                'last_name' => $data['last_name'],
                'birthdate' => $data['birthdate'],
                'gender' => $data['gender'],
                'purok_id' => $data['purok'],
                'username' => $data['username'],
            ]);

            DB::commit();
            return [
                'message' => 'User updated successfully',
                'body' => $user,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Delete a user.
     *
     * @param array $data
     * @return array
     */
    public function delete(array $data): array
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($data['id']);

            $user->delete();

            DB::commit();
            return [
                'message' => 'User deleted successfully',
                'body' => $user,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Update user status.
     *
     * @param array $data
     * @return array
     */
    public function updateStatus(array $data): array
    {

        DB::beginTransaction();
        try {
            $user = User::findOrFail($data['id']);

            if ($data['status'] === 'approved') {
                $user->update([
                    'active_voter' => 1,
                ]);

                DB::commit();
                return [
                    'message' => 'User approved',
                    'body' => $user,
                ];
            } else {
                $user->delete();

                DB::commit();
                return [
                    'message' => 'User rejected',
                    'body' => $user,
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Update user participation counts.
     *
     * @param array $data
     * @return array
     */
    public function updateParticipationCount(array $data): array
    {
        DB::beginTransaction();
        try {
            $responses = [];

            foreach ($data as $item) {
                $user = User::findOrFail($item['id']);

                $user->update([
                    'participation_count' => $item['count'],
                ]);

                $responses[] = [
                    'user_id' => $user->id,
                    'new_count' => $user->participation_count,
                ];
            }

            DB::commit();
            return [
                'message' => 'Users updated successfully',
                'body' => $responses,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Get active voters count.
     *
     * @return array
     */
    public function getVotersCount(): array
    {
        $voters = User::where('active_voter', true)->get();
        $votersCount = $voters->count();

        return [
            'message' => 'Active voters count retrieved successfully',
            'body' => $votersCount
        ];
    }

    /**
     * Get users count per purok.
     *
     * @return array
     */
    public function getCountPerPurok(): array
    {
        $votersPerPurok = Purok::select('puroks.id as purok_id', 'puroks.name')
            ->leftJoin('users', 'puroks.id', '=', 'users.purok_id')
            ->groupBy('puroks.id', 'puroks.name')
            ->selectRaw('count(users.id) as count')
            ->get();

        return [
            'message' => 'Users count per purok retrieved successfully',
            'body' => $votersPerPurok
        ];
    }
}
