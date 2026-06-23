<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render JSON errors for API and AJAX requests without exposing internals.
     */
    public function render($request, Throwable $e): Response
    {
        if (! $this->expectsJson($request)) {
            return parent::render($request, $e);
        }

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

            return $this->errorResponse($message, $status, [], 'HTTP_ERROR');
        }

        return $this->errorResponse(
            'An unexpected server error occurred.',
            Response::HTTP_INTERNAL_SERVER_ERROR,
            [],
            'SERVER_ERROR'
        );
    }

    private function expectsJson(Request $request): bool
    {
        return $request->expectsJson()
            || $request->is('api/*')
            || $request->ajax();
    }

    /**
     * @param  array<string, array<int, string>|string>  $errors
     */
    private function errorResponse(
        string $message,
        int $status,
        array $errors,
        string $code
    ): Response {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'code' => $code,
        ], $status);
    }
}
