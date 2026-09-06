<?php

use App\Http\Controllers\Api\BugReportController;
use App\Http\Controllers\Api\DashboardSummaryController;
use App\Http\Controllers\Api\StationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', static fn (Request $request) => response()->json([
    'success' => true,
    'message' => 'Authenticated user retrieved successfully.',
    'data' => ['user' => $request->user()],
]));

Route::get('/dashboard-summary', DashboardSummaryController::class);
Route::put('/station', [StationController::class, 'update'])->middleware('web');
Route::post('/bug-reports', [BugReportController::class, 'store'])
    ->middleware('throttle:10,1');
