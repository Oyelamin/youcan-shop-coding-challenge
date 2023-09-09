<?php
/**
 * Created by PhpStorm.
 * User: blessing
 * Date: 23/08/2023
 * Time: 8:19 pm
 */

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

class GitHubApiService
{
    public ?string $token;
    protected User|null $user;
    public ?array $response = null;
    public int $page = 1;
    public int $perPage = 20;
    protected string $baseUrl;

    public function __construct() {
        $this->baseUrl = config('services.google_api_base_url');
    }

    /**
     * Get user profile detail
     *
     * @return ?$this
     */
    public function getUserProfile(): ?GitHubApiService
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
     * @return ?string
     */
    public function getUsername(): ?string {
        return $this->response['login'];
    }

    /**
     * Get authenticated user repositories
     *
     * @return ?array
     */
    public function getUserRepositories(): ?array
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
     *
     * @param string $name
     * @return ?array
     */
    public function getUserRepository(string $name): ?array
    {
        $user = getCurrentUser();
        $url = "{$this->baseUrl}/repos/{$user->username}/{$name}";
        $response = getRequest(url: $url);
        if ($response->successful()) {
            return $response->json();
        }
        return null;
    }

    /**
     * Get user repository pull requests
     *
     * @param string $repository
     * @param string $state
     * @return ?array
     */
    public function getRepositoryPullRequests(string $repository, string $state): ?array {
        $user = getCurrentUser();
        $url = "{$this->baseUrl}/repos/{$user->username}/{$repository}/pulls";
        $response = getRequest(url: $url, param: [
            'state' => $state
        ]);
        if ($response->successful()) {
            return $response->json();
        }
        return null;
    }

    /**
     * Get user repository pull request reviews
     *
     * @param string $repository
     * @param int $prNumber
     * @return ?array
     */
    public function getRepositoryPullRequestReviews(string $repository, int $prNumber): ?array {
        $user = getCurrentUser();
        $url = "{$this->baseUrl}/repos/{$user->username}/{$repository}/pulls/{$prNumber}/reviews";
        $response = getRequest(url: $url);
        if ($response->successful()) {
            return $response->json();
        }
        return null;
    }
}
