<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\MediaUrlResolver;
use App\Support\ResourceAudioStorage;
use App\Support\ResourceDefinitionRegistry;
use App\Support\ResourceImageStorage;
use App\Support\ResourcePresenter;
use App\Support\RichTextSanitizer;
use App\Support\StationContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

final class ResourceModuleController extends Controller
{
    private const SOCIAL_NETWORKS = ['Facebook', 'X', 'Instagram', 'TikTok', 'YouTube', 'Spotify', 'Website', 'Other'];

    public function __construct(
        private ResourceDefinitionRegistry $registry,
        private StationContext $stations,
        private ResourceImageStorage $images,
        private ResourceAudioStorage $audio,
        private RichTextSanitizer $richText,
        private ResourcePresenter $presenter
    ) {}

    public function details(Request $request, string $section, string $item, int $id): JsonResponse
    {
        $resource = $this->registry->resolve($section, $item);
        $record = $this->findParent($resource, $id);

        $details = match ($item) {
            'jocks' => $this->jockDetails($id),
            'radio1-batches' => $this->batchDetails($id),
            'student-jocks' => $this->studentJockDetails($id),
            'articles' => $this->articleDetails($id),
            'songs' => $this->songDetails($record),
            'shows' => $this->showDetails($id),
            'timeslots' => $this->timeslotDetails($id),
            'scholar-batches' => $this->scholarBatchDetails($id),
            'mobile-application' => $this->mobileDetails($record),
            'monster-music-awards' => $this->musicAwardsDetails($id),
            default => [],
        };

        return $this->successResponse(['details' => $details], 'Related data loaded successfully.');
    }

    public function storeChild(
        Request $request,
        string $section,
        string $item,
        int $id,
        string $relation
    ): JsonResponse {
        $resource = $this->authorizedResource($request, $section, $item);
        $this->findParent($resource, $id);

        $record = match ("{$item}:{$relation}") {
            'jocks:facts' => $this->storeFact($request, $id),
            'jocks:images' => $this->storeJockImage($request, $id),
            'jocks:links' => $this->storeSocial($request, 'jock_id', $id),
            'jocks:shows' => $this->attachShow($request, $id),
            'radio1-batches:students' => $this->attachStudentJock($request, $id),
            'student-jocks:socials' => $this->storeSocial($request, 'student_jock_id', $id),
            'articles:related' => $this->attachRelatedArticle($request, $id),
            'articles:sub-contents' => $this->storeArticleContent($request, $id),
            'monster-music-awards:awards' => $this->storeMusicAward($request, $id),
            'shows:jocks' => $this->attachShowJock($request, $id),
            'shows:timeslots' => $this->storeShowTimeslot($request, $id),
            'shows:images' => $this->storeShowImage($request, $id),
            'shows:podcasts' => $this->storeShowPodcast($request, $id),
            'timeslots:jocks' => $this->attachTimeslotJock($request, $id),
            'scholar-batches:students' => $this->storeScholarStudent($request, $id),
            'scholar-batches:sponsors' => $this->storeScholarSponsor($request, $id),
            default => throw ValidationException::withMessages(['relation' => 'This related record type is not supported.']),
        };

        return $this->successResponse(['record' => $record], 'Related record saved successfully.', 201);
    }

    public function updateChild(
        Request $request,
        string $section,
        string $item,
        int $id,
        string $relation,
        int $childId
    ): JsonResponse {
        $resource = $this->authorizedResource($request, $section, $item);
        $this->findParent($resource, $id);

        $record = match ("{$item}:{$relation}") {
            'jocks:facts' => $this->updateFact($request, $id, $childId),
            'jocks:images' => $this->updateJockImage($request, $id, $childId),
            'jocks:links' => $this->updateSocial($request, 'jock_id', $id, $childId),
            'student-jocks:socials' => $this->updateSocial($request, 'student_jock_id', $id, $childId),
            'articles:sub-contents' => $this->updateArticleContent($request, $id, $childId),
            'mobile-application:titles' => $this->updateMobileTitles($request, $id, $childId),
            'monster-music-awards:awards' => $this->updateMusicAward($request, $id, $childId),
            'shows:timeslots' => $this->updateShowTimeslot($request, $id, $childId),
            'shows:images' => $this->updateShowImage($request, $id, $childId),
            'shows:podcasts' => $this->updateShowPodcast($request, $id, $childId),
            'scholar-batches:students' => $this->updateScholarStudent($request, $id, $childId),
            'scholar-batches:sponsors' => $this->updateScholarSponsor($request, $id, $childId),
            default => throw ValidationException::withMessages(['relation' => 'This related record type cannot be updated.']),
        };

        return $this->successResponse(['record' => $record], 'Related record updated successfully.');
    }

