<?php

namespace App\Http\Controllers;

use App\Traits\AssetProcessors;
use App\Traits\ChartFunctions;
use App\Traits\JockFunctions;
use App\Traits\LogsUsers;
use App\Traits\MediaProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AssetProcessors, AuthorizesRequests, ChartFunctions, DispatchesJobs, JockFunctions, LogsUsers, MediaProcessors, SystemFunctions, ValidatesRequests;

    /**
     * Return a consistent successful API response.
     */
    protected function successResponse(
        mixed $data = null,
        string $message = 'Request completed successfully.',
        int $status = 200
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}
