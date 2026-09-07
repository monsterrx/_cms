<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class WorkspaceResourceContractTest extends TestCase
{
    #[DataProvider('workspaceModules')]
    public function test_every_navigation_module_has_a_crud_contract(
        string $section,
        string $item,
        string $table,
        bool $readOnly,
    ): void {
        $definition = config("workspace.resources.{$section}.{$item}");
        $navigationGroups = collect(config('workspace.navigation'))->firstWhere('slug', $section)['groups'] ?? [];
        $navigationSlugs = collect($navigationGroups)
            ->flatMap(static fn (array $group): array => $group['items'])
            ->pluck('slug');

        $this->assertIsArray($definition, "{$section}/{$item} has no resource definition.");
        $this->assertSame($table, $definition['table']);
        $this->assertSame($readOnly, (bool) ($definition['read_only'] ?? false));
        $this->assertTrue($navigationSlugs->contains($item), "{$section}/{$item} is missing from navigation.");
    }

    public function test_resource_routes_expose_the_shared_crud_operations(): void
    {
        $routes = collect(app('router')->getRoutes()->getRoutes())
            ->filter(static fn ($route): bool => str_starts_with($route->uri(), 'api/resources/'));

        foreach (['GET', 'POST', 'PUT', 'PATCH', 'DELETE'] as $method) {
            $this->assertTrue(
                $routes->contains(static fn ($route): bool => in_array($method, $route->methods(), true)),
                "The resource API is missing its {$method} operation.",
            );
        }
    }

    /** @return array<string, array{string, string, string, bool}> */
    public static function workspaceModules(): array
    {
        return [
            'staffs' => ['staff', 'staffs', 'employees', false],
            'designations' => ['staff', 'designations', 'designations', false],
            'jocks' => ['staff', 'jocks', 'jocks', false],
            'radio1 batches' => ['staff', 'radio1-batches', 'student_jocks_batches', false],
            'student jocks' => ['staff', 'student-jocks', 'student_jocks', false],
            'awards' => ['staff', 'awards', 'awards', false],
            'artists' => ['music', 'artists', 'artists', false],
            'albums' => ['music', 'albums', 'albums', false],
            'songs' => ['music', 'songs', 'songs', false],
            'genres' => ['music', 'genres', 'genres', false],
            'station chart' => ['music', 'station-chart', 'charts', false],
            'daily survey' => ['music', 'daily-survey-top-5', 'charts', false],
            'votes' => ['music', 'votes', 'charts', true],
            'dropouts' => ['music', 'dropouts', 'charts', false],
            'indieground artists' => ['music', 'indieground-artists', 'indiegrounds', false],
            'indieground featured' => ['music', 'indieground-featured', 'featured_indiegrounds', false],
            'articles' => ['digital-content-programs', 'articles', 'articles', false],
            'categories' => ['digital-content-programs', 'categories', 'categories', false],
            'graphics artist' => ['digital-content-programs', 'graphics-artist', 'headers', false],
            'mobile application' => ['digital-content-programs', 'mobile-application', 'mobile_app_assets', false],
            'wallpapers' => ['digital-content-programs', 'wallpapers', 'wallpapers', false],
            'music awards' => ['digital-content-programs', 'monster-music-awards', 'music_awards_releases', false],
            'shows' => ['digital-content-programs', 'shows', 'shows', false],
            'timeslots' => ['digital-content-programs', 'timeslots', 'timeslots', false],
            'podcasts' => ['digital-content-programs', 'podcasts', 'podcasts', false],
            'schools' => ['events-scholarship', 'schools', 'schools', false],
            'gimik board' => ['events-scholarship', 'gimik-board', 'gimikboards', false],
            'scholar batches' => ['events-scholarship', 'scholar-batches', 'batches', false],
            'students' => ['events-scholarship', 'students', 'students', false],
            'sponsors' => ['events-scholarship', 'sponsors', 'sponsors', false],
            'giveaways' => ['promos', 'giveaways', 'giveaways', false],
            'contestants' => ['promos', 'contestants', 'contestants', true],
            'users' => ['utilities', 'users', 'users', true],
            'reports' => ['utilities', 'reports', 'reports', false],
            'logs' => ['utilities', 'logs', 'user_logs', true],
            'archives' => ['utilities', 'archives', 'user_logs', true],
        ];
    }
}
