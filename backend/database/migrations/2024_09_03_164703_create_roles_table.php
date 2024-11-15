<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });

        DB::table('roles')->insert([
            ['name' => 'Super Admin', 'slug' => 'super-admin'],
            ['name' => 'Admin', 'slug' => 'admin'],
            ['name' => 'User', 'slug' => 'user'],
            ['name' => 'guest', 'slug' => 'guest'],
        ]);


        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->default(3);
            $table->foreign('role_id')->references('id')->on('roles');
        });

        DB::table('users')->insert([
            'first_name' => 'Admin',
            'middle_name' => null,
            'last_name' => 'User',
            'birthdate' => '2002-01-01',
            'gender' => 'Male',
            'email' => 'superadmin@admin.com',
            'username' => 'admin.user',
            'password' => Hash::make('password'),
            'role_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('users')->insert([
            'first_name' => 'Guest',
            'middle_name' => null,
            'last_name' => 'User',
            'birthdate' => '2002-01-01',
            'gender' => 'Male',
            'email' => 'guest@guest.com',
            'username' => 'Guest',
            'password' => Hash::make('2M[4oD5BAaP4'),
            'role_id' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('uri');
            $table->string('name')->nullable();
            $table->string('action');
            $table->string('methods');
            $table->string('middleware')->nullable();
            $table->timestamps();
        });

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

        Schema::create('abilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('route_id');

            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('route_id')->references('id')->on('routes');
            $table->timestamps();
        });

        $routes = DB::table('routes')->get();

        foreach ($routes as $route) {
            DB::table('abilities')->insert([
                'role_id' => 1,
                'route_id' => $route->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abilities');

        Schema::dropIfExists('routes');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });

        Schema::dropIfExists('roles');
    }
};
