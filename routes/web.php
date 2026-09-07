<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', static fn () => redirect()->route('dashboard'));

Route::get('/login', static fn () => Inertia::render('Auth/Login'))
    ->middleware('guest')
    ->name('login');

Route::middleware('auth')->group(function (): void {
    require __DIR__.'/cms/operations.php';
    require __DIR__.'/cms/content.php';
    require __DIR__.'/cms/workspace.php';
});

Route::get('/status/{status}', static function (int $status): never {
    abort($status);
})->whereIn('status', [400, 401, 403, 404, 405, 408, 409, 419, 422, 429, 500, 502, 503, 504]);

require __DIR__.'/auth.php';
