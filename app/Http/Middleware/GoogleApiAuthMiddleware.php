<?php

namespace App\Http\Middleware;

use App\Support\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class GoogleApiAuthMiddleware
{
    use ResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!JWTAuth::user()) {
            return $this->respondWithError(
                message: 'Unauthorized. Kindly Login',
                status_code: Response::HTTP_UNAUTHORIZED
            );
        }

        return $next($request);
    }
}
