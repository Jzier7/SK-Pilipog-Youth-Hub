<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\Ability;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class AbilityRepository extends JsonResponseFormat
{
    /**
     * Ability store
     *
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        DB::beginTransaction();
        try {
            $role_id = $data['role_id'];
            $abilities = $data['abilities'];


            foreach ($abilities as $ability) {
                Ability::create([
                    'role_id' => $role_id,
                    'route_id' => $ability
                ]);
            }

            $role = Role::with(['abilities.route'])->find($role_id);

            DB::commit();
            return [
                'message' => 'Abilities added successfully.',
                'body' => $role
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
     * Ability update
     *
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        DB::beginTransaction();
        try {
            $role_id = $data['role_id'];
            $abilities_query = Ability::where('role_id', $role_id)->get();
            $abilities = $data['abilities'];

            $data_diff = count(array_diff($abilities_query->pluck('route_id')->toArray(), $abilities)) > 0 ?
                array_diff($abilities_query->pluck('route_id')->toArray(), $abilities) :
                array_diff($abilities, $abilities_query->pluck('route_id')->toArray());

            foreach ($data_diff as $data) {
                $ability = Ability::where('role_id', $role_id)->where('route_id', $data);

                if (!$ability->exists()) {
                    Ability::create([
                        'role_id' => $role_id,
                        'route_id' => $data
                    ]);
                    continue;
                }

                $ability->delete();
            }

            $role = Role::with(['abilities.route'])->find($role_id);

            DB::commit();
            return [
                'message' => 'Abilities updated successfully.',
                'body' => $role
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500
            ];
        }
    }
}
