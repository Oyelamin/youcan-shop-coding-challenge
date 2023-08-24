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
use App\Support\Traits\ResponseTrait;

abstract class BaseControllerConfig extends Controller
{
    use ResponseTrait;
    protected GitHubApiService $gitHubApi;

    public function __construct(GitHubApiService $gitHubApi)
    {
        $this->gitHubApi = $gitHubApi;
        $this->resourceItem = UserRepositoryResource::class;

    }






}
