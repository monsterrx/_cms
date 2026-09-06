<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

final class SystemHealthController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $startedAt = microtime(true);

        try {
            DB::select('SELECT 1');
        } catch (QueryException $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'The system is temporarily unable to reach its database.',
                'errors' => [],
                'code' => 'DATABASE_UNAVAILABLE',
                'data' => [
                    'application' => ['status' => 'operational'],
                    'database' => ['status' => 'unavailable'],
                    'checked_at' => now()->toIso8601String(),
                ],
            ], 503);
        }

        return response()->json([
            'success' => true,
            'message' => 'The application and database are operational.',
            'data' => [
                'application' => ['status' => 'operational'],
                'database' => ['status' => 'connected'],
                'checked_at' => now()->toIso8601String(),
                'response_time_ms' => round((microtime(true) - $startedAt) * 1000, 2),
            ],
        ]);
    }
}
