<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Support\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LeaderBoardsController extends BaseControllerConfig
{

    public function index(Request $request, $repository) {

        try {
            $this->repository = $repository;
            $repositories = $this->findByFilters($request->toArray());
            return $this->respondWithCustomData(
                data: $repositories
            );

        }catch(\Exception $e) {
            return $this->respondWithError(
                status_code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
