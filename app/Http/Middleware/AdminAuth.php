<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuth {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

	public function handle($request, Closure $next, $guard = 'admin') {

        if (Auth::guard($guard)->guest()) {
            session()->put('intended_url', url()->current());
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('admin/admin-login');
            }
        }

        return $next($request);
    }


}
