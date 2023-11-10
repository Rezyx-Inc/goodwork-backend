<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RecruiterNotAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'recruiter')
    {
        if (!Auth::guard($guard)->guest()) {
            return redirect()->route('recruiter-dashboard');
        }
        return $next($request);
    }
}
