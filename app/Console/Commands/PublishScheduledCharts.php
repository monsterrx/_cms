<?php

namespace App\Console\Commands;

use App\Support\ChartType;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Throwable;

final class PublishScheduledCharts extends Command
{
    protected $signature = 'charts:publish-scheduled';

    protected $description = 'Publish chart drafts whose scheduled release time has arrived';

    public function handle(): int
    {
        $scheduleIds = DB::table('chart_publication_schedules')
            ->where('status', 'pending')
            ->where('publish_at', '<=', now())
            ->orderBy('publish_at')
            ->pluck('id');

        foreach ($scheduleIds as $scheduleId) {
            $this->publish((int) $scheduleId);
        }

        $this->info(sprintf('Processed %d scheduled chart publication(s).', $scheduleIds->count()));

        return self::SUCCESS;
    }

    private function publish(int $scheduleId): void
    {
        try {
            DB::transaction(function () use ($scheduleId): void {
                $release = DB::table('chart_publication_schedules')
                    ->where('id', $scheduleId)
                    ->lockForUpdate()
                    ->first();

                if ($release === null || $release->status !== 'pending') {
                    return;
                }

                $query = DB::table('charts')
                    ->where('location', $release->location)
                    ->where('dated', $release->chart_date)
                    ->whereNull('deleted_at');
                ChartType::apply($query, ChartType::fromKey($release->chart_type));
                $count = $query
                    ->where(fn (Builder $drafts) => $drafts->whereNull('is_posted')->orWhere('is_posted', 0))
                    ->update(['is_posted' => 1, 'updated_at' => now()]);

                if ($count === 0) {
                    DB::table('chart_publication_schedules')->where('id', $scheduleId)->update([
                        'status' => 'failed',
                        'failure_message' => 'No draft entries were available at publication time.',
                        'updated_at' => now(),
                    ]);

                    return;
                }

                DB::table('chart_publication_schedules')->where('id', $scheduleId)->update([
                    'status' => 'published',
                    'published_at' => now(),
                    'failure_message' => null,
                    'updated_at' => now(),
                ]);
            });
        } catch (Throwable $exception) {
            report($exception);
            DB::table('chart_publication_schedules')->where('id', $scheduleId)->update([
                'status' => 'failed',
                'failure_message' => 'The scheduled publication could not be completed.',
                'updated_at' => now(),
            ]);
        }
    }
}
