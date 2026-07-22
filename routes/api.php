<?php

use App\Http\Controllers\Api\ResourceRecordController;
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

Route::prefix('resources')
    ->middleware('auth:sanctum')
    ->group(function (): void {
        Route::get('/{section}/{item}', [ResourceRecordController::class, 'index']);
        Route::post('/{section}/{item}', [ResourceRecordController::class, 'store']);
        Route::get('/{section}/{item}/{id}', [ResourceRecordController::class, 'show'])->whereNumber('id');
        Route::match(['put', 'patch'], '/{section}/{item}/{id}', [ResourceRecordController::class, 'update'])->whereNumber('id');
        Route::delete('/{section}/{item}/{id}', [ResourceRecordController::class, 'destroy'])->whereNumber('id');
    });

Route::get('/system/health', SystemHealthController::class)
    ->name('api.system.health');
