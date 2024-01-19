<?php

namespace App\Http\Middleware;

use Closure;


class controllHeaders
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

        $response = $next($request);
        header_remove('x-powered-by');
        $response->header('Accept','application/json');

        return $response;
    }
}
