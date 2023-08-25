<?php
/**
 * Created by PhpStorm.
 * User: blessing
 * Date: 24/08/2023
 * Time: 2:40 pm
 */

namespace App\Http\Controllers\Repository;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserRepositoryResource;
use App\Services\GitHubApiService;
use App\Support\Interfaces\BaseControllerConfigInterface;
use App\Support\Traits\ResponseTrait;
use Illuminate\Support\Collection;

abstract class BaseControllerConfig extends Controller implements BaseControllerConfigInterface
{
    use ResponseTrait;
    protected GitHubApiService $gitHubApi;

    public function __construct(GitHubApiService $gitHubApi)
    {
        $this->gitHubApi = $gitHubApi;
        $this->resourceItem = UserRepositoryResource::class;

    }

    public function findByFilters(array $filterOptions): Collection|null|array
    {
        $perPage = $filterOptions['limit'] ?? 20;
        $page = $filterOptions['page'] ?? 1;
        $this->gitHubApi->page = $page;
        $this->gitHubApi->perPage = $perPage;
        return $this->gitHubApi->getUserRepositories();
    }

}
