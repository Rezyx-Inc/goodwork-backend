<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

class OrganizationNotAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'organization')
    {
        if (!Auth::guard($guard)->guest()) {
            return redirect()->route('organization-dashboard');
        }
        return $next($request);
    }
}
