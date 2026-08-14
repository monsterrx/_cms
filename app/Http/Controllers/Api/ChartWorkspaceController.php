<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ChartType;
use App\Support\StationContext;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

final class ChartWorkspaceController extends Controller
{
    private const STATION_SHOW_IDS = ['mnl' => 17, 'cbu' => 38, 'dav' => 29];

    public function __construct(private StationContext $stations)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'module' => ['required', Rule::in(['station', 'daily', 'dropouts'])],
            'type' => ['nullable', 'string', 'max:32'],
            'date' => ['nullable', 'date'],
            'status' => ['nullable', Rule::in(['draft', 'posted', 'all'])],
        ]);
        $station = $this->stations->current();
        $options = ChartType::options($validated['module'], $station);
        $type = $validated['type'] ?? $options[0]['value'];
        $flags = ChartType::flags($validated['module'], $type, $station);
        $base = $this->baseQuery($station, $flags);
        $status = $validated['status'] ?? 'draft';
        $dates = (clone $base)->select('charts.dated')->distinct()->orderByDesc('charts.dated')->limit(365)->pluck('dated');
        $statusDates = clone $base;
        $this->applyStatus($statusDates, $status);
        $defaultDate = $statusDates->max('charts.dated') ?? $dates->first();
        $date = $validated['date'] ?? $defaultDate ?? now()->toDateString();
        $query = (clone $base)->where('charts.dated', $date);
        $this->applyStatus($query, $status);
        $records = $query->join('songs', 'songs.id', '=', 'charts.song_id')
            ->leftJoin('albums', 'albums.id', '=', 'songs.album_id')
            ->leftJoin('artists', 'artists.id', '=', 'albums.artist_id')
            ->orderBy('charts.position')->orderBy('charts.id')
            ->get(['charts.id', 'charts.song_id', 'charts.position', 'charts.last_position', 'charts.dated', 'charts.is_posted', 'charts.is_dropped', 'songs.name as song', 'artists.name as artist']);
        $songs = DB::table('songs')->leftJoin('albums', 'albums.id', '=', 'songs.album_id')
            ->leftJoin('artists', 'artists.id', '=', 'albums.artist_id')->whereNull('songs.deleted_at')
            ->orderBy('songs.name')->limit(5000)->get(['songs.id', 'songs.name', 'artists.name as artist']);
        $schedules = DB::table('chart_publication_schedules')->where('location', $station)
            ->where('chart_date', $date)->where('chart_type', ChartType::key($flags))->orderByDesc('publish_at')->get();

        $stationShow = $this->stationShow($station);

        return $this->successResponse([
            'station_chart_name' => $stationShow->title,
            'station_show_id' => $stationShow->id,
            'types' => $options,
            'selected_type' => $type,
            'dates' => $dates,
            'selected_date' => $date,
            'status' => $status,
            'records' => $records,
            'songs' => $songs,
            'schedules' => $schedules,
            'can_write' => $this->canWrite($request),
        ], 'Chart workspace loaded successfully.');
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeWrite($request);
        $validated = $request->validate([
            'module' => ['required', Rule::in(['station', 'daily'])],
            'type' => ['required', 'string', 'max:32'],
            'date' => ['required', 'date'],
            'song_id' => ['required', 'integer', 'exists:songs,id'],
        ]);
        $station = $this->stations->current();
        $flags = ChartType::flags($validated['module'], $validated['type'], $station);
        $base = $this->baseQuery($station, $flags)->where('charts.dated', $validated['date']);
        if ((clone $base)->where('charts.song_id', $validated['song_id'])->exists()) {
            throw ValidationException::withMessages(['song_id' => 'This song is already in the selected chart.']);
        }
        $position = max(1, (int) (clone $base)->max('charts.position') + 1);
        $id = DB::table('charts')->insertGetId($flags + [
            'song_id' => $validated['song_id'], 'position' => $position, 'last_position' => 0,
            're_entry' => 0, 'dated' => $validated['date'], 'location' => $station,
            'is_posted' => null, 'votes' => 0, 'last_results' => 0, 'phone_votes' => 0,
            'social_votes' => 0, 'online_votes' => 0, 'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('songs')->where('id', $validated['song_id'])->update(['is_charted' => 1, 'updated_at' => now()]);

        return $this->successResponse(['id' => $id], 'Song added to the chart draft.', 201);
    }

    public function reorder(Request $request): JsonResponse
    {
        $this->authorizeWrite($request);
        $validated = $request->validate([
            'module' => ['required', Rule::in(['station', 'daily'])], 'type' => ['required', 'string'],
            'date' => ['required', 'date'], 'ids' => ['required', 'array', 'min:1'], 'ids.*' => ['integer', 'distinct'],
        ]);
        $station = $this->stations->current();
        $flags = ChartType::flags($validated['module'], $validated['type'], $station);
        $query = $this->baseQuery($station, $flags)
            ->where('charts.dated', $validated['date'])
            ->whereIn('charts.id', $validated['ids'])
            ->where(fn (Builder $drafts) => $drafts->whereNull('charts.is_posted')->orWhere('charts.is_posted', 0));
        abort_unless($query->count() === count($validated['ids']), 422, 'One or more chart entries do not belong to the selected chart.');
        DB::transaction(function () use ($validated): void {
            foreach ($validated['ids'] as $index => $id) DB::table('charts')->where('id', $id)->update(['position' => $index + 1, 'updated_at' => now()]);
        });
        return $this->successResponse(null, 'Chart order saved successfully.');
    }

    public function publish(Request $request): JsonResponse
    {
        $this->authorizeWrite($request);
        $validated = $request->validate(['module' => ['required', Rule::in(['station', 'daily'])], 'type' => ['required', 'string'], 'date' => ['required', 'date']]);
        $station = $this->stations->current();
        $flags = ChartType::flags($validated['module'], $validated['type'], $station);
        $count = $this->baseQuery($station, $flags)->where('charts.dated', $validated['date'])
            ->where(fn (Builder $query) => $query->whereNull('charts.is_posted')->orWhere('charts.is_posted', 0))
            ->update(['is_posted' => 1, 'updated_at' => now()]);
        abort_if($count === 0, 422, 'No draft entries were found for this chart date.');
        return $this->successResponse(['published_entries' => $count], 'The complete chart draft was published.');
    }

    public function schedule(Request $request): JsonResponse
    {
        $this->authorizeWrite($request);
        $validated = $request->validate([
            'module' => ['required', Rule::in(['station', 'daily'])], 'type' => ['required', 'string'],
            'date' => ['required', 'date'], 'publish_at' => ['required', 'date', 'after:now'],
        ]);
        $station = $this->stations->current();
        $flags = ChartType::flags($validated['module'], $validated['type'], $station);
        $draftExists = $this->baseQuery($station, $flags)
            ->where('charts.dated', $validated['date'])
            ->where(fn (Builder $query) => $query->whereNull('charts.is_posted')->orWhere('charts.is_posted', 0))
            ->exists();
        abort_unless($draftExists, 422, 'Create at least one draft entry before scheduling publication.');

        $chartType = ChartType::key($flags);
        $alreadyScheduled = DB::table('chart_publication_schedules')
            ->where('location', $station)
            ->where('chart_date', $validated['date'])
            ->where('chart_type', $chartType)
            ->where('status', 'pending')
            ->exists();
        abort_if($alreadyScheduled, 422, 'This chart already has a pending publication schedule.');

        $id = DB::table('chart_publication_schedules')->insertGetId([
            'location' => $station, 'chart_date' => $validated['date'], 'chart_type' => $chartType,
            'publish_at' => $validated['publish_at'], 'status' => 'pending', 'created_at' => now(), 'updated_at' => now(),
        ]);
        return $this->successResponse(['id' => $id], 'Chart publication scheduled successfully.', 201);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->authorizeWrite($request);
        $record = DB::table('charts')->where('id', $id)->where('location', $this->stations->current())->whereNull('deleted_at')->first();
        abort_if($record === null, 404);
        abort_if((int) $record->is_posted === 1, 422, 'Published chart entries cannot be removed.');
        DB::table('charts')->where('id', $id)->update(['deleted_at' => now(), 'updated_at' => now()]);
        return $this->successResponse(null, 'Chart entry removed successfully.');
    }

    public function votes(Request $request): JsonResponse
    {
        $validated = $request->validate(['date' => ['nullable', 'date']]);
        $station = $this->stations->current();
        $flags = ChartType::flags('station', 'official', $station);
        $base = $this->baseQuery($station, $flags);
        $dates = (clone $base)->select('charts.dated')->distinct()->orderByDesc('charts.dated')->limit(365)->pluck('dated');
        $date = $validated['date'] ?? $dates->first() ?? now()->toDateString();
        $records = (clone $base)
            ->where('charts.dated', $date)
            ->join('songs', 'songs.id', '=', 'charts.song_id')
            ->leftJoin('albums', 'albums.id', '=', 'songs.album_id')
            ->leftJoin('artists', 'artists.id', '=', 'albums.artist_id')
            ->orderBy('charts.position')
            ->orderBy('charts.id')
            ->get([
                'charts.id', 'charts.position', 'charts.dated', 'charts.online_votes',
                'charts.phone_votes', 'charts.social_votes', 'charts.voted_at',
                'songs.name as song', 'artists.name as artist',
            ])->map(static function (object $record): object {
                $record->total_votes = (int) $record->online_votes
                    + (int) $record->phone_votes
                    + (int) $record->social_votes;

                return $record;
            });
        $stationShow = $this->stationShow($station);

        return $this->successResponse([
            'station_chart_name' => $stationShow->title,
            'station_show_id' => $stationShow->id,
            'dates' => $dates,
            'selected_date' => $date,
            'records' => $records,
            'can_write' => $this->canWrite($request),
        ], 'Chart votes loaded successfully.');
    }

    public function incrementVote(Request $request, int $id): JsonResponse
    {
        $this->authorizeWrite($request);
        $validated = $request->validate([
            'channel' => ['required', Rule::in(['phone', 'social'])],
        ]);
        $station = $this->stations->current();
        $field = $validated['channel'].'_votes';

        $record = DB::transaction(function () use ($field, $id, $station): object {
            $chart = DB::table('charts')
                ->where('id', $id)
                ->where('location', $station)
                ->whereNull('deleted_at')
                ->lockForUpdate()
                ->first();
            abort_if($chart === null, 404);

            $votes = [
                'online_votes' => (int) $chart->online_votes,
                'phone_votes' => (int) $chart->phone_votes,
                'social_votes' => (int) $chart->social_votes,
            ];
            $votes[$field]++;
            DB::table('charts')->where('id', $id)->update($votes + [
                'votes' => array_sum($votes),
                'voted_at' => now()->toDateString(),
                'updated_at' => now(),
            ]);

            return DB::table('charts')->where('id', $id)->first();
        });

        return $this->successResponse([
            'id' => $record->id,
            'online_votes' => (int) $record->online_votes,
            'phone_votes' => (int) $record->phone_votes,
            'social_votes' => (int) $record->social_votes,
            'total_votes' => (int) $record->online_votes + (int) $record->phone_votes + (int) $record->social_votes,
            'voted_at' => $record->voted_at,
        ], ucfirst($validated['channel']).' vote added successfully.');
    }

    private function baseQuery(string $station, array $flags): Builder
    {
        $query = DB::table('charts')->where('charts.location', $station)->whereNull('charts.deleted_at');
        ChartType::apply($query, $flags);
        return $query;
    }

    private function applyStatus(Builder $query, string $status): void
    {
        if ($status === 'draft') $query->where(fn (Builder $q) => $q->whereNull('charts.is_posted')->orWhere('charts.is_posted', 0));
        elseif ($status === 'posted') $query->where('charts.is_posted', 1);
    }

    private function canWrite(Request $request): bool
    {
        $level = $request->user()?->Employee?->Designation?->level;
        return $level !== null && in_array((int) $level, [1, 2, 5, 6, 7], true);
    }

    private function authorizeWrite(Request $request): void
    {
        abort_unless($this->canWrite($request), 403);
    }

    private function stationShow(string $station): object
    {
        $showId = self::STATION_SHOW_IDS[$station] ?? self::STATION_SHOW_IDS['mnl'];
        $show = DB::table('shows')->where('id', $showId)->whereNull('deleted_at')->first(['id', 'title']);

        return $show ?? (object) [
            'id' => $showId,
            'title' => $station === 'mnl' ? 'Countdown Top 7' : "Monster's Top 30",
        ];
    }
}
