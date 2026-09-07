<?php

use App\Http\Controllers\Api\DesignationGrantController;
use App\Http\Controllers\Api\ResourceModuleController;
use App\Http\Controllers\Api\ResourceRecordController;
use Illuminate\Support\Facades\Route;

Route::prefix('resources')->group(function (): void {
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

Route::get('/designations/{designation}/grants', [DesignationGrantController::class, 'show']);
Route::put('/designations/{designation}/grants', [DesignationGrantController::class, 'update']);
