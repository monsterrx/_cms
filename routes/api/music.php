<?php

use App\Http\Controllers\Api\ChartWorkspaceController;
use Illuminate\Support\Facades\Route;

Route::prefix('charts/workspace')->group(function (): void {
    Route::get('/', [ChartWorkspaceController::class, 'index']);
    Route::post('/entries', [ChartWorkspaceController::class, 'store']);
    Route::put('/order', [ChartWorkspaceController::class, 'reorder']);
    Route::post('/publish', [ChartWorkspaceController::class, 'publish']);
    Route::post('/schedules', [ChartWorkspaceController::class, 'schedule']);
    Route::get('/votes', [ChartWorkspaceController::class, 'votes']);
    Route::post('/votes/{id}/increment', [ChartWorkspaceController::class, 'incrementVote'])->whereNumber('id');
    Route::delete('/entries/{id}', [ChartWorkspaceController::class, 'destroy'])->whereNumber('id');
});
