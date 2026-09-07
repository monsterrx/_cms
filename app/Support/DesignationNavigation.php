<?php

namespace App\Support;

use App\Models\DesignationGrant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DesignationNavigation
{
    /**
     * @param  array<int, array<string, mixed>>  $navigation
     * @return array<int, array<string, mixed>>
     */
    public function forUser(?User $user, array $navigation): array
    {
        if (in_array($this->level($user), [1, 2], true)) {
            return $navigation;
        }

        $allowedItems = collect($this->grants($user))->keys();
        $isDj = $this->level($user) === 5;

        return collect($navigation)
            ->map(function (array $section) use ($allowedItems, $isDj): array {
                $section['groups'] = collect($section['groups'])
                    ->map(function (array $group) use ($allowedItems, $isDj, $section): array {
                        $group['items'] = array_values(array_filter(
                            $group['items'],
                            static fn (array $item): bool => $allowedItems->contains("{$section['slug']}:{$item['slug']}"),
                        ));

                        if ($isDj && $section['slug'] === 'staff' && $allowedItems->contains('staff:jocks')) {
                            $group['items'] = array_map(function (array $item): array {
                                if ($item['slug'] === 'jocks') {
                                    $item['label'] = 'My Jock Profile';
                                    $item['description'] = 'View and update your public jock profile, images, links, facts, and shows.';
                                }

                                return $item;
                            }, $group['items']);
                        }

                        return $group;
                    })
                    ->filter(static fn (array $group): bool => $group['items'] !== [])
                    ->values()
                    ->all();

                return $section;
            })
            ->filter(static fn (array $section): bool => $section['groups'] !== [])
            ->values()
            ->all();
    }

    public function canView(?User $user, string $section, ?string $item = null): bool
    {
        if (in_array($this->level($user), [1, 2], true)) {
            return true;
        }

        $grants = collect($this->grants($user));

        return $item === null
            ? $grants->keys()->contains(static fn (string $key): bool => str_starts_with($key, "{$section}:"))
            : $grants->has("{$section}:{$item}");
    }

    public function canWrite(?User $user, string $section, string $item): bool
    {
        if (in_array($this->level($user), [1, 2], true)) {
            return true;
        }

        return (bool) (collect($this->grants($user))->get("{$section}:{$item}") ?? false);
    }

    /** @return array<string, bool> */
    private function grants(?User $user): array
    {
        $designation = $user?->Employee?->Designation;
        $designationId = $user?->Employee?->designation_id ?? $designation?->id;

        if ($designationId === null || (! $designation?->relationLoaded('grants') && ! Schema::hasTable('designation_grants'))) {
            return [];
        }

        $records = $designation?->relationLoaded('grants')
            ? $designation->grants
            : DesignationGrant::query()->where('designation_id', $designationId)->get();
        $grants = $records
            ->mapWithKeys(static fn (DesignationGrant $grant): array => [
                "{$grant->section_slug}:{$grant->item_slug}" => $grant->can_write,
            ])
            ->all();

        if ($this->level($user) === 5 && $this->hostsDailySurvey($user)) {
            $grants['music:daily-survey-top-5'] = true;
            $grants['music:votes'] = true;
        }

        return $grants;
    }

    private function hostsDailySurvey(?User $user): bool
    {
        $employeeId = $user?->Employee?->id;

        if ($employeeId === null || ! Schema::hasTable('jocks') || ! Schema::hasTable('jock_show')) {
            return false;
        }

        return DB::table('jocks')
            ->join('jock_show', 'jock_show.jock_id', '=', 'jocks.id')
            ->join('shows', 'shows.id', '=', 'jock_show.show_id')
            ->where('jocks.employee_id', $employeeId)
            ->whereRaw('LOWER(shows.title) = ?', ['the daily survey'])
            ->exists();
    }

    private function level(?User $user): ?int
    {
        $level = $user?->Employee?->Designation?->level;

        return $level === null ? null : (int) $level;
    }
}
