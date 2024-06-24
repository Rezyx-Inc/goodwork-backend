<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminNotAuth {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

	public function handle($request, Closure $next, $guard = 'admin') {

        if (!Auth::guard($guard)->guest()) {
            return redirect()->route('admin-dashboard');
        }
        return $next($request);
    }

}
