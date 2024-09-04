<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class SyncRoutesTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync routes table';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $this->info('Syncing routes...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('abilities')->truncate();
        DB::table('routes')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $routes = Route::getRoutes();
        $newRoutes = [];

        foreach ($routes as $route) {
            if (strpos($route->uri(), 'api') === 0) {
                $routeId = DB::table('routes')->insertGetId([
                    'uri' => $route->uri(),
                    'name' => $route->getName(),
                    'action' => $route->getActionName(),
                    'methods' => implode(', ', $route->methods()),
                    'middleware' => implode(', ', $route->middleware()),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $newRoutes[] = $routeId;
            }
        }

        foreach ($newRoutes as $routeId) {
            DB::table('abilities')->insert([
                'role_id' => 1,
                'route_id' => $routeId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $this->info('Routes synced successfully!');
    }
}
