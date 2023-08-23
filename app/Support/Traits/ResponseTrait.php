<?php

namespace App\Support\Traits;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ResponseTrait
{
    /**
     * The current path of resource to respond
     *
     * @var string
     */
    protected string $resourceItem;

    /**
     * The current path of collection resource to respond
     *
     * @var string
     */
    protected string $resourceCollection;

    /**
     * @param mixed $data
     * @param int $status_code
     * @param string $message
     * @return JsonResponse
     */
    protected function respondWithCustomData(
        mixed $data,
        int $status_code = 200,
        string $message = "Successful"
    ): JsonResponse
    {
        return new JsonResponse([
            'message' => __($message),
            'status' => $status_code,
            'data' => $data,
            'meta' => ['timestamp' => $this->getTimestampInMilliseconds()]
        ], $status_code);
    }

    /**
     * @return int
     */
    protected function getTimestampInMilliseconds(): int
    {
        return intdiv((int)now()->format('Uu'), 1000);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    protected function respondWithNoContent(
        string $message = "Success"
    ): JsonResponse
    {
        return new JsonResponse([
            'message' => __($message),
            'status' => Response::HTTP_OK,
            'data' => null,
            'meta' => ['timestamp' => $this->getTimestampInMilliseconds()],
        ], Response::HTTP_OK);
    }

    /**
     * @param mixed $context
     * @param string $message
     * @return JsonResponse
     */
    protected function respondWithValidationError(
        mixed $context,
        string $message = "Error"
    ): JsonResponse
    {
        return new JsonResponse([
            'message' => __($message),
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'errors' => $context,
            'data' => null,
            'meta' => ['timestamp' => $this->getTimestampInMilliseconds()],
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param string $message
     * @param int $status_code
     * @return JsonResponse
     */
    protected function respondWithError(
        string $message = "Something went wrong. Kindly try again later.",
        int $status_code = Response::HTTP_BAD_REQUEST
    ): JsonResponse
    {
        return new JsonResponse([
            'message' => __($message),
            'status' => $status_code,
            'data' => null,
            'meta' => ['timestamp' => $this->getTimestampInMilliseconds()],
        ], $status_code);
    }

    /**
     *
     * Return collection response from the application
     */
    protected function respondWithCollection(LengthAwarePaginator|CursorPaginator $collection)
    {
        return (new $this->resourceCollection($collection))->additional(
            [
                'message' => __("Success"),
                'status' => Response::HTTP_OK,
                'meta' => ['timestamp' => $this->getTimestampInMilliseconds()]
            ]
        );
    }

    /**
     *
     * Return single item response from the application
     */
    protected function respondWithItem(Model|array|null $item)
    {
        return (new $this->resourceItem($item))->additional(
            [
                'message' => __("Success"),
                'status' => Response::HTTP_OK,
                'meta' => ['timestamp' => $this->getTimestampInMilliseconds()]
            ]
        );
    }
}