    public function destroyChild(
        Request $request,
        string $section,
        string $item,
        int $id,
        string $relation,
        int $childId
    ): JsonResponse {
        $resource = $this->authorizedResource($request, $section, $item);
        $this->findParent($resource, $id);

        match ("{$item}:{$relation}") {
            'jocks:facts' => $this->deleteScoped('facts', 'jock_id', $id, $childId),
            'jocks:images' => $this->deleteScoped('images', 'jock_id', $id, $childId),
            'jocks:links' => $this->deleteScoped('links', 'jock_id', $id, $childId),
            'jocks:shows' => DB::table('jock_show')->where('jock_id', $id)->where('show_id', $childId)->delete(),
            'radio1-batches:students' => DB::table('student_jock_student_jock_batch')
                ->where('student_jock_batch_id', $id)
                ->where('student_jock_id', $childId)
                ->delete(),
            'student-jocks:socials' => $this->deleteScoped('links', 'student_jock_id', $id, $childId),
            'articles:related' => $this->deleteScoped('relateds', 'article_id', $id, $childId),
            'articles:sub-contents' => $this->deleteScoped('sub_contents', 'article_id', $id, $childId),
            'monster-music-awards:awards' => $this->deleteScoped('music_awards', 'music_awards_releases_id', $id, $childId, false),
            'shows:jocks' => DB::table('jock_show')->where('show_id', $id)->where('jock_id', $childId)->delete(),
            'shows:timeslots' => $this->deleteScoped('timeslots', 'show_id', $id, $childId),
            'shows:images' => $this->deleteScoped('images', 'show_id', $id, $childId),
            'shows:podcasts' => $this->deleteScoped('podcasts', 'show_id', $id, $childId),
            'timeslots:jocks' => DB::table('jock_timeslot')->where('timeslot_id', $id)->where('jock_id', $childId)->delete(),
            'scholar-batches:students' => $this->detachScholarStudent($id, $childId),
            'scholar-batches:sponsors' => DB::table('batch_sponsor')->where('batch_id', $id)->where('sponsor_id', $childId)->delete(),
            default => throw ValidationException::withMessages(['relation' => 'This related record type cannot be removed.']),
        };

        return $this->successResponse(null, 'Related record removed successfully.');
    }

    public function action(
        Request $request,
        string $section,
        string $item,
        int $id,
        string $action
    ): JsonResponse {
        $resource = $this->authorizedResource($request, $section, $item);
        $this->findParent($resource, $id);

        if ($item === 'articles' && in_array($action, ['publish', 'unpublish'], true)) {
            DB::table('articles')->where('id', $id)->update([
                'published_at' => $action === 'publish' ? now()->toDateString() : null,
                'updated_at' => now(),
            ]);

            return $this->successResponse(
                ['published' => $action === 'publish'],
                $action === 'publish' ? 'Article published successfully.' : 'Article unpublished successfully.'
            );
        }

        if ($item === 'graphics-artist' && $action === 'reorder') {
            $validated = $request->validate([
                'ids' => ['required', 'array', 'min:1'],
                'ids.*' => ['required', 'integer', 'distinct'],
            ]);
            $available = DB::table('headers')->whereNull('deleted_at')
                ->where('location', $this->stations->current())
                ->whereIn('id', $validated['ids'])
                ->count();

            if ($available !== count($validated['ids'])) {
                throw ValidationException::withMessages(['ids' => 'One or more graphics cannot be reordered for this station.']);
            }

            DB::transaction(function () use ($validated): void {
                foreach ($validated['ids'] as $index => $headerId) {
                    DB::table('headers')->where('id', $headerId)->update([
                        'number' => $index + 1,
                        'updated_at' => now(),
                    ]);
                }
            });

            return $this->successResponse(null, 'Graphic order saved successfully.');
        }

        if ($item === 'songs' && $action === 'upload-sample') {
            $validated = $request->validate([
                'sample' => ['required', 'file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-m4a,audio/ogg,application/ogg,audio/wav,audio/x-wav,audio/wave', 'max:20480'],
            ]);
            $stored = $this->audio->store($validated['sample']);

            try {
                DB::table('songs')->where('id', $id)->update([
                    'type' => 'sample',
                    'track_link' => $stored['name'],
                    'updated_at' => now(),
                ]);
            } catch (Throwable $exception) {
                $this->audio->discard($stored);
                throw $exception;
            }

            return $this->successResponse(['track_url' => app(MediaUrlResolver::class)->audio($stored['name'])], 'Track sample saved successfully.');
        }

        throw ValidationException::withMessages(['action' => 'This module action is not supported.']);
    }

    private function jockDetails(int $id): array
    {
        $facts = DB::table('facts')->where('jock_id', $id)->whereNull('deleted_at')->orderBy('id')->get();
        $images = DB::table('images')->where('jock_id', $id)->whereNull('deleted_at')->latest('id')->get()
            ->map(function (object $image): object {
                $image->image_url = $this->presenter->imageUrl('jocks', $image->file);
                $image->fallback_image_url = $this->presenter->fallbackImageUrl();

                return $image;
            });
        $links = DB::table('links')->where('jock_id', $id)->whereNull('deleted_at')->orderBy('website')->get();
        $shows = DB::table('jock_show')
            ->join('shows', 'shows.id', '=', 'jock_show.show_id')
            ->where('jock_show.jock_id', $id)
            ->whereNull('shows.deleted_at')
            ->get(['shows.id', 'shows.title']);
        $availableShows = DB::table('shows')->whereNull('deleted_at')
            ->where('location', $this->stations->current())
            ->whereNotIn('id', $shows->pluck('id'))
            ->orderBy('title')
            ->get(['id', 'title']);

        return compact('facts', 'images', 'links', 'shows', 'availableShows') + [
            'social_networks' => self::SOCIAL_NETWORKS,
        ];
    }

