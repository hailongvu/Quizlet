<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserInClass
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $params)
    {
        if($request->expectsJson()) {
            if($request->user()){

                switch($params) {
                    case 'all':
                        if($request->user()->isInClass($request->route('id'))){
                            return $next($request);
                        } else {
                            return response()->json(['message' => 'You are not in this class.js'], 403);
                        }
                        break;
                    case 'student':
                        if($request->user()->isStudentInClass($request->route('id'))){
                            return $next($request);
                        } else {
                            return response()->json(['message' => 'You are not student in this class.js'], 403);
                        }
                        break;
                    case 'teacher':
                        if($request->user()->isTeacherInClass($request->route('id'))){
                            return $next($request);
                        } else {
                            return response()->json(['message' => 'You are not teacher in this class.js'], 403);
                        }
                        break;
                    default:
                        return response()->json(['message' => 'You are not logged in'], 403);
                        break;
                }
            } else {
                return response()->json(['message' => 'You are not logged in'], 403);
            }
        }

        return abort (500, 'This route is not available in HTML');
    }
}
