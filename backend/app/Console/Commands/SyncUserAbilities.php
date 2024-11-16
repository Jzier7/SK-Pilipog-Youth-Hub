<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncUserAbilities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:abilities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync user abilities based on roles to the routes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Syncing user abilities...');

        DB::table('abilities')->truncate();

        $superAdminRoleId = 1;
        $adminRoleId = 2;
        $userRoleId = 3;
        $guestRoleId = 4;

        $allRouteIds = DB::table('routes')->pluck('id')->toArray();

        $adminRouteIds = array_diff($allRouteIds, [6, 12]);

        $userRouteIds = [
            1,
            2,
            8,
            11,
            19,
            25,
            29,
            38,
            45,
            59,
            60,
            61,
            62,
            63,
            64,
            65,
            66,
            67,
            68,
            69,
            70,
        ];

        $guestRouteIds = [
            1,
            2,
            8,
            11,
            19,
            25,
            29,
            38,
            45,
            62,
            66,
            68
        ];

        // Syncing abilities for Super Admin
        foreach ($allRouteIds as $routeId) {
            DB::table('abilities')->insert([
                'role_id' => $superAdminRoleId,
                'route_id' => $routeId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Syncing abilities for Admin
        foreach ($adminRouteIds as $routeId) {
            DB::table('abilities')->insert([
                'role_id' => $adminRoleId,
                'route_id' => $routeId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Syncing abilities for User
        foreach ($userRouteIds as $routeId) {
            DB::table('abilities')->insert([
                'role_id' => $userRoleId,
                'route_id' => $routeId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Syncing abilities for Guest
        foreach ($guestRouteIds as $routeId) {
            DB::table('abilities')->insert([
                'role_id' => $guestRoleId,
                'route_id' => $routeId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $this->info('User abilities synced successfully!');
    }
}

