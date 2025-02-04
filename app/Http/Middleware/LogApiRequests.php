<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogApiRequests
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
        try {
            // API to log
            $keywords = ['match-worker-job', 'update-worker-profile'];

            $routeName = $request->route() ? $request->route()->getName() : $request->fullUrl();

            // Check if any of the keywords are in the URL
            $shouldLog = false;
            foreach ($keywords as $keyword) {
                if (strpos($routeName, $keyword) !== false) {
                    $shouldLog = true;
                    break;
                }
            }

            // // filter out the routes that should not be logged
            // if (!$shouldLog) {
            //     return $next($request);
            // }


            // Max length for the logged response content
            $maxLength = 250;


            // separator and space before the log
            Log::info('');
            Log::info('');
            Log::info('');
            Log::info('*******************API LOG*******************');


            // Log the incoming request
            Log::info('=======> API Request:', [
                // 'method' => $request->method(),
                'route' => $routeName,
                // 'url' => $request->fullUrl(),
                // 'headers' => $request->headers->all(),
                'body' => $request->all(),
            ]);


            // Get the response
            $response = $next($request);

            // Truncate the response body if it's too large
            $responseContent = $response->getContent();

            $truncatedContent = strlen($responseContent) > $maxLength
                ? substr($responseContent, 0, $maxLength) . '... [truncated]'
                : $responseContent;

            Log::info('');
            // Log the response
            Log::info('=======> API Response:', [
                'status' => $response->getStatusCode(),
                'body' => $truncatedContent,
            ]);

            return $response;
        } catch (\Exception $e) {
            return $next($request);
        }
    }
}
