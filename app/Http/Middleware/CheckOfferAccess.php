<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckOfferAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (
            isset(request()->route()->worker->id)
            && request()->route()->worker->id === Auth::user()->worker->id
        ) {
            return $next($request);            
        } else {
            abort(404);
            exit;
        }
    }
}
