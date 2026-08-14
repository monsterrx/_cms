<?php

namespace Tests\Unit;

use Tests\TestCase;

class WorkspaceMediaConfigurationTest extends TestCase
{
    public function test_square_image_modules_use_the_500_pixel_crop_preset(): void
    {
        $paths = [
            'workspace.resources.staff.jocks.uploads.profile_image',
            'workspace.resources.staff.student-jocks.uploads.image',
            'workspace.resources.music.artists.uploads.image',
            'workspace.resources.music.albums.uploads.image',
            'workspace.resources.music.indieground-artists.uploads.image',
            'workspace.resources.music.indieground-featured.related_uploads.featured_artist_image',
            'workspace.resources.digital-content-programs.mobile-application.uploads.logo',
            'workspace.resources.promos.giveaways.uploads.image',
        ];

        foreach ($paths as $path) {
            $this->assertSame(500, config($path.'.width'));
            $this->assertSame(500, config($path.'.height'));
        }
    }

    public function test_wallpapers_use_the_full_hd_crop_preset(): void
    {
        $preset = config('workspace.resources.digital-content-programs.wallpapers.uploads.image');

        $this->assertSame(1920, $preset['width']);
        $this->assertSame(1080, $preset['height']);
        $this->assertSame('device', $preset['variants']['selector']);
        $this->assertSame(1920, $preset['variants']['web']['width']);
        $this->assertSame(1080, $preset['variants']['web']['height']);
        $this->assertSame(1080, $preset['variants']['mobile']['width']);
        $this->assertSame(1920, $preset['variants']['mobile']['height']);
    }

    public function test_graphics_use_the_requested_wide_crop_preset(): void
    {
        $preset = config('workspace.resources.digital-content-programs.graphics-artist.uploads.image');

        $this->assertSame(1697, $preset['width']);
        $this->assertSame(625, $preset['height']);
    }

    public function test_specialized_modules_use_card_presentations(): void
    {
        $presentations = [
            'workspace.resources.staff.jocks.presentation' => 'jock-cards',
            'workspace.resources.staff.radio1-batches.presentation' => 'batch-cards',
            'workspace.resources.staff.student-jocks.presentation' => 'student-jock-cards',
            'workspace.resources.staff.awards.presentation' => 'award-cards',
            'workspace.resources.digital-content-programs.articles.presentation' => 'article-cards',
            'workspace.resources.digital-content-programs.graphics-artist.presentation' => 'sortable-graphic-cards',
            'workspace.resources.digital-content-programs.mobile-application.presentation' => 'mobile-theme-cards',
            'workspace.resources.digital-content-programs.wallpapers.presentation' => 'wallpaper-cards',
            'workspace.resources.digital-content-programs.monster-music-awards.presentation' => 'music-award-cards',
            'workspace.resources.music.indieground-artists.presentation' => 'indieground-artist-cards',
            'workspace.resources.music.indieground-featured.presentation' => 'featured-indieground-cards',
            'workspace.resources.events-scholarship.scholar-batches.presentation' => 'scholar-batch-cards',
            'workspace.resources.promos.giveaways.presentation' => 'giveaway-cards',
        ];

        foreach ($presentations as $path => $expected) {
            $this->assertSame($expected, config($path));
        }
    }

    public function test_student_jock_positions_use_the_legacy_numeric_contract(): void
    {
        $options = config('workspace.resources.staff.student-jocks.field_options.position');

        $this->assertSame(
            [
                ['value' => 1, 'label' => 'Heads'],
                ['value' => 2, 'label' => 'Seniors'],
                ['value' => 3, 'label' => 'Juniors'],
                ['value' => 4, 'label' => 'Babies'],
            ],
            $options
        );
    }

    public function test_requested_modules_define_rich_text_fields(): void
    {
        $this->assertSame(
            ['content'],
            config('workspace.resources.music.indieground-featured.rich_text_fields')
        );
        $this->assertEmpty(config('workspace.resources.digital-content-programs.articles.rich_text_fields', []));
        $this->assertSame(
            ['title', 'description'],
            config('workspace.resources.events-scholarship.gimik-board.rich_text_fields')
        );
        $this->assertSame(
            ['description'],
            config('workspace.resources.promos.giveaways.rich_text_fields')
        );
        $this->assertSame(
            ['description'],
            config('workspace.resources.staff.jocks.rich_text_fields')
        );
        $this->assertSame(
            ['description'],
            config('workspace.resources.staff.student-jocks.rich_text_fields')
        );
    }

    public function test_scholar_giveaway_and_contestant_policies_match_the_workspace_contract(): void
    {
        $scholarImage = config('workspace.resources.events-scholarship.scholar-batches.uploads.image');
        $giveaways = config('workspace.resources.promos.giveaways');
        $contestants = config('workspace.resources.promos.contestants');

        $this->assertSame(1600, $scholarImage['width']);
        $this->assertSame(1066, $scholarImage['height']);
        $this->assertSame('Category', $giveaways['field_labels']['type']);
        $this->assertSame('General Audience?', $giveaways['field_labels']['is_restricted']);
        $this->assertSame('random_code', $giveaways['generated_fields']['code']['generator']);
        $this->assertTrue($contestants['read_only']);
        $this->assertContains('image', $contestants['hidden_fields']);
    }

    public function test_categories_hide_legacy_icons_and_podcasts_retain_cards(): void
    {
        $categories = config('workspace.resources.digital-content-programs.categories');

        $this->assertSame(['icon', 'dark_mode_icon'], $categories['hidden_fields']);
        $this->assertSame('default.png', $categories['generated_fields']['icon']['value']);
        $this->assertSame('default.png', $categories['generated_fields']['dark_mode_icon']['value']);
        $this->assertSame(
            'podcast-cards',
            config('workspace.resources.digital-content-programs.podcasts.presentation')
        );
    }

    public function test_article_identifiers_are_hidden_and_generated_by_the_backend(): void
    {
        $articles = config('workspace.resources.digital-content-programs.articles');

        $this->assertSame(['employee_id', 'unique_id'], $articles['hidden_form_fields']);
        $this->assertSame(['employee_id', 'unique_id'], $articles['readonly_fields']);
        $this->assertSame(
            'authenticated_employee_id',
            $articles['generated_fields']['employee_id']['generator']
        );
        $this->assertSame('random_code', $articles['generated_fields']['unique_id']['generator']);
        $this->assertSame(8, $articles['generated_fields']['unique_id']['length']);
        $this->assertSame('RX', $articles['generated_fields']['unique_id']['prefix']);
        $this->assertSame('931', $articles['generated_fields']['unique_id']['suffix']);
    }

    public function test_gimikboard_table_uses_school_names_and_hides_editor_only_fields(): void
    {
        $gimikboard = config('workspace.resources.events-scholarship.gimik-board');

        $this->assertSame(['id', 'school_name', 'name', 'start_date', 'end_date'], $gimikboard['columns']);
        $this->assertSame('ID', $gimikboard['field_labels']['id']);
        $this->assertSame('School', $gimikboard['field_labels']['school_id']);
        $this->assertSame('School', $gimikboard['virtual_fields']['school_name']['label']);
        $this->assertContains('school_id', $gimikboard['hidden_table_fields']);
        $this->assertContains('title', $gimikboard['hidden_table_fields']);
        $this->assertContains('is_published', $gimikboard['hidden_table_fields']);
    }
}
