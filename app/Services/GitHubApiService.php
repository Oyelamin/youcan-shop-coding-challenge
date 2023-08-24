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
use Illuminate\Support\Facades\Crypt;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Collection;

class GitHubApiService extends HttpRequestConfig
{
    protected readonly string $baseUrl;

    public string $token;

    protected readonly User $user;

    public readonly array $response;

    public int $page = 1;
    public int $perPage = 20;


    public function __construct() {
        $this->baseUrl = config('services.google_api_base_url');
    }

    /**
     * Set Github request header
     *
     * @return string[]
     */
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
            $this->response = $response->json();

            return $this;
        }
        return null;
    }

    /**
     * Get username from github response
     *
     * @return string
     */
    public function getUsername(): string {
        return $this->response['login'];
    }


    /**
     * Get authenticated user repositories
     *
     * @return Collection|null
     */
    public function getUserRepositories(): Collection|null
    {
        $user = $this->getAuth();
        $this->token = Crypt::decryptString($user->password);
        $this->requestURL = "{$this->baseUrl}/users/{$user->username}/repos";
        $response = $this->get([
            'page' => $this->page,
            'per_page' => $this->perPage
        ]);
        if ($response->successful()) {
            return collect($response->json());
        }
        return null;
    }

    /**
     * Get authenticated user detail
     *
     * @return User
     */
    public function getAuth(): User {
        return JWTAuth::user();
    }

}
