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
            $repositories = $this->findByFilters($request->toArray());
            return $this->respondWithCollection(
                collection: $repositories
            );

        }catch(\Exception $e) {
            return $this->respondWithError(
                status_code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

    }

    public function show(Request $request, $repository) {
        try{
            $repository = $this->gitHubApi->getUserRepository(
                name: $repository
            );
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
