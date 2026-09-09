<?php

use App\Http\Controllers\Api\BugReportController;
use App\Http\Controllers\Api\DashboardSummaryController;
use App\Http\Controllers\Api\EditorImageController;
use App\Http\Controllers\Api\MessageReplyController;
use App\Http\Controllers\Api\StationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', static fn (Request $request) => response()->json([
    'success' => true,
    'message' => 'Authenticated user retrieved successfully.',
    'data' => ['user' => $request->user()],
]));

Route::get('/dashboard-summary', DashboardSummaryController::class);
Route::post('/messages/{message}/reply', MessageReplyController::class);
Route::put('/station', [StationController::class, 'update']);
Route::post('/bug-reports', [BugReportController::class, 'store'])
    ->middleware('throttle:10,1');

Route::post('/editor-images', EditorImageController::class)->middleware('throttle:30,1');
