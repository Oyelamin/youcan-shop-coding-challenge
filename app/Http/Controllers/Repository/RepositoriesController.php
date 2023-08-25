<?php

namespace App\Http\Controllers\Repository;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class RepositoriesController extends BaseControllerConfig
{
    /**
     * @param Request $request
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function index(Request $request): JsonResponse|AnonymousResourceCollection {

        try {
            $page = $request->page ?? 1;
            $perPage = $request->limit ?? 20;
            $this->gitHubApi->page = $page;
            $this->gitHubApi->perPage = $perPage;
            $repositories = $this->gitHubApi->getUserRepositories();
            return $this->respondWithCollection($repositories);

        }catch(\Exception $e) {
            return $this->respondWithError(
                status_code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

    }

    public function show(Request $request, $repository) {
        $repository = $this->gitHubApi->getUserRepository(
            name: $repository
        );
        return $this->respondWithItem($repository);
    }
}
