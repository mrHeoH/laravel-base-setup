<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PrettyJson
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $is_prod = getenv('APP_ENV');

        if ($response instanceof JsonResponse) {
            $response->setEncodingOptions(
                $is_prod === "production"
                    ? JSON_UNESCAPED_UNICODE
                    : JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            );
        }

        return $response;
    }
}
