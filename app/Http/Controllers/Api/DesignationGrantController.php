<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Models\DesignationGrant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DesignationGrantController extends Controller
{
    public function show(Request $request, Designation $designation): JsonResponse
    {
        $this->authorizeManager($request);

        return $this->successResponse([
            'designation' => $designation->only(['id', 'name', 'level']),
            'available' => $this->available(),
            'grants' => $designation->grants()->get(['section_slug', 'item_slug', 'can_write']),
        ], 'Designation grants loaded successfully.');
    }

    public function update(Request $request, Designation $designation): JsonResponse
    {
        $this->authorizeManager($request);
        abort_if(in_array((int) $designation->level, [1, 2], true), 422, 'Developer and Admin always have complete access.');

        $available = collect($this->available())->pluck('key')->all();
        $validated = $request->validate([
            'grants' => ['present', 'array'],
            'grants.*.key' => ['required', 'string', Rule::in($available), 'distinct'],
            'grants.*.can_write' => ['required', 'boolean'],
        ]);

        DB::transaction(function () use ($designation, $validated): void {
            $designation->grants()->delete();
            foreach ($validated['grants'] as $grant) {
                [$section, $item] = explode(':', $grant['key'], 2);
                DesignationGrant::query()->create([
                    'designation_id' => $designation->id,
                    'section_slug' => $section,
                    'item_slug' => $item,
                    'can_write' => $grant['can_write'],
                ]);
            }
        });

        return $this->show($request, $designation->refresh());
    }

    /** @return array<int, array{key: string, section: string, item: string, label: string}> */
    private function available(): array
    {
        return collect(config('workspace.navigation', []))->flatMap(
            static fn (array $section) => collect($section['groups'])->flatMap(
                static fn (array $group) => collect($group['items'])->map(static fn (array $item): array => [
                    'key' => "{$section['slug']}:{$item['slug']}",
                    'section' => $section['label'],
                    'item' => $item['label'],
                    'label' => "{$section['label']} / {$group['label']} / {$item['label']}",
                ])
            )
        )->values()->all();
    }

    private function authorizeManager(Request $request): void
    {
        $level = $request->user()?->Employee?->Designation?->level;
        abort_unless($level !== null && in_array((int) $level, [1, 2], true), 403);
    }
}
