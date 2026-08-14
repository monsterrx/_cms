<?php

use App\Http\Controllers\Api\ResourceRecordController;
use App\Http\Controllers\Api\BugReportController;
use App\Http\Controllers\Api\ResourceModuleController;
use App\Http\Controllers\Api\ChartWorkspaceController;
use App\Http\Controllers\Api\DashboardSummaryController;
use App\Http\Controllers\Api\SystemHealthController;
use App\Http\Controllers\Api\StationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'Authenticated user retrieved successfully.',
        'data' => [
            'user' => $request->user(),
        ],
    ]);
});

Route::get('/dashboard-summary', DashboardSummaryController::class)
    ->middleware('auth:sanctum');

Route::put('/station', [StationController::class, 'update'])
    ->middleware('auth:sanctum');

Route::post('/bug-reports', [BugReportController::class, 'store'])
    ->middleware(['auth:sanctum', 'throttle:10,1']);

Route::prefix('charts/workspace')
    ->middleware('auth:sanctum')
    ->group(function (): void {
        Route::get('/', [ChartWorkspaceController::class, 'index']);
        Route::post('/entries', [ChartWorkspaceController::class, 'store']);
        Route::put('/order', [ChartWorkspaceController::class, 'reorder']);
        Route::post('/publish', [ChartWorkspaceController::class, 'publish']);
        Route::post('/schedules', [ChartWorkspaceController::class, 'schedule']);
        Route::get('/votes', [ChartWorkspaceController::class, 'votes']);
        Route::post('/votes/{id}/increment', [ChartWorkspaceController::class, 'incrementVote'])->whereNumber('id');
        Route::delete('/entries/{id}', [ChartWorkspaceController::class, 'destroy'])->whereNumber('id');
    });

Route::prefix('resources')
    ->middleware('auth:sanctum')
    ->group(function (): void {
        Route::get('/{section}/{item}', [ResourceRecordController::class, 'index']);
        Route::post('/{section}/{item}', [ResourceRecordController::class, 'store']);
        Route::get('/{section}/{item}/{id}', [ResourceRecordController::class, 'show'])->whereNumber('id');
        Route::get('/{section}/{item}/{id}/details', [ResourceModuleController::class, 'details'])->whereNumber('id');
        Route::post('/{section}/{item}/{id}/children/{relation}', [ResourceModuleController::class, 'storeChild'])->whereNumber('id');
        Route::match(['put', 'patch'], '/{section}/{item}/{id}/children/{relation}/{childId}', [ResourceModuleController::class, 'updateChild'])
            ->whereNumber(['id', 'childId']);
        Route::delete('/{section}/{item}/{id}/children/{relation}/{childId}', [ResourceModuleController::class, 'destroyChild'])
            ->whereNumber(['id', 'childId']);
        Route::post('/{section}/{item}/{id}/actions/{action}', [ResourceModuleController::class, 'action'])->whereNumber('id');
        Route::match(['put', 'patch'], '/{section}/{item}/{id}', [ResourceRecordController::class, 'update'])->whereNumber('id');
        Route::delete('/{section}/{item}/{id}', [ResourceRecordController::class, 'destroy'])->whereNumber('id');
    });

Route::get('/system/health', SystemHealthController::class)
    ->name('api.system.health');
