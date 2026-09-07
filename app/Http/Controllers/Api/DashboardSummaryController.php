<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\DesignationNavigation;
use App\Support\StationContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

final class DashboardSummaryController extends Controller
{
    public function __construct(private StationContext $stations, private DesignationNavigation $navigation) {}

    public function __invoke(Request $request): JsonResponse
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

        $level = $request->user()?->Employee?->Designation?->level;
        $recentWorkspaces = $this->recentWorkspaces($request);
        $activities = in_array((int) $level, [1, 2], true) ? DB::table('user_logs')
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
            ->all() : [];

        return $this->successResponse([
            'pending_reviews' => $pendingReviews,
            'active_promos' => $activePromos,
            'recent_activity' => $activities,
            'can_view_recent_activity' => in_array((int) $level, [1, 2], true),
            'recent_workspaces' => $recentWorkspaces,
        ], 'Dashboard summary loaded successfully.');
    }

    /** @return array<int, array{label: string, section: string, href: string, visited_at: mixed}> */
    private function recentWorkspaces(Request $request): array
    {
        if (! Schema::hasTable('user_workspace_visits')) {
            return [];
        }

        $navigation = $this->navigation->forUser($request->user(), config('workspace.navigation', []));
        $destinations = collect($navigation)->flatMap(function (array $section) {
            $sectionDestination = [[
                'key' => $section['slug'].':',
                'label' => $section['label'],
                'section' => $section['label'],
                'href' => "/workspace/{$section['slug']}",
            ]];
            $items = collect($section['groups'])->flatMap(
                fn (array $group) => collect($group['items'])->map(fn (array $item): array => [
                    'key' => "{$section['slug']}:{$item['slug']}",
                    'label' => $item['label'],
                    'section' => $section['label'],
                    'href' => "/workspace/{$section['slug']}/{$item['slug']}",
                ])
            )->all();

            return [...$sectionDestination, ...$items];
        })->keyBy('key');

        return DB::table('user_workspace_visits')
            ->where('user_id', $request->user()->id)
            ->orderByDesc('last_visited_at')
            ->limit(12)
            ->get()
            ->map(function (object $visit) use ($destinations): ?array {
                $destination = $destinations->get("{$visit->section_slug}:{$visit->item_slug}");

                return $destination === null ? null : [
                    'label' => $destination['label'],
                    'section' => $destination['section'],
                    'href' => $destination['href'],
                    'visited_at' => $visit->last_visited_at,
                ];
            })
            ->filter()
            ->take(9)
            ->values()
            ->all();
    }
}
