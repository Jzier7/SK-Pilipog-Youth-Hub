<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class MigrateRoutes extends Command
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
    protected $description = 'Migrate application routes to the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Migrating routes...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('routes')->truncate();

        $routes = Route::getRoutes();

        foreach ($routes as $route) {
            if (strpos($route->uri(), 'api') === 0) {
                DB::table('routes')->insert([
                    'uri' => $route->uri(),
                    'name' => $route->getName(),
                    'action' => $route->getActionName(),
                    'methods' => implode(', ', $route->methods()),
                    'middleware' => implode(', ', $route->middleware()),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        $this->info('Routes migrated successfully!');
    }
}

