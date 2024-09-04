<?php

namespace App\Http\Middleware;

use App\Classes\JsonResponseFormat;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware extends JsonResponseFormat
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $url = $request->url();
        $parsed_url = parse_url($url);
        $path = ltrim($parsed_url['path'], '/');

        $user = Auth::user();

        $abilities = DB::table('abilities')
            ->leftJoin('routes', 'routes.id', '=', 'abilities.route_id')
            ->where('role_id', $user->role_id)
            ->where('routes.uri', $path)
            ->pluck('routes.uri')->first();

        if (!$abilities)
            return $this->getJsonResponse([
                'message' => 'Unauthorized',
                'status' => 401
            ]);

        return $next($request);
    }
}
