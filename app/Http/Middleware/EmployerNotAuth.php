<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

class EmployerNotAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'employer')
    {
        if (!Auth::guard($guard)->guest()) {
            return redirect()->route('employer-dashboard');
        }
        return $next($request);
    }
}
