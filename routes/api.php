<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function (): void {
    require __DIR__.'/api/operations.php';
    require __DIR__.'/api/music.php';
    require __DIR__.'/api/resources.php';
});

require __DIR__.'/api/system.php';
