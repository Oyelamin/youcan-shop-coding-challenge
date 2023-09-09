<?php

namespace App\Http\Controllers\Repository;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserRepositoryResource;
use App\Services\GitHubApiService;
use App\Support\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class RepositoriesController extends Controller
{
    use ResponseTrait;
    protected GitHubApiService $gitHubApi;

    public function __construct(GitHubApiService $gitHubApi)
    {
        $this->gitHubApi = $gitHubApi;
        $this->resourceItem = UserRepositoryResource::class;
    }

    /**
     * Get a list of user repositories.
     *
     * @param Request $request
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function index(Request $request): JsonResponse|AnonymousResourceCollection {
        try {
            $filterRequest = $request->toArray();
            $this->gitHubApi->page = $filterRequest['page'] ?? 1;
            $this->gitHubApi->perPage = $filterRequest['limit'] ?? 20;
            $repositories = $this->gitHubApi->getUserRepositories();
            return $this->respondWithCollection(
                collection: $repositories
            );
        }catch(\Exception $e) {
            return $this->respondWithError(
                status_code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Show details of a user repository.
     *
     * @param Request $request
     * @param string $repository
     * @return JsonResponse|mixed
     */
    public function show(Request $request, string $repository): mixed {
        try{
            $repository = $this->gitHubApi->getUserRepository(name: $repository);
            return $this->respondWithItem(
                item: $repository
            );
        }catch(\Exception $e) {
            return $this->respondWithError(
                status_code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
