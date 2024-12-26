<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class SanitizeInputs
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
        $sanitized = array_map(function ($value) {

            if (is_string($value)) {
            
                if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                
                    $value = filter_var($value, FILTER_SANITIZE_EMAIL);
                    
                } else {
                    
                    $value = strip_tags($value);
                    
                }
                
            }
            
            return $value;
            
        }, $request->all());

        // Log::info('Sanitized Input: ', $sanitized);

        $request->merge($sanitized);

        return $next($request);
    }
}
