<?php

namespace Tests\Feature;

use App\Models\Designation;
use App\Models\DesignationGrant;
use App\Models\Employee;
use App\Models\User;
use App\Support\DesignationNavigation;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DesignationNavigationTest extends TestCase
{
    /** @param array<string, array<int, string>> $expected */
    #[DataProvider('designationNavigationProvider')]
    public function test_navigation_matches_the_legacy_cms_designation_menu(int $level, array $expected): void
    {
        $navigation = app(DesignationNavigation::class)->forUser(
            $this->userAtLevel($level, $expected),
            config('workspace.navigation'),
        );

        $actual = collect($navigation)->mapWithKeys(fn (array $section): array => [
            $section['slug'] => collect($section['groups'])
                ->flatMap(fn (array $group): array => $group['items'])
                ->pluck('slug')->values()->all(),
        ])->all();

        $this->assertSame(in_array($level, [1, 2], true) ? $this->allNavigationItems() : $expected, $actual);
    }

    public function test_workspace_routes_enforce_the_designation_navigation(): void
    {
        $this->actingAs($this->userAtLevel(3, ['digital-content-programs' => ['articles']]));

        $this->get('/workspace/digital-content-programs/articles')->assertOk();
        $this->get('/workspace/staff/staffs')->assertForbidden();
        $this->get('/workspace/utilities')->assertForbidden();
    }

    public function test_jock_designations_do_not_receive_management_workspace_routes(): void
    {
        $this->actingAs($this->userAtLevel(5, ['staff' => ['jocks']]));

        $this->get('/workspace/music')->assertForbidden();
        $this->get('/workspace/staff/jocks')->assertOk();
    }

    /** @return array<string, array{int, array<string, array<int, string>>}> */
    public static function designationNavigationProvider(): array
    {
        return [
            'developer' => [1, []],
            'admin' => [2, []],
            'digital content specialist' => [3, [
                'staff' => ['jocks', 'radio1-batches', 'student-jocks'],
                'music' => ['artists', 'albums', 'songs', 'genres', 'indieground-artists', 'indieground-featured'],
                'digital-content-programs' => ['articles', 'categories', 'shows', 'timeslots', 'podcasts'],
                'promos' => ['giveaways', 'contestants'],
            ]],
            'graphic artist' => [4, [
                'staff' => ['jocks'],
                'digital-content-programs' => ['graphics-artist', 'wallpapers', 'shows'],
                'events-scholarship' => ['schools', 'gimik-board'],
            ]],
            'dj' => [5, ['staff' => ['jocks']]],
            'receptionist' => [6, [
                'utilities' => ['messages'],
            ]],
            'on job trainee' => [7, [
                'music' => ['artists', 'albums', 'songs', 'genres', 'station-chart', 'dropouts'],
            ]],
            'jock admin' => [8, [
                'staff' => ['jocks'],
                'digital-content-programs' => ['shows', 'timeslots', 'podcasts'],
            ]],
            'user' => [9, [
                'staff' => ['staffs', 'student-jocks', 'awards'],
                'music' => ['artists', 'albums', 'songs', 'genres', 'station-chart', 'dropouts'],
                'events-scholarship' => ['schools', 'gimik-board', 'scholar-batches', 'students', 'sponsors'],
            ]],
        ];
    }

    /** @return array<string, array<int, string>> */
    private function allNavigationItems(): array
    {
        return collect(config('workspace.navigation'))->mapWithKeys(fn (array $section): array => [
            $section['slug'] => collect($section['groups'])
                ->flatMap(fn (array $group): array => $group['items'])
                ->pluck('slug')->values()->all(),
        ])->all();
    }

    /** @param array<string, array<int, string>> $grants */
    private function userAtLevel(int $level, array $grants = []): User
    {
        $user = (new User)->forceFill([
            'id' => $level,
            'employee_id' => $level,
            'employee_number' => "ROLE{$level}",
            'email' => "role{$level}@example.com",
        ]);
        $employee = (new Employee)->forceFill(['id' => $level, 'designation_id' => $level]);
        $designation = (new Designation)->forceFill(['id' => $level, 'level' => $level]);
        $designation->setRelation('grants', collect($grants)->flatMap(
            fn (array $items, string $section) => collect($items)->map(
                fn (string $item) => (new DesignationGrant)->forceFill([
                    'designation_id' => $level,
                    'section_slug' => $section,
                    'item_slug' => $item,
                    'can_write' => true,
                ])
            )
        ));

        $employee->setRelation('Designation', $designation);
        $user->setRelation('Employee', $employee);

        return $user;
    }
}
