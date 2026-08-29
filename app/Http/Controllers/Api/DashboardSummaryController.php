<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\StationContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

final class DashboardSummaryController extends Controller
{
    public function __construct(private StationContext $stations) {}

    public function __invoke(): JsonResponse
    {
        $stationCode = $this->stations->current();

        $pendingReviews = DB::table('articles')
            ->whereNull('deleted_at')
            ->whereNull('published_at')
            ->when($stationCode, fn ($query) => $query->where('location', $stationCode))
            ->count();

        $activePromos = DB::table('giveaways')
            ->whereNull('deleted_at')
            ->where('is_active', 1)
            ->when($stationCode, fn ($query) => $query->where('location', $stationCode))
            ->count();

        $activities = DB::table('user_logs')
            ->leftJoin('employees', 'employees.id', '=', 'user_logs.employee_id')
            ->when($stationCode, fn ($query) => $query->where('user_logs.location', $stationCode))
            ->orderByDesc('user_logs.created_at')
            ->limit(8)
            ->get([
                'user_logs.id',
                'user_logs.action',
                'user_logs.created_at',
                'employees.first_name',
                'employees.last_name',
            ])
            ->map(static fn (object $activity): array => [
                'id' => $activity->id,
                'action' => $activity->action,
                'user' => trim("{$activity->first_name} {$activity->last_name}") ?: 'System user',
                'created_at' => $activity->created_at,
            ])
            ->all();

        return $this->successResponse([
            'pending_reviews' => $pendingReviews,
            'active_promos' => $activePromos,
            'recent_activity' => $activities,
        ], 'Dashboard summary loaded successfully.');
    }
}
