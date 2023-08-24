<?php
/**
 * Created by PhpStorm.
 * User: blessing
 * Date: 23/08/2023
 * Time: 10:06 pm
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\GitHubApiService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

abstract class BaseControllerConfig extends Controller
{

    protected GitHubApiService $gitHubApi;

    public function __construct(GitHubApiService $gitHubApi)
    {
        $this->gitHubApi = $gitHubApi;
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function attemptLogin(Request $request, GitHubApiService|null $gitHubServiceData): string{
        $user = $this->createUserIfNeeded($request, $gitHubServiceData);
        $user = $this->updateUserToken($user);
        return $this->generateJWTToken($user);

    }

    /**
     * Confirm if the token in DB is still active and ready for use...
     *
     * @return User
     */
    private function updateUserToken(User $user): User
    {
        try{
            $requestPassword = request('oAuthToken');
            if($requestPassword == Crypt::decryptString($user->password)){
                return $user;
            }

            $user->password = Crypt::encryptString($requestPassword);
            $user->save();

            return $user;
        }catch (\Exception $e) {
            dd($e->getMessage());
            Log::error($e->getMessage()); // For debugging
            throw new AuthenticationException(
                message: "Something went wrong while processing your request. Kindly try again later"
            );
        }

    }

    /**
     * @return User
     */
    protected function getAuth(): User {
        return JWTAuth::user();
    }

    protected function generateJWTToken(User $user): string {
        return JWTAuth::fromUser($user);
    }

    protected function createUserIfNeeded(Request $request, GitHubApiService $gitHubServiceData): User {
        try{
            $username = $gitHubServiceData->getUsername();

            $user = User::where('username', $username)->first();

            if (!$user) {
                $user = User::create([
                    'username' => $username,
                    'password' => Crypt::encryptString($request->oAuthToken), // safe encrypt with laravel Crypt - This token will be retrieved and used later...
                ]);
            }

            return $user;
        }catch (\Exception $e){
            dd($e->getMessage());
            Log::error($e->getMessage()); // For debugging
            throw new AuthenticationException(
                message: "Something went wrong while processing your request. Kindly try again later"
            );
        }

    }


}
