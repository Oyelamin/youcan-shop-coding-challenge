<?php
/**
 * Created by PhpStorm.
 * User: blessing
 * Date: 23/08/2023
 * Time: 8:19 pm
 */

namespace App\Services;

use App\Models\User;
use App\Support\Abstracts\HttpRequestConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class GitHubApiService extends HttpRequestConfig
{
    protected readonly string $baseUrl;

    public string $token;

    protected readonly User $user;

    public readonly array $userDetailResult;

    public function __construct() {
        $this->baseUrl = config('services.google_api_base_url');
    }

    protected function setRequestHeader(): array {
        return [
            'Accept' => 'application/vnd.github.v3+json',
            'Authorization' => "Bearer {$this->token}",
        ];

    }


    /**
     *  Get the authenticated user
     *
     * @return array|mixed|null
     */
    public function getUserProfile(): GitHubApiService|null
    {
        $this->requestURL = "{$this->baseUrl}/user";
        $response = $this->get();

        if ($response->successful()) {
            $this->userDetailResult = $response->json();

            return $this;
        }
        return null;
    }

    public function getUsername(): string {
        return $this->userDetailResult['login'];
    }

}
