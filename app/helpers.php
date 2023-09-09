<?php
/**
 * Created by PhpStorm.
 * User: blessing
 * Date: 08/09/2023
 * Time: 11:44 pm
 */

use Illuminate\Support\Facades\Http;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

/**
 * @param string $url
 * @param array $param
 * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
 */
function getRequest(string $url, array $param = [], ?string $token = null): \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response {
    $token = getTokenFromSession() ?? $token;
    $header = [
        'Accept' => 'application/vnd.github.v3+json',
        'Authorization' => "Bearer {$token}",
    ];
    return Http::withHeaders($header)->get($url, $param);
}

function getTokenFromSession(): ?string {
    $token = request()->bearerToken();
    return $token ? auth()->payload()->get(config('jwt.githubToken')) : null;
}

function getCurrentUser(): mixed {
    return JWTAuth::user();
}
