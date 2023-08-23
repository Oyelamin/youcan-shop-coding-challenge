<?php

namespace App\Exceptions;

use App\Support\Traits\ResponseTrait;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomApiExceptionHandler extends ExceptionHandler
{
    use ResponseTrait;
    public function render($request, \Throwable $exception)
    {
        // Check if the request is an API request
        if ($request->is('api/*')) {
            return $this->handleApiException($request, $exception);
        }

        return parent::render($request, $exception);
    }

    private function handleApiException($request, \Throwable $e): JsonResponse
    {
        $exceptionInstance = get_class($e);
        $isClientError = true;

        switch ($exceptionInstance) {
            case AuthenticationException::class:
            case AuthorizationException::class:
                $status = Response::HTTP_UNAUTHORIZED;
                $message = $e->getMessage();
                break;
            case AuthorizationException::class|AccessDeniedHttpException::class:
                $status = Response::HTTP_FORBIDDEN;
                $message = !empty($e->getMessage()) ? $e->getMessage() : 'Forbidden';
                break;

            case MethodNotAllowedHttpException::class:
                $status = Response::HTTP_METHOD_NOT_ALLOWED;
                $message = 'Method not allowed';
                break;
            case NotFoundHttpException::class:
            case ModelNotFoundException::class:
                $status = Response::HTTP_NOT_FOUND;
                $message = 'The requested resource was not found';
                break;
            case QueryException::class:
                $status = Response::HTTP_BAD_REQUEST;
                $message = 'Internal error';
                break;
            case ThrottleRequestsException::class:
                $status = Response::HTTP_TOO_MANY_REQUESTS;
                $message = 'Too many Requests';
                break;
            case ValidationException::class:
                $status = Response::HTTP_UNPROCESSABLE_ENTITY;
                $message = $e->getMessage();
                $errors = $e->validator->getMessageBag()->toArray();
                break;
            default:
                $status = $e->getCode() != 0 ? $e->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
                $message = $e->getMessage();
                $isClientError = false;
                break;
        }

        if (!empty($status) && !empty($message)) {

            if(!$isClientError){ // Log error for easy tracking...
                $errorMessage = "An error occurred
                        \n Status:: {$status}
                        \n Message:: {$message}
                        \n File:: {$e->getFile()}
                        \n Line:: {$e->getLine()}
                        \n URL:: {$request->fullUrl()} \n";
                Log::error($errorMessage);
                $message = "Something went wrong internally. Kindly try again later.";
            }
            return $this->respondWithCustomData(message: $message, status_code: $status, data: null);
        }

        return $this->respondWithNoContent();
    }
}
