<?php
/**
 * Created by PhpStorm.
 * User: blessing
 * Date: 09/09/2023
 * Time: 4:08 am
 */

namespace App\Helpers;

use App\Services\GitHubApiService;
use Carbon\Carbon;

class UserRepositoryLeaderboardHelper
{
    protected GitHubApiService $gitHubApi;
    public ?string $usernameFilter;
    public ?string $minReviewCountFilter;
    public ?string $minPRCountFilter;
    public ?string $startDateFilter;
    public ?string $endDateFilter;
    public string $prStateFilter;
    public string $repository;

    public function __construct(GitHubApiService $gitHubApi)
    {
        $this->gitHubApi = $gitHubApi;
    }

    /**
     * Apply filters to the leaderboard.
     *
     * @param array $filterOptions
     * @return UserRepositoryLeaderboardHelper
     */
    public function applyFilters(array $filterOptions = []): UserRepositoryLeaderboardHelper {
        $startDateFilter = $filterOptions['start_date'] ?? null;
        $endDateFilter = $filterOptions['end_date'] ?? null;
        $this->prStateFilter = $filterOptions['pr_state'] ?? 'open';
        $this->usernameFilter = $filterOptions['username'] ?? null;
        $this->minReviewCountFilter = $filterOptions['min_review_count'] ?? 0;
        $this->minPRCountFilter = $filterOptions['min_pr_count'] ?? 0;
        $this->startDateFilter = $startDateFilter ? Carbon::parse($startDateFilter) : Carbon::now()->subMonth()->startOfMonth();
        $this->endDateFilter = $endDateFilter ? Carbon::parse($endDateFilter) : Carbon::now()->subMonth()->endOfMonth();
        return $this;
    }

    /**
     * Fetch leaderboard records.
     *
     * @return array|null
     */
    public function fetchLeaderboardRecords(): ?array {
        $startDate = $this->startDateFilter;
        $endDate = $this->endDateFilter;
        $pullRequests = $this->gitHubApi->getRepositoryPullRequests(
            repository: $this->repository,
            state: $this->prStateFilter
        );
        $leaderboardData = [];

        if(!$pullRequests) return [];
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
     * Apply sorting/filters to leaderboard data.
     *
     * @param array $leaderboardData
     * @return array
     */
    private function applySort(array $leaderboardData): array {
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
