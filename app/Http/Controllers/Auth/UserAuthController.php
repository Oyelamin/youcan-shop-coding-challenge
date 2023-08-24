<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginApiRequest;
use App\Support\Traits\ResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserAuthController extends BaseControllerConfig
{
    use ResponseTrait;

    public function login(LoginApiRequest $request): JsonResponse {
        try{
            $this->gitHubApi->token = $request->oAuthToken;
            $gitHubServiceData = $this->gitHubApi->getUserProfile();
            if(!$gitHubServiceData || !$accessToken = $this->attemptLogin(request: $request, gitHubServiceData: $gitHubServiceData)){
                throw new AuthenticationException(
                    message: "Unauthorized. Kindly check your credentials."
                );
            }

            $data = [
                'token' => $accessToken
            ];

            return $this->respondWithCustomData(
                data: $data
            );
        }catch(AuthenticationException $e){
            return $this->respondWithError(
                message: $e->getMessage(),
                status_code: Response::HTTP_UNAUTHORIZED
            );
        }catch(\Exception $e){
            return $this->respondWithError(
                status_code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }


    }
}
