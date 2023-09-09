<?php

namespace App\Http\Controllers\User;

use App\Helpers\UserRepositoryLeaderboardHelper;
use App\Http\Controllers\Controller;
use App\Services\GitHubApiService;
use App\Support\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LeaderBoardsController extends Controller
{
    use ResponseTrait;
    protected UserRepositoryLeaderboardHelper $userRepositoryLeaderboardHelper;

    public function __construct(UserRepositoryLeaderboardHelper $userRepositoryLeaderboardHelper)
    {
        $this->userRepositoryLeaderboardHelper = $userRepositoryLeaderboardHelper;
    }

    /**
     * Get leaderboard records for a repository.
     *
     * @param Request $request
     * @param string $repository
     * @return JsonResponse
     */
    public function index(Request $request, string $repository): JsonResponse
    {
        try {
            $filterOptions = $request->toArray();
            $this->userRepositoryLeaderboardHelper->repository = $repository;
            $repositories = $this->userRepositoryLeaderboardHelper
                ->applyFilters($filterOptions)
                ->fetchLeaderboardRecords();
            return $this->respondWithCustomData(
                data: $repositories
            );
        } catch (\Exception $e) {
            return $this->respondWithError(
                status_code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