    private function batchDetails(int $id): array
    {
        $students = DB::table('student_jock_student_jock_batch as pivot')
            ->join('student_jocks', 'student_jocks.id', '=', 'pivot.student_jock_id')
            ->leftJoin('schools', 'schools.id', '=', 'student_jocks.school_id')
            ->where('pivot.student_jock_batch_id', $id)
            ->whereNull('pivot.deleted_at')
            ->whereNull('student_jocks.deleted_at')
            ->orderBy('student_jocks.position')
            ->orderBy('student_jocks.first_name')
            ->get([
                'student_jocks.id', 'student_jocks.first_name', 'student_jocks.last_name',
                'student_jocks.nickname', 'student_jocks.position', 'student_jocks.image', 'schools.name as school',
            ])->map(function (object $student): object {
                $student->image_url = $this->presenter->imageUrl('studentJocks', $student->image);
                $student->fallback_image_url = $this->presenter->fallbackImageUrl();
                $student->position_label = $this->positionLabel($student->position);

                return $student;
            });
        $availableStudents = DB::table('student_jocks')->whereNull('deleted_at')
            ->whereNotIn('id', $students->pluck('id'))
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'nickname']);

        return compact('students', 'availableStudents') + [
            'positions' => $this->positions(),
        ];
    }

    private function studentJockDetails(int $id): array
    {
        $socials = DB::table('links')->where('student_jock_id', $id)->whereNull('deleted_at')->orderBy('website')->get();
        $batches = DB::table('student_jock_student_jock_batch as pivot')
            ->join('student_jocks_batches as batches', 'batches.id', '=', 'pivot.student_jock_batch_id')
            ->where('pivot.student_jock_id', $id)
            ->whereNull('pivot.deleted_at')
            ->get(['batches.id', 'batches.batch_number', 'batches.start_year', 'batches.end_year']);

        return compact('socials', 'batches') + [
            'social_networks' => self::SOCIAL_NETWORKS,
            'positions' => $this->positions(),
        ];
    }

    private function articleDetails(int $id): array
    {
        $related = DB::table('relateds')
            ->join('articles', 'articles.id', '=', 'relateds.related_article_id')
            ->where('relateds.article_id', $id)
            ->whereNull('relateds.deleted_at')
            ->whereNull('articles.deleted_at')
            ->get(['relateds.id', 'articles.id as article_id', 'articles.title', 'articles.published_at']);
        $options = DB::table('articles')->whereNull('deleted_at')
            ->where('location', $this->stations->current())
            ->where('id', '<>', $id)
            ->whereNotIn('id', $related->pluck('article_id'))
            ->latest('created_at')
            ->limit(500)
            ->get(['id', 'title', 'published_at']);
        $contents = DB::table('sub_contents')
            ->where('article_id', $id)
            ->whereNull('deleted_at')
            ->orderBy('id')
            ->get(['id', 'article_id', 'content', 'image'])
            ->map(function (object $content): object {
                $content->content = $this->richText->sanitize($content->content);

                return $content;
            });
        $preview = DB::table('articles')
            ->leftJoin('categories', 'categories.id', '=', 'articles.category_id')
            ->leftJoin('employees', 'employees.id', '=', 'articles.employee_id')
            ->where('articles.id', $id)
            ->first([
                'categories.name as category',
                'employees.first_name as author_first_name',
                'employees.last_name as author_last_name',
            ]);

        if ($preview !== null) {
            $preview->category = $preview->category ?: 'Uncategorized';
            $preview->author = trim($preview->author_first_name.' '.$preview->author_last_name) ?: 'Monster RX93.1';
            unset($preview->author_first_name, $preview->author_last_name);
        }

        return compact('related', 'options', 'contents', 'preview');
    }

    private function mobileDetails(object $record): array
    {
        $title = DB::table('mobile_app_titles')->where('id', $record->title_id)->first();
        $labels = [
            'logo' => 'Application logo',
            'chart_icon' => 'Chart image',
            'article_icon' => 'Article image',
            'podcast_icon' => 'Podcast image',
            'article_page_icon' => 'Article page image',
            'youtube_page_icon' => 'YouTube page image',
        ];
        $assets = collect($labels)->map(function (string $label, string $name) use ($record): array {
            $value = $record->{$name} ?? null;

            return [
                'name' => $name,
                'label' => $label,
                'value' => $value,
                'image_url' => $this->presenter->imageUrl('_assets/mobile', $value),
                'fallback_image_url' => $this->presenter->fallbackImageUrl(),
                'crop' => ['width' => 500, 'height' => 500, 'label' => 'Square application image'],
            ];
        })->values();

        return [
            'title' => $title,
            'assets' => $assets,
            'app_name' => $this->stations->current() === 'mnl' ? 'Official Monster App' : 'Monster Radio App',
        ];
    }

    private function musicAwardsDetails(int $id): array
    {
        $awards = DB::table('music_awards')->where('music_awards_releases_id', $id)->orderBy('id')->get()
            ->map(function (object $award): object {
                [$award->awardee_type, $award->awardee_name] = $this->musicAwardee($award);

                return $award;
            });

        return [
            'awards' => $awards,
            'choices' => [
                'artist' => DB::table('artists')->whereNull('deleted_at')->orderBy('name')->limit(5000)->get(['id', 'name']),
                'album' => DB::table('albums')->whereNull('deleted_at')->orderBy('name')->limit(5000)->get(['id', 'name']),
                'song' => DB::table('songs')->whereNull('deleted_at')->orderBy('name')->limit(5000)->get(['id', 'name']),
            ],
        ];
    }

    private function songDetails(object $record): array
    {
        $type = strtolower((string) ($record->type ?? '')) === 'spotify' ? 'spotify' : 'sample';

        return [
            'type' => $type,
            'track_url' => $type === 'spotify'
                ? ($record->track_link ?: null)
                : app(MediaUrlResolver::class)->audio($record->track_link),
        ];
    }

    private function showDetails(int $id): array
    {
        $jocks = DB::table('jock_show')->join('jocks', 'jocks.id', '=', 'jock_show.jock_id')
            ->where('jock_show.show_id', $id)->whereNull('jocks.deleted_at')->orderBy('jocks.name')
            ->get(['jocks.id', 'jocks.name', 'jocks.profile_image'])
            ->map(function (object $jock): object {
                $jock->image_url = $this->presenter->imageUrl('jocks', $jock->profile_image);

                return $jock;
            });
        $availableJocks = DB::table('jocks')->join('employees', 'employees.id', '=', 'jocks.employee_id')
            ->whereNull('jocks.deleted_at')->whereNull('employees.deleted_at')
            ->where('employees.location', $this->stations->current())->whereNotIn('jocks.id', $jocks->pluck('id'))
            ->orderBy('jocks.name')->get(['jocks.id', 'jocks.name']);
        $timeslots = DB::table('timeslots')->where('show_id', $id)->whereNull('deleted_at')
            ->orderByRaw("FIELD(day, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
            ->orderBy('start')->get();
        $images = DB::table('images')->where('show_id', $id)->whereNull('deleted_at')->latest('id')->get()
            ->map(function (object $image): object {
                $image->image_url = $this->presenter->imageUrl('shows', $image->file);

                return $image;
            });
        $podcasts = DB::table('podcasts')->where('show_id', $id)->whereNull('deleted_at')
            ->orderByDesc('date')->limit(100)->get()
            ->map(function (object $podcast): object {
                $podcast->image_url = $this->presenter->imageUrl('podcasts', $podcast->image);

                return $podcast;
            });

        return compact('jocks', 'availableJocks', 'timeslots', 'images', 'podcasts') + [
            'days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
        ];
    }

    private function timeslotDetails(int $id): array
    {
        $jocks = DB::table('jock_timeslot')->join('jocks', 'jocks.id', '=', 'jock_timeslot.jock_id')
            ->where('jock_timeslot.timeslot_id', $id)->whereNull('jock_timeslot.deleted_at')
            ->whereNull('jocks.deleted_at')->orderBy('jocks.name')->get(['jocks.id', 'jocks.name']);
        $availableJocks = DB::table('jocks')->join('employees', 'employees.id', '=', 'jocks.employee_id')
            ->whereNull('jocks.deleted_at')->whereNull('employees.deleted_at')
            ->where('employees.location', $this->stations->current())->whereNotIn('jocks.id', $jocks->pluck('id'))
            ->orderBy('jocks.name')->get(['jocks.id', 'jocks.name']);

        return compact('jocks', 'availableJocks');
    }

    private function storeFact(Request $request, int $jockId): object
    {
        $validated = $request->validate(['content' => ['required', 'string', 'max:5000']]);
        $id = DB::table('facts')->insertGetId($validated + ['jock_id' => $jockId, 'created_at' => now(), 'updated_at' => now()]);

        return DB::table('facts')->find($id);
    }

    private function updateFact(Request $request, int $jockId, int $id): object
    {
        $validated = $request->validate(['content' => ['required', 'string', 'max:5000']]);
        $this->scopedChild('facts', 'jock_id', $jockId, $id);
        DB::table('facts')->where('id', $id)->update($validated + ['updated_at' => now()]);

        return DB::table('facts')->find($id);
    }

    private function storeJockImage(Request $request, int $jockId): object
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'file' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:12288', 'dimensions:width=500,height=500'],
        ]);
        $stored = $this->images->store($validated['file'], ['directory' => 'jocks']);

        try {
            $id = DB::table('images')->insertGetId([
                'jock_id' => $jockId,
                'name' => $validated['name'],
                'file' => $stored['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (Throwable $exception) {
            $this->images->discard([$stored]);
            throw $exception;
        }

        return DB::table('images')->find($id);
    }

    private function updateJockImage(Request $request, int $jockId, int $id): object
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:12288', 'dimensions:width=500,height=500'],
        ]);
        $this->scopedChild('images', 'jock_id', $jockId, $id);
        $payload = ['name' => $validated['name'], 'updated_at' => now()];
        $stored = null;

        if ($request->hasFile('file')) {
            $stored = $this->images->store($validated['file'], ['directory' => 'jocks']);
            $payload['file'] = $stored['name'];
        }

        try {
            DB::table('images')->where('id', $id)->update($payload);
        } catch (Throwable $exception) {
            $this->images->discard(array_filter([$stored]));
            throw $exception;
        }

        return DB::table('images')->find($id);
    }

    private function storeSocial(Request $request, string $foreignKey, int $parentId): object
    {
        $validated = $this->validateSocial($request);
        $id = DB::table('links')->insertGetId($validated + [$foreignKey => $parentId, 'created_at' => now(), 'updated_at' => now()]);

        return DB::table('links')->find($id);
    }

    private function updateSocial(Request $request, string $foreignKey, int $parentId, int $id): object
    {
        $validated = $this->validateSocial($request);
        $this->scopedChild('links', $foreignKey, $parentId, $id);
        DB::table('links')->where('id', $id)->update($validated + ['updated_at' => now()]);

        return DB::table('links')->find($id);
    }

    private function validateSocial(Request $request): array
    {
        return $request->validate([
            'website' => ['required', 'string', Rule::in(self::SOCIAL_NETWORKS)],
            'url' => ['required', 'url', 'max:255'],
        ]);
    }

    private function attachShow(Request $request, int $jockId): object
    {
        $validated = $request->validate(['show_id' => ['required', 'integer', 'exists:shows,id']]);
        DB::table('jock_show')->updateOrInsert(
            ['jock_id' => $jockId, 'show_id' => $validated['show_id']],
            ['updated_at' => now(), 'created_at' => now()]
        );

        return DB::table('shows')->find($validated['show_id']);
    }

    private function attachStudentJock(Request $request, int $batchId): object
    {
        $validated = $request->validate([
            'student_jock_id' => ['required', 'integer', 'exists:student_jocks,id'],
            'position' => ['required', 'integer', Rule::in(array_keys($this->positions()))],
        ]);
        DB::transaction(function () use ($batchId, $validated): void {
            DB::table('student_jocks')->where('id', $validated['student_jock_id'])->update([
                'position' => $validated['position'],
                'updated_at' => now(),
            ]);
            DB::table('student_jock_student_jock_batch')->updateOrInsert(
                ['student_jock_batch_id' => $batchId, 'student_jock_id' => $validated['student_jock_id']],
                ['deleted_at' => null, 'created_at' => now()]
            );
        });

        return DB::table('student_jocks')->find($validated['student_jock_id']);
    }

    private function attachRelatedArticle(Request $request, int $articleId): object
    {
        $validated = $request->validate([
            'related_article_id' => [
                'required', 'integer', 'different:article_id', 'exists:articles,id',
                Rule::unique('relateds', 'related_article_id')->where('article_id', $articleId)->whereNull('deleted_at'),
            ],
        ]);
        if ((int) $validated['related_article_id'] === $articleId) {
            throw ValidationException::withMessages([
                'related_article_id' => 'An article cannot be related to itself.',
            ]);
        }
        $id = DB::table('relateds')->insertGetId([
            'article_id' => $articleId,
            'related_article_id' => $validated['related_article_id'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('relateds')->find($id);
    }

    private function storeArticleContent(Request $request, int $articleId): object
    {
        $content = $this->validatedArticleContent($request);
        $id = DB::table('sub_contents')->insertGetId([
            'article_id' => $articleId,
            'content' => $content,
            'image' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('sub_contents')->find($id);
    }

    private function updateArticleContent(Request $request, int $articleId, int $contentId): object
    {
        $this->scopedChild('sub_contents', 'article_id', $articleId, $contentId);
        DB::table('sub_contents')->where('id', $contentId)->update([
            'content' => $this->validatedArticleContent($request),
            'updated_at' => now(),
        ]);

        return DB::table('sub_contents')->find($contentId);
    }

    private function validatedArticleContent(Request $request): string
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:500000'],
        ]);
        $content = $this->richText->sanitize($validated['content']);
        $hasText = trim(html_entity_decode(strip_tags($content))) !== '';
        $hasImage = str_contains(strtolower($content), '<img');

        if (! $hasText && ! $hasImage) {
            throw ValidationException::withMessages([
                'content' => 'Enter article content before saving.',
            ]);
        }

        return $content;
    }

    private function updateMobileTitles(Request $request, int $assetId, int $titleId): object
    {
        $asset = DB::table('mobile_app_assets')->where('id', $assetId)->where('title_id', $titleId)->first();
        abort_if($asset === null, 404);
        $fields = [
            'chart_title', 'chart_sub_title', 'article_title', 'article_sub_title', 'podcast_title',
            'podcast_sub_title', 'articles_main_page_title', 'articles_main_page_subtitle',
            'podcast_main_page_title', 'youtube_main_page_title',
        ];
        $rules = array_fill_keys($fields, ['nullable', 'string', 'max:500']);
        $validated = $request->validate($rules);
        DB::table('mobile_app_titles')->where('id', $titleId)->update($validated + ['updated_at' => now()]);

        return DB::table('mobile_app_titles')->find($titleId);
    }

    private function storeMusicAward(Request $request, int $releaseId): object
    {
        $payload = $this->musicAwardPayload($request) + [
            'music_awards_releases_id' => $releaseId,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $id = DB::table('music_awards')->insertGetId($payload);

        return DB::table('music_awards')->find($id);
    }

    private function updateMusicAward(Request $request, int $releaseId, int $id): object
    {
        $this->scopedChild('music_awards', 'music_awards_releases_id', $releaseId, $id, false);
        DB::table('music_awards')->where('id', $id)->update($this->musicAwardPayload($request) + ['updated_at' => now()]);

        return DB::table('music_awards')->find($id);
    }

    private function musicAwardPayload(Request $request): array
    {
        $validated = $request->validate([
            'award_name' => ['required', 'string', 'min:2', 'max:255'],
            'award_type' => ['required', Rule::in(['artist', 'album', 'song'])],
            'awardee_id' => ['required', 'integer'],
            'is_featured' => ['nullable', 'boolean'],
        ]);
        $table = $validated['award_type'].'s';
        $column = $validated['award_type'].'_id';
        abort_unless(DB::table($table)->where('id', $validated['awardee_id'])->exists(), 422, 'The selected award recipient was not found.');

        return [
            'award_name' => $validated['award_name'],
            'award_type' => $validated['award_type'],
            'artist_id' => $column === 'artist_id' ? $validated['awardee_id'] : null,
            'album_id' => $column === 'album_id' ? $validated['awardee_id'] : null,
            'song_id' => $column === 'song_id' ? $validated['awardee_id'] : null,
            'is_featured' => (bool) ($validated['is_featured'] ?? false),
        ];
    }

    private function musicAwardee(object $award): array
    {
        foreach (['artist' => 'artists', 'album' => 'albums', 'song' => 'songs'] as $type => $table) {
            $id = $award->{$type.'_id'} ?? null;
            if ($id) {
                return [$type, DB::table($table)->where('id', $id)->value('name') ?? 'Unknown'];
            }
        }

        return ['', 'Unassigned'];
    }

    private function attachShowJock(Request $request, int $showId): object
    {
        $validated = $request->validate(['jock_id' => ['required', 'integer', 'exists:jocks,id']]);
        $allowed = DB::table('jocks')->join('employees', 'employees.id', '=', 'jocks.employee_id')
            ->where('jocks.id', $validated['jock_id'])->where('employees.location', $this->stations->current())
            ->whereNull('jocks.deleted_at')->whereNull('employees.deleted_at')->exists();
        abort_unless($allowed, 422, 'The selected Jock is not available for this station.');
        DB::table('jock_show')->updateOrInsert(
            ['show_id' => $showId, 'jock_id' => $validated['jock_id']],
            ['created_at' => now(), 'updated_at' => now()]
        );

        return DB::table('jocks')->find($validated['jock_id']);
    }

    private function storeShowTimeslot(Request $request, int $showId): object
    {
        $validated = $this->validateTimeslot($request);
        $id = DB::table('timeslots')->insertGetId($validated + [
            'show_id' => $showId,
            'location' => $this->stations->current(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('timeslots')->find($id);
    }

    private function updateShowTimeslot(Request $request, int $showId, int $id): object
    {
        $this->scopedChild('timeslots', 'show_id', $showId, $id);
        DB::table('timeslots')->where('id', $id)->update($this->validateTimeslot($request) + ['updated_at' => now()]);

        return DB::table('timeslots')->find($id);
    }

    private function validateTimeslot(Request $request): array
    {
        $validated = $request->validate([
            'day' => ['required', Rule::in(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])],
            'start' => ['required', 'date_format:H:i'],
            'end' => ['required', 'date_format:H:i', 'after:start'],
        ]);

        return $validated;
    }

    private function storeShowImage(Request $request, int $showId): object
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'file' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:12288', 'dimensions:width=500,height=500'],
        ]);
        $stored = $this->images->store($validated['file'], ['directory' => 'shows']);

        try {
            $id = DB::table('images')->insertGetId([
                'show_id' => $showId,
                'name' => $validated['name'],
                'file' => $stored['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (Throwable $exception) {
            $this->images->discard([$stored]);
            throw $exception;
        }

        return DB::table('images')->find($id);
    }

    private function updateShowImage(Request $request, int $showId, int $id): object
    {
        $validated = $request->validate(['name' => ['required', 'string', 'max:255']]);
        $this->scopedChild('images', 'show_id', $showId, $id);
        DB::table('images')->where('id', $id)->update($validated + ['updated_at' => now()]);

        return DB::table('images')->find($id);
    }

    private function storeShowPodcast(Request $request, int $showId): object
    {
        [$validated, $stored] = $this->validatedPodcast($request);

        try {
            $id = DB::table('podcasts')->insertGetId($validated + [
                'show_id' => $showId,
                'location' => $this->stations->current(),
                'image' => $stored['name'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (Throwable $exception) {
            $this->images->discard(array_filter([$stored]));
            throw $exception;
        }

        return DB::table('podcasts')->find($id);
    }

    private function updateShowPodcast(Request $request, int $showId, int $id): object
    {
        $this->scopedChild('podcasts', 'show_id', $showId, $id);
        [$validated, $stored] = $this->validatedPodcast($request, false);
        if ($stored) {
            $validated['image'] = $stored['name'];
        }

        try {
            DB::table('podcasts')->where('id', $id)->update($validated + ['updated_at' => now()]);
        } catch (Throwable $exception) {
            $this->images->discard(array_filter([$stored]));
            throw $exception;
        }

        return DB::table('podcasts')->find($id);
    }

    /** @return array{0: array<string, mixed>, 1: array{name: string, path: string}|null} */
    private function validatedPodcast(Request $request, bool $creating = true): array
    {
        $validated = $request->validate([
            'episode' => [$creating ? 'required' : 'sometimes', 'string', 'max:255'],
            'date' => [$creating ? 'required' : 'sometimes', 'date'],
            'link' => [$creating ? 'required' : 'sometimes', 'url', 'max:2000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:12288', 'dimensions:width=500,height=500'],
        ]);
        $stored = $request->hasFile('image')
            ? $this->images->store($request->file('image'), ['directory' => 'podcasts'])
            : null;
        unset($validated['image']);

        return [$validated, $stored];
    }

    private function attachTimeslotJock(Request $request, int $timeslotId): object
    {
        $validated = $request->validate(['jock_id' => ['required', 'integer', 'exists:jocks,id']]);
        $belongsToStation = DB::table('jocks')
            ->join('employees', 'employees.id', '=', 'jocks.employee_id')
            ->where('jocks.id', $validated['jock_id'])
            ->where('employees.location', $this->stations->current())
            ->whereNull('jocks.deleted_at')
            ->whereNull('employees.deleted_at')
            ->exists();
        abort_unless($belongsToStation, 422, 'The selected Jock is not available for the current station.');

        DB::table('jock_timeslot')->updateOrInsert(
            ['timeslot_id' => $timeslotId, 'jock_id' => $validated['jock_id']],
            ['deleted_at' => null, 'created_at' => now(), 'updated_at' => now()]
        );

        return DB::table('jocks')->find($validated['jock_id']);
    }

    private function scholarBatchDetails(int $batchId): array
    {
        $station = $this->stations->current();
        $students = DB::table('batch_student as pivot')
            ->join('students', 'students.id', '=', 'pivot.student_id')
            ->leftJoin('schools', 'schools.id', '=', 'students.school_id')
            ->leftJoin('scholars', function ($join) use ($batchId): void {
                $join->on('scholars.student_id', '=', 'students.id')
                    ->where('scholars.batch_id', '=', $batchId)
                    ->whereNull('scholars.deleted_at');
            })
            ->where('pivot.batch_id', $batchId)
            ->where('students.location', $station)
            ->whereNull('students.deleted_at')
            ->orderBy('students.last_name')
            ->get([
                'students.id', 'students.school_id', 'students.first_name', 'students.middle_name',
                'students.last_name', 'students.course', 'students.year_level', 'students.data',
                'schools.name as school', 'scholars.scholar_type',
            ]);
        $sponsors = DB::table('batch_sponsor as pivot')
            ->join('sponsors', 'sponsors.id', '=', 'pivot.sponsor_id')
            ->where('pivot.batch_id', $batchId)
            ->whereNull('sponsors.deleted_at')
            ->orderBy('sponsors.name')
            ->get(['sponsors.id', 'sponsors.name', 'sponsors.remarks']);
        $availableStudents = DB::table('students')->where('location', $station)->whereNull('deleted_at')
            ->whereNotIn('id', $students->pluck('id'))->orderBy('last_name')
            ->get(['id', 'first_name', 'last_name']);
        $availableSponsors = DB::table('sponsors')->whereNull('deleted_at')
            ->whereNotIn('id', $sponsors->pluck('id'))->orderBy('name')->get(['id', 'name']);
        $schools = DB::table('schools')->where('location', $station)->whereNull('deleted_at')
            ->orderBy('name')->get(['id', 'name']);

        return compact('students', 'sponsors', 'availableStudents', 'availableSponsors', 'schools') + [
            'scholarTypes' => [0 => 'Official', 1 => 'Sponsored'],
        ];
    }

    private function storeScholarStudent(Request $request, int $batchId): object
    {
        $station = $this->stations->current();
        $validated = $request->validate([
            'student_id' => ['nullable', 'integer', 'exists:students,id'],
            'school_id' => ['required_without:student_id', 'nullable', 'integer', 'exists:schools,id'],
            'first_name' => ['required_without:student_id', 'nullable', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required_without:student_id', 'nullable', 'string', 'max:255'],
            'course' => ['nullable', 'string', 'max:255'],
            'year_level' => ['nullable', 'integer', 'min:1', 'max:20'],
            'data' => ['nullable', 'string'],
            'scholar_type' => ['required', Rule::in([0, 1, '0', '1'])],
        ]);

        return DB::transaction(function () use ($batchId, $station, $validated): object {
            if (! isset($validated['student_id'])) {
                $this->assertSchoolBelongsToStation((int) $validated['school_id'], $station);
            }

            $studentId = isset($validated['student_id'])
                ? (int) $validated['student_id']
                : DB::table('students')->insertGetId([
                    'school_id' => $validated['school_id'],
                    'first_name' => $validated['first_name'],
                    'middle_name' => $validated['middle_name'] ?? null,
                    'last_name' => $validated['last_name'],
                    'course' => $validated['course'] ?? null,
                    'year_level' => $validated['year_level'] ?? null,
                    'data' => $validated['data'] ?? null,
                    'location' => $station,
                    'image' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            $student = DB::table('students')->where('id', $studentId)->where('location', $station)->whereNull('deleted_at')->first();
            abort_if($student === null, 422, 'The selected student is not available for the current station.');
            abort_if(DB::table('batch_student')->where('batch_id', $batchId)->where('student_id', $studentId)->exists(), 422, 'This student is already assigned to the batch.');

            DB::table('batch_student')->insert(['batch_id' => $batchId, 'student_id' => $studentId, 'created_at' => now(), 'updated_at' => now()]);
            DB::table('scholars')->updateOrInsert(
                ['batch_id' => $batchId, 'student_id' => $studentId],
                ['scholar_type' => (int) $validated['scholar_type'], 'deleted_at' => null, 'created_at' => now(), 'updated_at' => now()]
            );

            return $student;
        });
    }

    private function updateScholarStudent(Request $request, int $batchId, int $studentId): object
    {
        abort_unless(DB::table('batch_student')->where('batch_id', $batchId)->where('student_id', $studentId)->exists(), 404);
        $validated = $request->validate([
            'school_id' => ['sometimes', 'required', 'integer', 'exists:schools,id'],
            'first_name' => ['sometimes', 'required', 'string', 'max:255'],
            'middle_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'last_name' => ['sometimes', 'required', 'string', 'max:255'],
            'course' => ['sometimes', 'nullable', 'string', 'max:255'],
            'year_level' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:20'],
            'data' => ['sometimes', 'nullable', 'string'],
            'scholar_type' => ['sometimes', Rule::in([0, 1, '0', '1'])],
        ]);
        $scholarType = $validated['scholar_type'] ?? null;
        unset($validated['scholar_type']);
        $station = $this->stations->current();
        if (isset($validated['school_id'])) {
            $this->assertSchoolBelongsToStation((int) $validated['school_id'], $station);
        }
        if ($validated !== []) {
            $updated = DB::table('students')->where('id', $studentId)->where('location', $station)
                ->whereNull('deleted_at')->update($validated + ['updated_at' => now()]);
            abort_if($updated === 0 && ! DB::table('students')->where('id', $studentId)->where('location', $station)->whereNull('deleted_at')->exists(), 404);
        }
        if ($scholarType !== null) {
            DB::table('scholars')->updateOrInsert(
                ['batch_id' => $batchId, 'student_id' => $studentId],
                ['scholar_type' => (int) $scholarType, 'deleted_at' => null, 'updated_at' => now()]
            );
        }

        return DB::table('students')->find($studentId);
    }

    private function detachScholarStudent(int $batchId, int $studentId): void
    {
        abort_unless(DB::table('batch_student')->where('batch_id', $batchId)->where('student_id', $studentId)->exists(), 404);
        DB::transaction(function () use ($batchId, $studentId): void {
            DB::table('batch_student')->where('batch_id', $batchId)->where('student_id', $studentId)->delete();
            DB::table('scholars')->where('batch_id', $batchId)->where('student_id', $studentId)->whereNull('deleted_at')
                ->update(['deleted_at' => now(), 'updated_at' => now()]);
        });
    }

    private function storeScholarSponsor(Request $request, int $batchId): object
    {
        $validated = $request->validate([
            'sponsor_id' => ['nullable', 'integer', 'exists:sponsors,id'],
            'name' => ['required_without:sponsor_id', 'nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
        ]);
        $sponsorId = isset($validated['sponsor_id'])
            ? (int) $validated['sponsor_id']
            : DB::table('sponsors')->insertGetId([
                'name' => $validated['name'], 'remarks' => $validated['remarks'] ?? null,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        abort_if(DB::table('batch_sponsor')->where('batch_id', $batchId)->where('sponsor_id', $sponsorId)->exists(), 422, 'This sponsor is already assigned to the batch.');
        DB::table('batch_sponsor')->insert(['batch_id' => $batchId, 'sponsor_id' => $sponsorId, 'created_at' => now(), 'updated_at' => now()]);

        return DB::table('sponsors')->find($sponsorId);
    }

    private function assertSchoolBelongsToStation(int $schoolId, string $station): void
    {
        $exists = DB::table('schools')
            ->where('id', $schoolId)
            ->where('location', $station)
            ->whereNull('deleted_at')
            ->exists();

        abort_unless($exists, 422, 'The selected school is not available for the current station.');
    }

    private function updateScholarSponsor(Request $request, int $batchId, int $sponsorId): object
    {
        abort_unless(DB::table('batch_sponsor')->where('batch_id', $batchId)->where('sponsor_id', $sponsorId)->exists(), 404);
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'remarks' => ['sometimes', 'nullable', 'string'],
        ]);
        DB::table('sponsors')->where('id', $sponsorId)->whereNull('deleted_at')->update($validated + ['updated_at' => now()]);

        return DB::table('sponsors')->find($sponsorId);
    }

    private function deleteScoped(
        string $table,
        string $foreignKey,
        int $parentId,
        int $id,
        bool $softDelete = true
    ): void {
        $this->scopedChild($table, $foreignKey, $parentId, $id, $softDelete);
        $query = DB::table($table)->where('id', $id);
        $softDelete
            ? $query->update(['deleted_at' => now(), 'updated_at' => now()])
            : $query->delete();
    }

    private function scopedChild(
        string $table,
        string $foreignKey,
        int $parentId,
        int $id,
        bool $softDelete = true
    ): object {
        $query = DB::table($table)->where('id', $id)->where($foreignKey, $parentId);
        if ($softDelete) {
            $query->whereNull('deleted_at');
        }
        $record = $query->first();
        abort_if($record === null, 404);

        return $record;
    }

    /** @param array<string, mixed> $resource */
    private function findParent(array $resource, int $id): object
    {
        $query = DB::table($resource['table'])->where('id', $id);
        $this->registry->applyScopes($query, $resource);
        $record = $query->first();
        abort_if($record === null, 404);

        return $record;
    }

    /** @return array<string, mixed> */
    private function authorizedResource(Request $request, string $section, string $item): array
    {
        $resource = $this->registry->resolve($section, $item);
        $level = $request->user()?->Employee?->Designation?->level;
        abort_unless(
            ! $resource['read_only'] && $level !== null && in_array((int) $level, $resource['write_levels'], true),
            403
        );

        return $resource;
    }

    private function positions(): array
    {
        return [1 => 'Heads', 2 => 'Seniors', 3 => 'Juniors', 4 => 'Babies'];
    }

    private function positionLabel(?int $position): string
    {
        return $this->positions()[$position] ?? 'Unassigned';
    }
}
