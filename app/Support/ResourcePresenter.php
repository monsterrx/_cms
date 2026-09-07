<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class ResourcePresenter
{
    public function __construct(private MediaUrlResolver $media) {}

    /**
     * @param  array<string, mixed>  $resource
     * @param  array<int, array<string, mixed>>  $records
     * @return array<int, array<string, mixed>>
     */
    public function decorate(array $resource, array $records): array
    {
        if ($records === []) {
            return [];
        }

        return match ($resource['item']) {
            'jocks' => $this->jocks($records),
            'radio1-batches' => $this->radioOneBatches($records),
            'student-jocks' => $this->studentJocks($records),
            'awards' => $this->awards($records),
            'indieground-artists' => $this->indiegroundArtists($records),
            'indieground-featured' => $this->featuredIndiegrounds($records),
            'schools' => $this->schools($records),
            'gimik-board' => $this->gimikBoards($records),
            'scholar-batches' => $this->scholarBatches($records),
            'giveaways' => $this->giveaways($records),
            'songs' => $this->songs($records),
            'articles' => $this->articles($records),
            'graphics-artist' => $this->graphics($records),
            'wallpapers' => $this->wallpapers($records),
            'mobile-application' => $this->mobileThemes($records),
            'monster-music-awards' => $this->musicAwards($records),
            'shows' => $this->shows($records),
            'podcasts' => $this->podcasts($records),
            'timeslots' => $this->timeslots($records),
            'votes' => $this->votes($records),
            default => $records,
        };
    }

    private function jocks(array $records): array
    {
        return array_map(fn (array $record): array => $this->withDisplay($record, [
            'image' => $this->imageUrl('jocks', $record['profile_image'] ?? null),
            'title' => $record['name'] ?? 'Unnamed Jock',
            'subtitle' => $record['moniker'] ?: 'On-air personality',
            'status' => (string) ($record['is_active'] ?? '0') === '1' ? 'Active' : 'Inactive',
            'kind' => 'jock',
        ]), $records);
    }

    private function radioOneBatches(array $records): array
    {
        $counts = DB::table('student_jock_student_jock_batch')
            ->whereNull('deleted_at')
            ->whereIn('student_jock_batch_id', array_column($records, 'id'))
            ->select('student_jock_batch_id', DB::raw('COUNT(*) as aggregate'))
            ->groupBy('student_jock_batch_id')
            ->pluck('aggregate', 'student_jock_batch_id');

        return array_map(fn (array $record): array => $this->withDisplay($record, [
            'title' => 'Batch '.$record['batch_number'],
            'subtitle' => $record['start_year'].'–'.$record['end_year'],
            'status' => ((int) ($counts[$record['id']] ?? 0)).' Student Jocks',
            'kind' => 'batch',
        ]), $records);
    }

    private function studentJocks(array $records): array
    {
        $positions = [1 => 'Heads', 2 => 'Seniors', 3 => 'Juniors', 4 => 'Babies'];

        return array_map(fn (array $record): array => $this->withDisplay($record, [
            'image' => $this->imageUrl('studentJocks', $record['image'] ?? null),
            'title' => trim(($record['first_name'] ?? '').' '.($record['last_name'] ?? '')),
            'subtitle' => $record['nickname'] ?? '',
            'status' => $positions[(int) ($record['position'] ?? 0)] ?? 'Unassigned',
            'kind' => 'student-jock',
        ]), $records);
    }

    private function awards(array $records): array
    {
        $jocks = DB::table('jocks')
            ->whereIn('id', array_values(array_filter(array_column($records, 'jock_id'))))
            ->get(['id', 'name', 'profile_image'])
            ->keyBy('id');
        $shows = DB::table('shows')
            ->whereIn('id', array_values(array_filter(array_column($records, 'show_id'))))
            ->get(['id', 'title', 'background_image'])
            ->keyBy('id');

        return array_map(function (array $record) use ($jocks, $shows): array {
            $show = $record['show_id'] ? $shows->get($record['show_id']) : null;
            $jock = $record['jock_id'] ? $jocks->get($record['jock_id']) : null;
            $isShowAward = ! empty($record['show_id']) || ($record['award_target'] ?? null) === 'show';

            $record['award_target'] = $isShowAward ? 'show' : 'jock';

            return $this->withDisplay($record, [
                'image' => $isShowAward
                    ? $this->imageUrl('shows', $show?->background_image, 'banner')
                    : $this->imageUrl('jocks', $jock?->profile_image),
                'title' => $isShowAward ? ($show?->title ?? 'Unknown Show') : ($jock?->name ?? 'Unknown Jock'),
                'subtitle' => $record['name'] ?? $record['title'] ?? 'Award',
                'status' => (string) ($record['year'] ?? ''),
                'kind' => $isShowAward ? 'show' : 'jock',
            ]);
        }, $records);
    }

    private function indiegroundArtists(array $records): array
    {
        $artists = DB::table('artists')
            ->whereIn('id', array_values(array_filter(array_column($records, 'artist_id'))))
            ->whereNull('deleted_at')
            ->pluck('name', 'id');

        return array_map(fn (array $record): array => $this->withDisplay($record, [
            'image' => $this->imageUrl('indie', $record['image'] ?? null),
            'title' => $artists[$record['artist_id']] ?? 'Unknown Indieground Artist',
            'subtitle' => Str::limit(trim((string) preg_replace('/\s+/', ' ', strip_tags((string) ($record['introduction'] ?? '')))), 100),
            'status' => 'Indieground Artist',
            'kind' => 'indieground-artist',
            'aspect' => 'square',
        ]), $records);
    }

    private function featuredIndiegrounds(array $records): array
    {
        $indiegrounds = DB::table('indiegrounds')
            ->join('artists', 'artists.id', '=', 'indiegrounds.artist_id')
            ->whereIn('indiegrounds.id', array_values(array_filter(array_column($records, 'indieground_id'))))
            ->whereNull('indiegrounds.deleted_at')
            ->whereNull('artists.deleted_at')
            ->get(['indiegrounds.id', 'indiegrounds.image', 'artists.name'])
            ->keyBy('id');

        $months = [
            '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
            '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
            '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December',
        ];

        return array_map(function (array $record) use ($indiegrounds, $months): array {
            $artist = $indiegrounds->get($record['indieground_id']);
            $month = str_pad((string) ($record['month'] ?? ''), 2, '0', STR_PAD_LEFT);
            $record['artist_name'] = $artist?->name ?? 'Unknown Indieground Artist';
            $record['feature_period'] = trim(($months[$month] ?? $month).' '.($record['year'] ?? ''));

            return $this->withDisplay($record, [
                'image' => $this->imageUrl('indie', $artist?->image),
                'title' => $record['artist_name'],
                'subtitle' => $record['feature_period'],
                'status' => 'Featured',
                'kind' => 'featured-indieground',
                'aspect' => 'square',
            ]);
        }, $records);
    }

    private function schools(array $records): array
    {
        return array_map(function (array $record): array {
            $record['_media'] = [
                'seal' => $this->imageUrl('schools', $record['seal'] ?? null),
            ];

            return $record;
        }, $records);
    }

    private function gimikBoards(array $records): array
    {
        $schools = DB::table('schools')
            ->whereIn('id', array_values(array_filter(array_column($records, 'school_id'))))
            ->whereNull('deleted_at')
            ->pluck('name', 'id');

        return array_map(function (array $record) use ($schools): array {
            $record['school_name'] = $schools[$record['school_id']] ?? 'Unknown School';

            return $record;
        }, $records);
    }

    private function scholarBatches(array $records): array
    {
        $ids = array_column($records, 'id');
        $studentCounts = DB::table('batch_student')->whereIn('batch_id', $ids)
            ->select('batch_id', DB::raw('COUNT(DISTINCT student_id) AS aggregate'))
            ->groupBy('batch_id')->pluck('aggregate', 'batch_id');
        $sponsorCounts = DB::table('batch_sponsor')->whereIn('batch_id', $ids)
            ->select('batch_id', DB::raw('COUNT(DISTINCT sponsor_id) AS aggregate'))
            ->groupBy('batch_id')->pluck('aggregate', 'batch_id');

        return array_map(fn (array $record): array => $this->withDisplay($record, [
            'image' => $this->imageUrl('scholarBatch', $record['image'] ?? null, 'banner'),
            'title' => trim(($record['semester'] ?? '').' Monster Scholars '.$record['number']),
            'subtitle' => ($record['start_year'] ?? '').'–'.($record['end_year'] ?? ''),
            'status' => ((int) ($studentCounts[$record['id']] ?? 0)).' students · '.((int) ($sponsorCounts[$record['id']] ?? 0)).' sponsors',
            'kind' => 'scholar-batch',
        ]), $records);
    }

    private function giveaways(array $records): array
    {
        $categories = ['movies' => 'Monster Movie Premiere', 'concerts' => 'Concert Tickets'];

        return array_map(fn (array $record): array => $this->withDisplay($record, [
            'image' => $this->imageUrl('giveaways', $record['image'] ?? null),
            'title' => $record['name'] ?? 'Untitled Giveaway',
            'subtitle' => $categories[$record['type'] ?? ''] ?? 'Uncategorized',
            'status' => (int) ($record['is_active'] ?? 0) === 1 ? 'Active' : 'Inactive',
            'kind' => 'giveaway',
            'aspect' => 'square',
        ]), $records);
    }

    private function songs(array $records): array
    {
        return array_map(function (array $record): array {
            $type = strtolower((string) ($record['type'] ?? ''));
            $record['type'] = $type === 'spotify' ? 'spotify' : 'sample';
            $record['_track'] = [
                'kind' => $record['type'],
                'url' => $record['type'] === 'spotify'
                    ? ($record['track_link'] ?? null)
                    : $this->media->audio($record['track_link'] ?? null),
            ];

            return $record;
        }, $records);
    }

    private function articles(array $records): array
    {
        return array_map(fn (array $record): array => $this->withDisplay($record, [
            'image' => $this->imageUrl('articles', $record['image'] ?? null),
            'title' => $record['title'] ?? 'Untitled Article',
            'subtitle' => $record['published_at'] ? 'Published '.$record['published_at'] : 'Draft',
            'status' => $record['published_at'] ? 'Published' : 'Unpublished',
            'kind' => 'article',
        ]), $records);
    }

    private function graphics(array $records): array
    {
        return array_map(fn (array $record): array => $this->withDisplay($record, [
            'image' => $this->imageUrl('headers', $record['image'] ?? null, 'banner'),
            'fallback_image' => $this->fallbackImageUrl('banner'),
            'title' => $record['title'] ?? 'Untitled Graphic',
            'subtitle' => $record['sub_title'] ?? '',
            'status' => 'Order '.($record['number'] ?? '—'),
            'kind' => 'graphic',
        ]), $records);
    }

    private function wallpapers(array $records): array
    {
        return array_map(function (array $record): array {
            $fallback = ($record['device'] ?? '') === 'mobile'
                ? 'mobile-wallpaper'
                : 'desktop-wallpaper';

            return $this->withDisplay($record, [
                'image' => $this->imageUrl('wallpapers', $record['image'] ?? null, $fallback),
                'fallback_image' => $this->fallbackImageUrl($fallback),
                'title' => $record['name'] ?? 'Untitled Wallpaper',
                'subtitle' => ($record['device'] ?? '') === 'mobile' ? 'Mobile' : 'Desktop',
                'status' => ($record['device'] ?? '') === 'mobile' ? '1080 × 1920' : '1920 × 1080',
                'kind' => $record['device'] ?? 'web',
            ]);
        }, $records);
    }

    private function mobileThemes(array $records): array
    {
        $titles = DB::table('mobile_app_titles')
            ->whereIn('id', array_values(array_filter(array_column($records, 'title_id'))))
            ->get()
            ->keyBy('id');
        $station = app(StationContext::class)->current();
        $appName = $station === 'mnl' ? 'Official Monster App' : 'Monster Radio App';

        return array_map(function (array $record) use ($appName, $titles): array {
            $record['_mobile_title'] = (array) ($titles->get($record['title_id']) ?? []);

            return $this->withDisplay($record, [
                'image' => $this->imageUrl('_assets/mobile', $record['logo'] ?? null),
                'title' => $appName,
                'subtitle' => (int) ($record['is_dark_mode'] ?? 0) === 1 ? 'Dark theme' : 'Light theme',
                'status' => (int) ($record['is_dark_mode'] ?? 0) === 1 ? 'Dark' : 'Light',
                'kind' => (int) ($record['is_dark_mode'] ?? 0) === 1 ? 'dark' : 'light',
            ]);
        }, $records);
    }

    private function musicAwards(array $records): array
    {
        $counts = DB::table('music_awards')
            ->whereIn('music_awards_releases_id', array_column($records, 'id'))
            ->select('music_awards_releases_id', DB::raw('COUNT(*) as aggregate'))
            ->groupBy('music_awards_releases_id')
            ->pluck('aggregate', 'music_awards_releases_id');

        return array_map(fn (array $record): array => $this->withDisplay($record, [
            'image' => $this->imageUrl('music_awards', $record['banner_image'] ?? null, 'banner'),
            'fallback_image' => $this->fallbackImageUrl('banner'),
            'title' => (string) ($record['release'] ?? 'Awards'),
            'subtitle' => ((int) ($counts[$record['id']] ?? 0)).' awards',
            'status' => (int) ($record['is_live'] ?? 0) === 1 ? 'Live' : 'Hidden',
            'kind' => 'music-awards',
        ]), $records);
    }

    private function shows(array $records): array
    {
        return array_map(fn (array $record): array => $this->withDisplay($record, [
            'image' => $this->imageUrl('shows', $record['icon'] ?? null),
            'secondary_image' => $this->imageUrl('shows', $record['header_image'] ?? null, 'banner'),
            'background_image' => $this->imageUrl('shows', $record['background_image'] ?? null, 'banner'),
            'title' => $record['title'] ?? 'Untitled Show',
            'subtitle' => (int) ($record['is_special'] ?? 0) === 1 ? 'Special Show' : 'Daily Show',
            'status' => (int) ($record['is_active'] ?? 0) === 1 ? 'Active' : 'Inactive',
            'kind' => (int) ($record['is_special'] ?? 0) === 1 ? 'special' : 'daily',
            'active_kind' => (int) ($record['is_active'] ?? 0) === 1 ? 'active' : 'inactive',
        ]), $records);
    }

    private function podcasts(array $records): array
    {
        $shows = DB::table('shows')
            ->whereIn('id', array_values(array_filter(array_column($records, 'show_id'))))
            ->pluck('title', 'id');

        return array_map(fn (array $record): array => $this->withDisplay($record, [
            'image' => $this->imageUrl('podcasts', $record['image'] ?? null),
            'title' => $record['episode'] ?? 'Untitled Podcast',
            'subtitle' => $shows[$record['show_id']] ?? 'Unknown Show',
            'status' => (string) ($record['date'] ?? ''),
            'kind' => 'podcast',
        ]), $records);
    }

    private function votes(array $records): array
    {
        $chartIds = array_values(array_filter(array_column($records, 'chart_id')));
        $charts = DB::table('charts')
            ->join('songs', 'songs.id', '=', 'charts.song_id')
            ->whereIn('charts.id', $chartIds)
            ->get(['charts.id', 'charts.position', 'charts.dated', 'songs.name'])
            ->keyBy('id');

        return array_map(function (array $record) use ($charts): array {
            $chart = $charts->get($record['chart_id'] ?? null);
            $record['chart_id'] = $chart
                ? "#{$chart->position} {$chart->name} ({$chart->dated})"
                : ($record['chart_id'] ?? null);

            return $record;
        }, $records);
    }

    private function timeslots(array $records): array
    {
        $shows = DB::table('shows')
            ->whereIn('id', array_values(array_filter(array_column($records, 'show_id'))))
            ->pluck('title', 'id');
        $jocks = DB::table('jock_timeslot')
            ->join('jocks', 'jocks.id', '=', 'jock_timeslot.jock_id')
            ->whereNull('jock_timeslot.deleted_at')
            ->whereIn('jock_timeslot.timeslot_id', array_column($records, 'id'))
            ->get(['jock_timeslot.timeslot_id', 'jocks.name'])
            ->groupBy('timeslot_id');

        return array_map(function (array $record) use ($shows, $jocks): array {
            $record['_schedule'] = [
                'show' => $shows[$record['show_id']] ?? null,
                'jocks' => collect($jocks[$record['id']] ?? [])->pluck('name')->values()->all(),
            ];

            return $record;
        }, $records);
    }

    /** @param array<string, mixed> $display */
    private function withDisplay(array $record, array $display): array
    {
        $display['fallback_image'] ??= $this->fallbackImageUrl();
        $record['_display'] = $display;

        return $record;
    }

    public function imageUrl(string $directory, ?string $file, string $fallback = 'default'): string
    {
        return $this->media->photo($file, $directory, $fallback, false);
    }

    public function fallbackImageUrl(string $fallback = 'default'): string
    {
        return $this->media->fallback($fallback);
    }
}
