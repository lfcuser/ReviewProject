<?php

namespace App\Http\Responses;

use App\Exceptions\AbstractAppException;
use Throwable;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ApiErrorResponseHandler
{
    public function handle($request, Throwable $e): Response|ResponseFactory
    {
        if ($e instanceof ValidationException) {
            return response()->error($e->getMessage(), SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->error('NOT FOUND', SymfonyResponse::HTTP_NOT_FOUND);
        }

        if ($e instanceof AuthenticationException) {
            return response()->error('UNAUTHORIZED', SymfonyResponse::HTTP_UNAUTHORIZED);
        }

        if ($e instanceof AbstractAppException) {
            return response()->error($e->getMessage(), $e->getStatus());
        }

        return response()->error($e->getMessage(), SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
