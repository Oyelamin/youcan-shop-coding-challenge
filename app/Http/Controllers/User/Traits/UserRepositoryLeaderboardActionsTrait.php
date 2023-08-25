<?php

namespace App\Http\Controllers\User\Traits;

use App\Services\GitHubApiService;
use Carbon\Carbon;

trait UserRepositoryLeaderboardActionsTrait
{
    // Modular Behavior

    protected readonly string|null $usernameFilter;
    protected readonly string|null $minReviewCountFilter;
    protected readonly string|null $minPRCountFilter;
    protected string|null $startDateFilter;
    protected string|null $endDateFilter;
    protected readonly string $prStateFilter;

    protected GitHubApiService $gitHubApi;
    protected string $repository;

    public function __construct(GitHubApiService $gitHubApi)
    {
        $this->gitHubApi = $gitHubApi;
        $this->startDateFilter = Carbon::now()->subMonth()->startOfMonth();
        $this->endDateFilter = Carbon::now()->subMonth()->endOfMonth();
    }

    /**
     *  Leaderboard actions...
     *
     * @return array|null
     */
    private function executeLeaderboardActions(): array|null{
        $startDate = $this->startDateFilter;
        $endDate = $this->endDateFilter;
        $pullRequests = $this->gitHubApi->getRepositoryPullRequests(
            repository: $this->repository,
            state: $this->prStateFilter
        );
        $leaderboardData = [];

        foreach ($pullRequests as $pr) {
            $createdAt = Carbon::parse($pr['created_at']);
            if ($createdAt->between($startDate, $endDate)) {
                $prNumber = $pr['number'];
                $pullRequestReviews = $this->gitHubApi->getRepositoryPullRequestReviews(
                    repository: $this->repository,
                    prNumber: $prNumber
                );
                $prReviewCount = count($pullRequestReviews);

                $prAuthor = $pr['user']['login'];


                if (!isset($leaderboardData[$prAuthor])) {
                    $leaderboardData[$prAuthor] = [
                        'pr_review_count' => 0,
                        'pr_count' => 0,
                    ];
                }

                $leaderboardData[$prAuthor]['pr_review_count'] += $prReviewCount;
                $leaderboardData[$prAuthor]['pr_count']++;

            }
        }


        // Sort leaderboard data by PR review count, etc...
        return $this->applySort(
            leaderboardData: $leaderboardData
        );
    }

    /**
     * Apply sorting/filters to leaderboard data
     *
     * @param $leaderboardData
     * @return array
     */
    private function applySort($leaderboardData): array {
        $usernameFilter = $this->usernameFilter;
        $minReviewCount = $this->minReviewCountFilter;
        $minPRCount = $this->minPRCountFilter;
        arsort($leaderboardData);

        if ($usernameFilter) {
            $leaderboardData = array_filter($leaderboardData, function ($data, $username) use ($usernameFilter) {
                return str_contains($username, $usernameFilter);
            }, ARRAY_FILTER_USE_BOTH);
        }

        if ($minReviewCount) {
            $leaderboardData = array_filter($leaderboardData, function ($data) use ($minReviewCount) {
                return $data['pr_review_count'] >= $minReviewCount;
            });
        }

        if ($minPRCount) {
            $leaderboardData = array_filter($leaderboardData, function ($data) use ($minPRCount) {
                return $data['pr_count'] >= $minPRCount;
            });
        }

        return array_map(function($value, $key): array {
            return [
                'username' => $key,
                'pr_review_count' => $value['pr_review_count'],
                'pr_count' => $value['pr_count']
            ];
        }, $leaderboardData, array_keys($leaderboardData));

    }

}
