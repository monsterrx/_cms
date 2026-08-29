<?php

use App\Http\Controllers\Api\SystemHealthController;
use Illuminate\Support\Facades\Route;

Route::get('/system/health', SystemHealthController::class)
    ->name('api.system.health');
