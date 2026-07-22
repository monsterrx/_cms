<?php

namespace App\Exceptions;

use App\Support\StatusPage;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use PDOException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /** @var array<int, class-string<Throwable>> */
    protected $dontReport = [];

    /** @var array<int, string> */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(static function (Throwable $e): void {
            // Laravel's configured logger handles reportable exceptions.
        });
    }

    /**
     * API requests receive a stable JSON contract; browser requests receive branded status pages.
     */
    public function render($request, Throwable $e): Response
    {
        if ($this->expectsJson($request)) {
            return $this->renderJson($e);
        }

        $response = parent::render($request, $e);
        $status = $response->getStatusCode();

        if (! $request->acceptsHtml() || ! StatusPage::supports($status)) {
            return $response;
        }

        return Inertia::render('Status/StatusPage', StatusPage::forStatus($status))
            ->toResponse($request)
            ->setStatusCode($status);
    }

    private function renderJson(Throwable $e): Response
    {
        if ($e instanceof ValidationException) {
            return $this->errorResponse(
                'The submitted data is invalid.',
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $e->errors(),
                'VALIDATION_ERROR'
            );
        }

        if ($e instanceof AuthenticationException) {
            return $this->errorResponse(
                'Authentication is required.',
                Response::HTTP_UNAUTHORIZED,
                [],
                'UNAUTHENTICATED'
            );
        }

        if ($e instanceof AuthorizationException) {
            return $this->errorResponse(
                'You are not authorized to perform this action.',
                Response::HTTP_FORBIDDEN,
                [],
                'FORBIDDEN'
            );
        }

        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            return $this->errorResponse(
                'The requested resource was not found.',
                Response::HTTP_NOT_FOUND,
                [],
                'NOT_FOUND'
            );
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse(
                'The requested method is not allowed for this endpoint.',
                Response::HTTP_METHOD_NOT_ALLOWED,
                [],
                'METHOD_NOT_ALLOWED'
            );
        }

        if ($e instanceof TokenMismatchException) {
            return $this->errorResponse(
                'Your session has expired. Refresh the page and try again.',
                419,
                [],
                'SESSION_EXPIRED'
            );
        }

        if ($e instanceof QueryException || $e instanceof PDOException) {
            return $this->databaseErrorResponse($e);
        }

        if ($e instanceof HttpExceptionInterface) {
            $status = $e->getStatusCode();
            $message = match ($status) {
                Response::HTTP_TOO_MANY_REQUESTS => 'Too many requests. Please try again later.',
                Response::HTTP_FORBIDDEN => 'You are not authorized to perform this action.',
                Response::HTTP_SERVICE_UNAVAILABLE => 'The service is temporarily unavailable.',
                default => $status >= 500
                    ? 'An unexpected server error occurred.'
                    : ($e->getMessage() ?: Response::$statusTexts[$status] ?? 'Request failed.'),
            };

            return $this->errorResponse($message, $status, [], $this->errorCodeForStatus($status));
        }

        return $this->errorResponse(
            'An unexpected server error occurred.',
            Response::HTTP_INTERNAL_SERVER_ERROR,
            [],
            'SERVER_ERROR'
        );
    }

    private function databaseErrorResponse(Throwable $e): Response
    {
        $errorInfo = $e instanceof QueryException
            ? $e->errorInfo
            : ($e->errorInfo ?? []);
        $sqlState = (string) ($errorInfo[0] ?? $e->getCode());

        if (str_starts_with($sqlState, '23')) {
            return $this->errorResponse(
                'This record conflicts with existing content. Review it and try again.',
                Response::HTTP_CONFLICT,
                [],
                'DATA_CONFLICT'
            );
        }

        if (str_starts_with($sqlState, '08') || in_array($sqlState, ['2002', '2006'], true)) {
            return $this->errorResponse(
                'The content database is temporarily unavailable. Please try again shortly.',
                Response::HTTP_SERVICE_UNAVAILABLE,
                [],
                'DATABASE_UNAVAILABLE'
            );
        }

        return $this->errorResponse(
            'The request could not be completed because of a data service error.',
            Response::HTTP_INTERNAL_SERVER_ERROR,
            [],
            'DATABASE_ERROR'
        );
    }

    private function expectsJson(Request $request): bool
    {
        return $request->expectsJson()
            || $request->is('api/*')
            || $request->ajax();
    }

    private function errorCodeForStatus(int $status): string
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
    private function errorResponse(string $message, int $status, array $errors, string $code): Response
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'code' => $code,
        ], $status);
    }
}
