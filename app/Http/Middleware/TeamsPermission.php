<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamsPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, \Closure $next)
    {
        if (!empty(auth()->user())) {
            if (!session()->has('team_id')) {
                session(['team_id' => auth()->user()->teams->first()->id]);
            }
            setPermissionsTeamId(session('team_id'));
        }

        return $next($request);
    }
}
