<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    public function handle(Request $request, \Closure $next): ?string
    {
        if (!Auth::check()) {
            return $this->respondWithError(message: 'Unauthorized. Kindly Login', status_code: Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
