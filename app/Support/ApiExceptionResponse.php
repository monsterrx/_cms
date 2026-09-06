<?php

namespace App\Support;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use PDOException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

final class ApiExceptionResponse
{
    public function render(Throwable $exception): JsonResponse
    {
        return match (true) {
            $exception instanceof ValidationException => $this->error(
                'The submitted data is invalid.',
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $exception->errors(),
                'VALIDATION_ERROR',
            ),
            $exception instanceof AuthenticationException => $this->error(
                'Authentication is required.', Response::HTTP_UNAUTHORIZED, [], 'UNAUTHENTICATED',
            ),
            $exception instanceof AuthorizationException => $this->error(
                'You are not authorized to perform this action.', Response::HTTP_FORBIDDEN, [], 'FORBIDDEN',
            ),
            $exception instanceof ModelNotFoundException,
            $exception instanceof NotFoundHttpException => $this->error(
                'The requested resource was not found.', Response::HTTP_NOT_FOUND, [], 'NOT_FOUND',
            ),
            $exception instanceof MethodNotAllowedHttpException => $this->error(
                'The requested method is not allowed for this endpoint.',
                Response::HTTP_METHOD_NOT_ALLOWED,
                [],
                'METHOD_NOT_ALLOWED',
            ),
            $exception instanceof TokenMismatchException => $this->error(
                'Your session has expired. Refresh the page and try again.', 419, [], 'SESSION_EXPIRED',
            ),
            $exception instanceof QueryException,
            $exception instanceof PDOException => $this->databaseError($exception),
            $exception instanceof HttpExceptionInterface => $this->httpError($exception),
            default => $this->error(
                'An unexpected server error occurred.',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                [],
                'SERVER_ERROR',
            ),
        };
    }

    private function databaseError(Throwable $exception): JsonResponse
    {
        $errorInfo = $exception instanceof QueryException
            ? $exception->errorInfo
            : ($exception->errorInfo ?? []);
        $sqlState = (string) ($errorInfo[0] ?? $exception->getCode());

        if (str_starts_with($sqlState, '23')) {
            return $this->error(
                'This record conflicts with existing content. Review it and try again.',
                Response::HTTP_CONFLICT,
                [],
                'DATA_CONFLICT',
            );
        }

        if (str_starts_with($sqlState, '08') || in_array($sqlState, ['2002', '2006'], true)) {
            return $this->error(
                'The content database is temporarily unavailable. Please try again shortly.',
                Response::HTTP_SERVICE_UNAVAILABLE,
                [],
                'DATABASE_UNAVAILABLE',
            );
        }

        return $this->error(
            'The request could not be completed because of a data service error.',
            Response::HTTP_INTERNAL_SERVER_ERROR,
            [],
            'DATABASE_ERROR',
        );
    }

    private function httpError(HttpExceptionInterface $exception): JsonResponse
    {
        $status = $exception->getStatusCode();
        $message = match ($status) {
            Response::HTTP_TOO_MANY_REQUESTS => 'Too many requests. Please try again later.',
            Response::HTTP_FORBIDDEN => 'You are not authorized to perform this action.',
            Response::HTTP_SERVICE_UNAVAILABLE => 'The service is temporarily unavailable.',
            default => $status >= 500
                ? 'An unexpected server error occurred.'
                : ($exception->getMessage() ?: Response::$statusTexts[$status] ?? 'Request failed.'),
        };

        return $this->error($message, $status, [], $this->codeForStatus($status));
    }

    private function codeForStatus(int $status): string
    {
        return match ($status) {
            Response::HTTP_BAD_REQUEST => 'BAD_REQUEST',
            Response::HTTP_UNAUTHORIZED => 'UNAUTHENTICATED',
            Response::HTTP_FORBIDDEN => 'FORBIDDEN',
            Response::HTTP_NOT_FOUND => 'NOT_FOUND',
            Response::HTTP_METHOD_NOT_ALLOWED => 'METHOD_NOT_ALLOWED',
            Response::HTTP_REQUEST_TIMEOUT => 'REQUEST_TIMEOUT',
            Response::HTTP_CONFLICT => 'CONFLICT',
            Response::HTTP_UNPROCESSABLE_ENTITY => 'VALIDATION_ERROR',
            Response::HTTP_TOO_MANY_REQUESTS => 'TOO_MANY_REQUESTS',
            Response::HTTP_BAD_GATEWAY => 'BAD_GATEWAY',
            Response::HTTP_SERVICE_UNAVAILABLE => 'SERVICE_UNAVAILABLE',
            Response::HTTP_GATEWAY_TIMEOUT => 'GATEWAY_TIMEOUT',
            default => $status >= 500 ? 'SERVER_ERROR' : 'HTTP_ERROR',
        };
    }

    /** @param array<string, array<int, string>|string> $errors */
    private function error(string $message, int $status, array $errors, string $code): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'code' => $code,
        ], $status);
    }
}
