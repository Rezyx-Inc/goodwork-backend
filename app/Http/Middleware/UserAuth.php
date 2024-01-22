<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\UserSubscriptionPlan;
use Illuminate\Support\Facades\DB;
use App\Models\ConnectionRequest;

class UserAuth {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'frontend') {
        if (Auth::guard($guard)->guest()) {
            session()->put('intended_url', url()->current());
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('worker/login');
            }
        }
        return $next($request);
    }

}
