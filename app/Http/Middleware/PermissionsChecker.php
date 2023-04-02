<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PermissionsChecker
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param mixed ...$permission
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next, ... $permission)
    {
        if($request->expectsJson()){
            // check if user is logged in
            if($request->user()){
                // check if user is admin
                if($request->user()->isSuperAdmin()){
                    return $next($request);
                }
                // check if user has permission
                foreach($permission as $p){
                    if($request->user()->hasPermission($p)){
                        return $next($request);
                    }
                }
            }
            return response()->json([
                'message' => 'You do not have permission to access this resource'
            ], 403);
        }
        return $next($request);
    }
}
