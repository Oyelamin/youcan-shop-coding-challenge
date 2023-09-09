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

class GitHubApiService
{
    protected readonly string $baseUrl;

    public string|null $token;

    protected User|null $user;

    public readonly array|null $response;

    public int $page = 1;
    public int $perPage = 20;

    public function __construct() {
        $this->baseUrl = config('services.google_api_base_url');
    }

    public function getUserProfile(): GitHubApiService|null
    {
        $url = "{$this->baseUrl}/user";
        $response = getRequest(url: $url, token: $this->token);
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
    public function getUserRepositories(): array|null
    {
        $user = getCurrentUser();
        $url = "{$this->baseUrl}/users/{$user->username}/repos";
        $response = getRequest(url: $url, param: [
            'page' => $this->page,
            'per_page' => $this->perPage
        ]);
        if ($response->successful()) {
            return $response->json();
        }
        return null;
    }

    /**
     * Get a single repository detail
     * @param $name
     * @return array|null
     */
    public function getUserRepository(string $name): array|null
    {
        $user = getCurrentUser();
        $url = "{$this->baseUrl}/repos/{$user->username}/{$name}";
        $response = getRequest(url: $url);
        if ($response->successful()) {
            return $response->json();
        }
        return null;
    }

    public function getRepositoryPullRequests(string $repository, string $state) {
        $this->requestURL = "{$this->baseUrl}/repos/{$this->user->username}/{$repository}/pulls";
        $response = $this->get([
            'state' => $state
        ]);

        if ($response->successful()) {
            return $response->json();
        }
        return null;
    }

    public function getRepositoryPullRequestReviews(string $repository, int $prNumber) {
        $this->requestURL = "{$this->baseUrl}/repos/{$this->user->username}/{$repository}/pulls/{$prNumber}/reviews";
        $response = $this->get();

        if ($response->successful()) {
            return $response->json();
        }
        return null;
    }

}
