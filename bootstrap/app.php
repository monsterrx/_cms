<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Support\ApiExceptionResponse;
use App\Support\StatusPage;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();

        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->redirectGuestsTo(fn (Request $request): string => route('login'));
        $middleware->redirectUsersTo(fn (Request $request): string => route('dashboard'));
        $middleware->trimStrings(except: [
            'current_password',
            'password',
            'password_confirmation',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $expectsJson = static fn (Request $request): bool => $request->expectsJson()
            || $request->is('api/*')
            || $request->ajax();

        $exceptions->dontFlash([
            'current_password',
            'password',
            'password_confirmation',
        ]);

        $exceptions->shouldRenderJsonWhen(
            static fn (Request $request, Throwable $exception): bool => $expectsJson($request),
        );

        $exceptions->render(
            static fn (Throwable $exception, Request $request): ?Response => $expectsJson($request)
                ? app(ApiExceptionResponse::class)->render($exception)
                : null,
        );

        $exceptions->respond(function (Response $response, Throwable $exception, Request $request) use ($expectsJson): Response {
            $status = $response->getStatusCode();

            if ($expectsJson($request) || ! $request->acceptsHtml() || ! StatusPage::supports($status)) {
                return $response;
            }

            return Inertia::render('Status/StatusPage', StatusPage::forStatus($status))
                ->toResponse($request)
                ->setStatusCode($status);
        });
    })
    ->create();
