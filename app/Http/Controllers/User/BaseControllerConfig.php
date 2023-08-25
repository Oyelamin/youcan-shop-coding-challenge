<?php
/**
 * Created by PhpStorm.
 * User: blessing
 * Date: 25/08/2023
 * Time: 11:30 am
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\Traits\UserRepositoryLeaderboardActionsTrait;
use App\Services\GitHubApiService;
use App\Support\Interfaces\BaseControllerConfigInterface;
use App\Support\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Support\Collection;

abstract class BaseControllerConfig extends Controller implements BaseControllerConfigInterface
{
    use ResponseTrait, UserRepositoryLeaderboardActionsTrait;

    /**
     *  Filter record...
     *
     * @param array $filterOptions
     * @return Collection|null|array
     */
    public function findByFilters(array $filterOptions): Collection|null|array
    {
        $this->prStateFilter = $filterOptions['pr_state'] ?? 'open';
        $this->usernameFilter = $filterOptions['username'] ?? null;
        $this->minReviewCountFilter = $filterOptions['min_review_count'] ?? 0;
        $this->minPRCountFilter = $filterOptions['min_pr_count'] ?? 0;
        $startDateFilter = $filterOptions['start_date'] ?? null;
        $endDateFilter = $filterOptions['end_date'] ?? null;
        if($startDateFilter){
            $this->startDateFilter = Carbon::parse($startDateFilter);
        }
        if($endDateFilter){
            $this->endDateFilter = Carbon::parse($endDateFilter);
        }
        return $this->executeLeaderboardActions();

    }
}
