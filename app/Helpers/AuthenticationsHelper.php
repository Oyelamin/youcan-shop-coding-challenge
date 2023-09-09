<?php
/**
 * Created by PhpStorm.
 * User: blessing
 * Date: 09/09/2023
 * Time: 12:01 am
 */

namespace App\Helpers;

use App\Models\User;
use App\Services\GitHubApiService;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthenticationsHelper
{
    protected GitHubApiService $gitHubApi;

    public function __construct(GitHubApiService $gitHubApi)
    {
        $this->gitHubApi = $gitHubApi;
    }

    /**
     * Attempt to log in and return a JWT token.
     *
     * @param Request $request
     * @param GitHubApiService|null $gitHubServiceData
     * @return string
     */
    public function attemptLogin(Request $request, ?GitHubApiService $gitHubServiceData): string
    {
        session(['oAuthToken' => $request->oAuthToken]);
        $user = $this->createUserIfNeeded($gitHubServiceData);
        return $this->generateJWTToken($user);
    }

    private function generateJWTToken(User $user): string
    {
        return JWTAuth::fromUser($user);
    }

    private function createUserIfNeeded(GitHubApiService $gitHubServiceData): User
    {
        $username = $gitHubServiceData->getUsername();
        $user = User::where('username', $username)->first();
        if (!$user) {
            $user = User::create([
                'username' => $username
            ]);
        }
        return $user;
    }

    public function verifyToken($token): GitHubApiService|null {
        $this->gitHubApi->token = $token;
        return $this->gitHubApi->getUserProfile();
    }
}
